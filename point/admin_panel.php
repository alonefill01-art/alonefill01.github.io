<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') { header('Location: index.php'); exit; }

$data_file = 'data/scores.json';
$data = json_decode(file_get_contents($data_file), true);
$members = $data['members'];
$member_list = array_filter($members, fn($m) => $m['role'] === 'member');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👑 Admin Panel - ให้คะแนน</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'nav.php'; ?>
    <main class="admin-main-panel">
        <h1 class="main-title">✨ Admin Score Management</h1>
        <p class="current-date">วันที่: <?php echo date("d/m/Y H:i:s"); ?></p>
        
        <h2>📝 บันทึกคะแนนใหม่ (รายบุคคล)</h2>
        
        <form action="give_score.php" method="POST" class="score-form-admin">
            <div class="form-group-select">
                <label for="member_id">เลือกสมาชิก:</label>
                <select name="member_id" id="member_id" required>
                    <option value="">-- เลือกสมาชิก --</option>
                    <?php foreach ($member_list as $id => $member): ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($member['name']); ?> (คะแนน: <?php echo $member['current_score']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group-inline">
                <label for="points">คะแนนที่จะเพิ่ม:</label>
                <input type="number" id="points" name="points" value="0" min="1" required>
            </div>

            <div class="form-group-full">
                <label for="reason">เหตุผลในการให้คะแนน (รายละเอียด):</label>
                <textarea id="reason" name="reason" rows="3" placeholder="เช่น ตรงต่อเวลา +1, ช่วยจัดทำโลโก้ทีม +5" required></textarea>
            </div>
            
            <button type="submit" class="btn-submit-score-admin">⭐ บันทึกและเพิ่มคะแนน</button>
        </form>

        <h2 class="section-title-history">📜 ประวัติคะแนนโดยรวม (ตัวอย่าง)</h2>
        <div class="history-summary">
            <?php 
            $all_history = [];
            foreach ($member_list as $member) {
                foreach ($member['score_history'] as $entry) {
                    $entry['name'] = $member['name'];
                    $all_history[] = $entry;
                }
            }
            // เรียงลำดับประวัติทั้งหมดตามวันที่ล่าสุด
            usort($all_history, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));
            $recent_history = array_slice($all_history, 0, 10); // แสดง 10 รายการล่าสุด
            ?>
            
            <?php if (!empty($recent_history)): ?>
                <ul>
                    <?php foreach($recent_history as $entry): ?>
                        <li>
                            **[<?php echo date("d/m H:i", strtotime($entry['date'])); ?>]** <span class="history-name"><?php echo $entry['name']; ?></span> 
                            ได้รับ: <span class="history-points">+<?php echo $entry['points']; ?></span> 
                            เนื่องจาก: <?php echo htmlspecialchars($entry['reason']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>ยังไม่มีประวัติการให้คะแนนในระบบ</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>