<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: index.php'); exit; }

$data_file = 'data/scores.json';
$data = json_decode(file_get_contents($data_file), true);
$members = $data['members'];

// ตรวจสอบ ID ที่ส่งมาใน URL 
// ถ้ามี ID ให้ดูของคนนั้น, ถ้าไม่มี (หรือเป็นแอดมิน) ให้ดูของตัวเองที่ล็อกอินอยู่
$view_user_id = strtoupper($_GET['id'] ?? $_SESSION['user_id']); 

// แอดมินและสมาชิกดูหน้านี้ได้ทุกคน แต่ต้องล็อกอิน
if (!isset($members[$view_user_id])) {
    header('Location: scoreboard.php'); 
    exit;
}

$member = $members[$view_user_id];
$is_owner = ($view_user_id === $_SESSION['user_id']); // ตรวจสอบว่าเป็นเจ้าของโปรไฟล์หรือไม่
$history = array_reverse($member['score_history']); // แสดงรายการล่าสุดก่อน

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👤 ประวัติคะแนนของ<?php echo $member['name']; ?></title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="score-body">
    <?php include 'nav.php'; ?>
    <main class="score-history-container">
        <h1 class="main-title">
            📜 ประวัติคะแนน
            <?php echo $is_owner ? 'ของคุณ' : 'ของ ' . $member['name']; ?>
        </h1>
        
        <div class="current-score-banner">
            <img src="<?php echo htmlspecialchars($member['avatar']); ?>" alt="Avatar" class="history-avatar">
            คะแนนรวมปัจจุบัน: <span class="big-score"><?php echo $member['current_score']; ?> ★</span>
        </div>

        <h3 class="section-title-history">รายการการได้คะแนน (ล่าสุดก่อน)</h3>

        <div class="history-list">
            <?php if (!empty($history)): ?>
                <?php foreach ($history as $entry): ?>
                    <div class="history-item">
                        <div class="history-date">📅 <?php echo date("d M Y | H:i", strtotime($entry['date'])); ?></div>
                        <div class="history-reason">**<?php echo htmlspecialchars($entry['reason']); ?>**</div>
                        <div class="history-points">+<?php echo $entry['points']; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-history">ยังไม่มีประวัติการได้คะแนนสำหรับ <?php echo $member['name']; ?></p>
            <?php endif; ?>
        </div>
        
        <a href="scoreboard.php" class="btn-back-board">« กลับสู่ Leaderboard</a>
    </main>
</body>
</html>