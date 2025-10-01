<?php
session_start();
$data_file = 'data/scores.json';

// 1. ตรวจสอบว่ามีไฟล์ JSON หรือไม่
if (!file_exists($data_file)) {
    $_SESSION['login_error'] = "❌ ไม่พบไฟล์ฐานข้อมูล (scores.json)";
    header('Location: index.php');
    exit;
}

$data = json_decode(file_get_contents($data_file), true);

$user_id = strtoupper(trim($_POST['user_id'] ?? '')); // แปลงเป็นตัวพิมพ์ใหญ่และตัดช่องว่าง
$password = $_POST['password'] ?? '';

// 2. ตรวจสอบว่า User ID มีอยู่ในฐานข้อมูลหรือไม่
if (isset($data['members'][$user_id])) {
    $member = $data['members'][$user_id];
    
    // 3. ตรวจสอบรหัสผ่าน (แบบตรงตัว เพราะเราไม่ได้ Hash รหัสผ่าน)
    if ($password === $member['password']) {
        // ล็อกอินสำเร็จ
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $member['name'];
        $_SESSION['user_role'] = $member['role'];

        // Redirect ตามสิทธิ์
        if ($member['role'] === 'admin') {
            header('Location: admin_panel.php');
        } else {
            header('Location: scoreboard.php');
        }
        exit;
        
    } else {
        // รหัสผ่านผิด
        $_SESSION['login_error'] = "❌ รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง";
    }
} else {
    // User ID/Username ผิด
    $_SESSION['login_error'] = "❌ ไม่พบรหัสสมาชิกนี้ในระบบ";
}

// ล็อกอินไม่สำเร็จ
header('Location: index.php');
exit;
?>