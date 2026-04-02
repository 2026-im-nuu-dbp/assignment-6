<?php
session_start();
require 'db.php';

// 只接受 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$name     = trim($_POST['name']     ?? '');
$username = trim($_POST['username'] ?? '');
$password =       $_POST['password'] ?? '';
$email    = trim($_POST['email']    ?? '');

// 基本驗證
if (!$name || !$username || !$password || !$email) {
    header('Location: register.php?error=' . urlencode('請填寫所有欄位'));
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: register.php?error=' . urlencode('電子郵件格式不正確'));
    exit;
}

// 確認帳號是否已存在
$stmt = $conn->prepare('SELECT id FROM musers WHERE username = ? OR email = ?');
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    header('Location: register.php?error=' . urlencode('帳號或電子郵件已被使用'));
    exit;
}
$stmt->close();

// 產生開通碼 (128-bit 隨機 token)
$token         = bin2hex(random_bytes(16));
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$is_active     = 0;

// 寫入資料庫
$stmt = $conn->prepare(
    'INSERT INTO musers (name, username, password, email, token, is_active) VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('sssssi', $name, $username, $password_hash, $email, $token, $is_active);
$stmt->execute();
$stmt->close();

// 寄送確認信
$activation_link = 'http://' . $_SERVER['HTTP_HOST']
    . dirname($_SERVER['PHP_SELF']) . '/activate.php?token=' . $token;

$subject = '【作業 6】帳號啟用確認';
$message = "您好，{$name}，\n\n請點選以下連結以啟用帳號：\n{$activation_link}\n\n此連結僅可使用一次。";
$headers = 'From: no-reply@example.com' . "\r\n" . 'Content-Type: text/plain; charset=UTF-8';

// 注意：實際部署時應使用可靠的郵件服務，並處理可能的寄信失敗
// 這是一定要修改的部分，否則無法收到啟用信
mail($email, $subject, $message, $headers);

echo '<!DOCTYPE html><html lang="zh-TW"><head><meta charset="UTF-8"><title>申請成功</title></head><body>';
echo '<h2>申請成功</h2>';
echo '<p>確認信已寄送至 <strong>' . htmlspecialchars($email) . '</strong>，請點選信中連結以啟用帳號。</p>';
echo '<p><a href="login.php">前往登入</a></p>';
echo '</body></html>';
