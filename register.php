<?php
session_start();
// 若已登入，導向個人資料頁
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>申請帳號</title>
</head>
<body>
<h2>申請帳號</h2>

<?php if (!empty($_GET['error'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

<form action="register_process.php" method="post">
    <label>姓名：<input type="text" name="name" required></label><br>
    <label>帳號：<input type="text" name="username" required></label><br>
    <label>密碼：<input type="password" name="password" required></label><br>
    <label>電子郵件：<input type="email" name="email" required></label><br>
    <button type="submit">送出申請</button>
</form>
<p>已有帳號？<a href="login.php">登入</a></p>
</body>
</html>
