<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password =       $_POST['password'] ?? '';

if (!$username || !$password) {
    header('Location: login.php?error=' . urlencode('請輸入帳號及密碼'));
    exit;
}

$stmt = $conn->prepare('SELECT id, password, is_active FROM musers WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($user_id, $hashed, $is_active);
$stmt->fetch();
$stmt->close();

if (!$user_id || !password_verify($password, $hashed)) {
    header('Location: login.php?error=' . urlencode('帳號或密碼錯誤'));
    exit;
}

if (!$is_active) {
    header('Location: login.php?error=' . urlencode('帳號尚未啟用，請確認電子郵件'));
    exit;
}

// 登入成功，建立 session
session_regenerate_id(true);
$_SESSION['user_id'] = $user_id;

header('Location: profile.php');
exit;
