<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    // ตรวจสอบรหัสแอดมิน (ใช้รหัสแบบตรงๆ ตามที่คุณระบุ: 132400)
    if ($password === '132400') {
        $_SESSION['is_admin'] = true;
        header('Location: admin_panel.php');
        exit;
    } else {
        $error = 'รหัสผ่านไม่ถูกต้อง!';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ Admin</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-login-body">
    <div class="login-container">
        <h2>🔒 เข้าสู่ระบบผู้ดูแล</h2>
        <form method="POST">
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">ล็อกอิน</button>
        </form>
        <p><a href="index.php">กลับหน้าหลัก</a></p>
    </div>
</body>
</html>