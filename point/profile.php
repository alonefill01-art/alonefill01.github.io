<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: index.php'); exit; }

$data_file = 'data/scores.json';
$data = json_decode(file_get_contents($data_file), true);
$members = $data['members'];

// ตรวจสอบ ID ที่ส่งมาใน URL 
$view_user_id = strtoupper($_GET['id'] ?? $_SESSION['user_id']); // ถ้าไม่มี ID ให้ใช้ ID ของผู้ใช้ที่ล็อกอิน

// ตรวจสอบว่า ID นี้มีอยู่จริงหรือไม่
if (!isset($members[$view_user_id])) {
    // ถ้าไม่พบ ID ให้ redirect กลับไปหน้า scoreboard
    header('Location: scoreboard.php'); 
    exit;
}

$member = $members[$view_user_id];
$is_owner = ($view_user_id === $_SESSION['user_id']); // ตรวจสอบว่าเป็นเจ้าของโปรไฟล์หรือไม่

// ข้อความแจ้งเตือนจะแสดงเฉพาะหน้าของเจ้าของเท่านั้น
$message = $is_owner ? ($_SESSION['profile_message'] ?? '') : '';
if ($is_owner) { unset($_SESSION['profile_message']); }

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👤 โปรไฟล์ของ <?php echo $member['name']; ?></title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="score-body">
    <?php include 'nav.php'; ?>
    <main class="profile-container">
        <div class="profile-header" style="background-image: url('<?php echo htmlspecialchars($member['banner']); ?>');">
            <div class="profile-header-content">
                <div class="profile-avatar-display">
                    <img src="<?php echo htmlspecialchars($member['avatar']); ?>" alt="Profile Avatar">
                </div>
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($member['name']); ?></h2>
                    <p>สิทธิ์: <?php echo ($member['role'] === 'admin') ? 'ผู้ดูแลระบบ' : 'สมาชิก'; ?></p>
                </div>
            </div>
        </div>

        <div class="profile-body">
            <?php if ($message): ?>
                <p class="error-message" style="background: #28a745; color: white; padding: 10px; border-radius: 5px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <h3 class="profile-section-title">ข้อมูลส่วนตัวและ Bio</h3>
            
            <?php if ($is_owner): ?>
            <form action="process_profile.php" method="POST" enctype="multipart/form-data" class="profile-form">
                
                <div class="form-group">
                    <label>ชื่อ (แสดงผล):</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>ประวัติสั้น (Bio):</label>
                    <textarea name="bio" rows="2"><?php echo htmlspecialchars($member['bio']); ?></textarea>
                </div>

                <h3 class="profile-section-title" style="margin-top: 30px;">เปลี่ยนรูปภาพและรหัสผ่าน</h3>

                <div class="form-group">
                    <label for="avatar" data-label="เลือกรูปโปรไฟล์ (Avatar)">เลือกรูปโปรไฟล์ (Avatar):</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="banner" data-label="เลือกรูปแบนเนอร์ (Banner)">เลือกรูปแบนเนอร์ (Banner):</label>
                    <input type="file" id="banner" name="banner" accept="image/*">
                </div>

                <div class="form-group">
                    <label>รหัสผ่านใหม่:</label>
                    <input type="password" name="new_password" placeholder="เว้นว่างถ้าไม่ต้องการเปลี่ยน">
                </div>
                <div class="form-group">
                    <label>ยืนยันรหัสผ่าน:</label>
                    <input type="password" name="confirm_password" placeholder="กรอกรหัสผ่านใหม่ซ้ำ">
                </div>

                <button type="submit" class="btn-save-profile">💾 บันทึกการเปลี่ยนแปลงโปรไฟล์</button>
            </form>
            <?php else: ?>
            <div class="profile-view-only">
                <div class="form-group">
                    <label>ชื่อ:</label>
                    <p><?php echo htmlspecialchars($member['name']); ?></p>
                </div>
                <div class="form-group">
                    <label>Bio:</label>
                    <p><?php echo htmlspecialchars($member['bio']); ?></p>
                </div>
                <p style="margin-top: 20px; color: var(--color-primary-purple); font-weight: 600;">(คุณสามารถดูข้อมูลส่วนตัวของผู้อื่นได้เท่านั้น)</p>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>