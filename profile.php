<?php
session_start();
require 'db.php';

// 須登入才能使用
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// 處理更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '請填寫正確的姓名與電子郵件。';
    } else {
        $stmt = $conn->prepare('UPDATE musers SET name = ?, email = ? WHERE id = ?');
        $stmt->bind_param('ssi', $name, $email, $user_id);
        $stmt->execute();
        $stmt->close();
        $message = '資料更新成功。';
    }
}

// 取得目前資料
$stmt = $conn->prepare('SELECT name, username, email FROM musers WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($name, $username, $email);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>個人資料</title>
</head>
<body>
<h2>個人資料</h2>
<?php if ($message): ?><p><?= htmlspecialchars($message) ?></p><?php endif; ?>

<form method="post">
    <label>帳號：<input type="text" value="<?= htmlspecialchars($username) ?>" disabled></label><br>
    <label>姓名：<input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required></label><br>
    <label>電子郵件：<input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required></label><br>
    <button type="submit">更新資料</button>
</form>
<p><a href="logout.php">登出</a></p>
</body>
</html>
