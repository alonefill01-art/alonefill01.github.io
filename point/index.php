<?php
session_start();
// ตรวจสอบว่าล็อกอินแล้วหรือยัง ถ้าใช่ให้ไปหน้าหลัก
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin_panel.php');
    } else {
        header('Location: scoreboard.php');
    }
    exit;
}

// ดึงข้อความแจ้งเตือน (ถ้ามี)
$error_message = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']); 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔒 เข้าสู่ระบบ Team Score</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">
    
    <div class="login-box">
        <h1>✨ ยินดีต้อนรับเข้าสู่ระบบ!</h1>
        <p>Dashboard คะแนนทีมแสนน่ารัก</p>

        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="process_login.php" method="POST" class="login-form">
            <div>
                <input type="text" name="user_id" placeholder="รหัสสมาชิก (เช่น ADMIN, NIL)" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
            </div>
            <button type="submit" class="btn-login">เข้าสู่ระบบ</button>
        </form>
        
        <p style="margin-top: 25px; font-size: 0.9em; color: var(--color-text-dim);">
            โปรดใช้รหัสที่กำหนดเพื่อเข้าสู่หน้า Leaderboard
        </p>

    </div>
</body>
</html>