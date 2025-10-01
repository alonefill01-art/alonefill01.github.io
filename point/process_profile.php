<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: index.php'); exit; }

$user_id = $_SESSION['user_id'];
$data_file = 'data/scores.json';
$data = json_decode(file_get_contents($data_file), true);
$member = $data['members'][$user_id];
$message = '';
$error = false;

// 1. รับข้อมูลจากฟอร์ม
$newName = trim($_POST['name'] ?? $member['name']);
$newBio = trim($_POST['bio'] ?? $member['bio']);
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// 2. ตรวจสอบรหัสผ่าน
if (!empty($newPassword)) {
    if ($newPassword !== $confirmPassword) {
        $message = "❌ รหัสผ่านใหม่และการยืนยันไม่ตรงกัน!";
        $error = true;
    } else {
        $member['password'] = $newPassword;
    }
}

// 3. จัดการการอัปโหลดรูปภาพ
if (!$error) {
    $upload_dir = 'assets/';
    if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
    
    // อัปโหลด Avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = strtolower($user_id) . '_avatar.' . $ext;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $filename)) {
            $member['avatar'] = $upload_dir . $filename;
        }
    }

    // อัปโหลด Banner
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
        $filename = strtolower($user_id) . '_banner.' . $ext;
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $upload_dir . $filename)) {
            $member['banner'] = $upload_dir . $filename;
        }
    }
    
    // 4. อัปเดตข้อมูล Text ใน JSON
    $member['name'] = $newName;
    $member['bio'] = $newBio;
    
    // 5. บันทึกกลับ JSON
    $data['members'][$user_id] = $member;
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    $_SESSION['profile_message'] = $message ?: "✅ บันทึกโปรไฟล์สำเร็จแล้ว!";
} else {
    $_SESSION['profile_message'] = $message;
}

// อัปเดตชื่อใน Session ทันที (ถ้าเปลี่ยนชื่อ)
$_SESSION['user_name'] = $member['name'];

header('Location: profile.php');
exit;
?>