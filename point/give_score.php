<?php
session_start();
// ตรวจสอบสิทธิ์ Admin และต้องเป็น method POST
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$data_file = 'data/scores.json';
$member_id = $_POST['member_id'] ?? null;
$points = (int)($_POST['points'] ?? 0);
$reason = trim($_POST['reason'] ?? '');
$current_time = date("Y-m-d H:i:s");
$admin_id = $_SESSION['user_id'] ?? 'UNKNOWN_ADMIN'; // ดึง ID ผู้ดูแลที่ล็อกอินอยู่

if ($member_id && $points > 0 && !empty($reason)) {
    $data = json_decode(file_get_contents($data_file), true);

    if (isset($data['members'][$member_id]) && $data['members'][$member_id]['role'] === 'member') {
        
        // 1. อัปเดตคะแนนรวมและประวัติส่วนตัวของสมาชิก
        $member_name = $data['members'][$member_id]['name'];
        $data['members'][$member_id]['current_score'] += $points;

        $history_entry = [
            'date' => $current_time,
            'points' => $points,
            'reason' => $reason
        ];
        $data['members'][$member_id]['score_history'][] = $history_entry;
        
        
        // 2. *** สร้างรายการประกาศคะแนนใหม่ (New Announcement) ***
        $announcement_detail = [
            'name' => $member_name,
            'reason' => $reason,
            'points' => $points
        ];
        
        $new_announcement = [
            // ตั้งชื่อหัวข้อตามความเหมาะสม
            'title' => "ประกาศให้คะแนนกิจกรรม: {$reason}", 
            'date' => $current_time,
            'admin_id' => $admin_id, // <<< บันทึก ID ผู้ดูแลที่นี่!
            'details' => [$announcement_detail] 
        ];
        
        // ตรวจสอบและสร้างอาร์เรย์ 'announcements' ถ้าไม่มี
        if (!isset($data['announcements'])) {
            $data['announcements'] = [];
        }
        
        // เพิ่มประกาศใหม่เข้าในอาร์เรย์ประกาศ
        $data['announcements'][] = $new_announcement;
        // *** สิ้นสุดการสร้างประกาศ ***


        // 3. เขียนข้อมูลทั้งหมดกลับลงไฟล์ JSON
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $message = "✅ อัปเดตคะแนน **" . $member_name . "** สำเร็จ: +" . $points . " คะแนน";
    } else {
        $message = "❌ ไม่พบสมาชิกหรือสิทธิ์ไม่ถูกต้อง";
    }

} else {
    $message = "❌ ข้อมูลไม่ครบถ้วน: ต้องระบุสมาชิก คะแนน และเหตุผล";
}

// แสดงผลลัพธ์แบบสวยงาม
echo "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><title>ผลลัพธ์</title><link rel='stylesheet' href='styles.css'><link href='https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap' rel='stylesheet'></head><body class='result-body'><div class='result-box'><h2>" . (strpos($message, '✅') !== false ? '🎉 สำเร็จ!' : '⚠️ ข้อผิดพลาด!') . "</h2><p>$message</p><p><a href='admin_panel.php' class='btn-back'>กลับหน้า Admin</a> | <a href='scoreboard.php' class='btn-back'>ดู Leaderboard</a></p></div></body></html>";
?>