<?php
// 管理員寄送通知頁面
// 實際部署時應加入管理員身分驗證
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>系統通知寄送</title>
</head>
<body>
<h2>寄送通知信給所有使用者</h2>

<?php if (!empty($_GET['status'])): ?>
    <p><?= htmlspecialchars($_GET['status']) ?></p>
<?php endif; ?>

<form action="send_notification.php" method="post" enctype="multipart/form-data">
    <label>主旨：<input type="text" name="subject" required style="width:300px"></label><br><br>

    <label>信件內容：<br>
        <textarea name="body" rows="8" cols="60" required></textarea>
    </label><br><br>

    <label>附加圖片（選填）：<input type="file" name="image" accept="image/*"></label><br><br>

    <button type="submit">寄送通知</button>
</form>
</body>
</html>
