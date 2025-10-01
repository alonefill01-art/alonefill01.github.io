<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: index.php'); exit; }

$data_file = 'data/scores.json';
// ตรวจสอบว่าไฟล์ข้อมูลมีอยู่จริงหรือไม่
if (!file_exists($data_file)) {
    $data = ['announcements' => [], 'members' => []];
} else {
    $data = json_decode(file_get_contents($data_file), true);
}

// ประกาศล่าสุดอยู่บนสุด
$announcements = array_reverse($data['announcements'] ?? []); 
// ดึงข้อมูลสมาชิกทั้งหมดเพื่อใช้ค้นหาชื่อแอดมิน
$members = $data['members'] ?? []; 

/**
 * ฟังก์ชันสำหรับค้นหาชื่อแอดมินจาก ID ที่บันทึกไว้ในประกาศ
 */
function getAdminName($adminId, $members) {
    // ปรับ ID ให้เป็นตัวใหญ่เพื่อความแน่ใจ
    $adminId = strtoupper(trim($adminId)); 
    
    // คืนค่าชื่อผู้ดูแล ถ้าไม่พบจะคืนค่า 'ไม่ระบุผู้ดูแล'
    if (isset($members[$adminId])) { 
        return $members[$adminId]['name'];
    }
    return 'ไม่ระบุผู้ดูแล';
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📢 ประกาศคะแนนล่าสุด</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="score-body">
    <?php include 'nav.php'; ?>
    <main class="announcement-container">
        <h1 class="main-title">📢 ศูนย์ประกาศการให้คะแนน</h1>
        <p class="update-info">รายละเอียดการทำงานที่ได้รับคะแนนตามวาระต่างๆ</p>

        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $announcement): 
                // ค้นหาชื่อ Admin ที่ประกาศคะแนน
                $admin_name = getAdminName($announcement['admin_id'] ?? '', $members);
            ?>
                <div class="announcement-box">
                    <div class="announcement-header">
                        <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                        <div>
                            <p>วันที่ประกาศ: <?php echo date("d M Y | H:i", strtotime($announcement['date'])); ?></p>
                            <p class="admin-issuer">ผู้ดูแล: **<?php echo htmlspecialchars($admin_name); ?>**</p>
                        </div>
                    </div>
                    
                    <ul class="score-details-list">
                        <?php foreach ($announcement['details'] as $detail): ?>
                            <li>
                                <span class="detail-name"><?php echo htmlspecialchars($detail['name']); ?></span>
                                <div>
                                    <span class="detail-reason"><?php echo htmlspecialchars($detail['reason']); ?></span>
                                    <span class="detail-points">+<?php echo $detail['points']; ?> ★</span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="announcement-box" style="text-align: center;">
                <p>ยังไม่มีการประกาศคะแนนในขณะนี้</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>