<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: index.php'); exit; }

$data_file = 'data/scores.json';
$data = json_decode(file_get_contents($data_file), true);
$members = $data['members'];

// *** การแก้ไข: กรองและเรียงลำดับสมาชิกทั้งหมด (รวม Admin) ตามคะแนน (จากมากไปน้อย) ***
$scores = $members; 
uasort($scores, fn($a, $b) => $b['current_score'] <=> $a['current_score']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔥 Leaderboard - คะแนนรวม</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="score-body">
    <?php include 'nav.php'; // สำหรับแถบเมนู ?>
    <main class="scoreboard-container">
        <h1 class="main-title">🚀 Team Leaderboard 2025</h1>
        <p class="update-info">ยินดีต้อนรับ <?php echo $_SESSION['user_name']; ?></p>

        <div class="leaderboard-grid">
            <?php $rank = 1; foreach ($scores as $id => $member): 
                $is_admin = $member['role'] === 'admin';
                // ถ้าเป็น Admin จะไม่นับ Rank และใช้คลาสพิเศษ
                $display_rank = $is_admin ? '' : $rank;
            ?>
            <div class="leader-card <?php echo $is_admin ? 'admin-card-special' : "rank-{$display_rank}"; ?>">
                <div class="card-banner" style="background-image: url('<?php echo htmlspecialchars($member['banner']); ?>');"></div>
                <div class="card-avatar">
                    <img src="<?php echo htmlspecialchars($member['avatar']); ?>" alt="<?php echo $member['name']; ?> Profile">
                </div>
                
                <div class="rank-badge">
                    <?php echo $is_admin ? '👑 ADMIN' : "#{$display_rank}"; ?>
                </div>
                
                <div class="card-content">
                    <h2 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h2>
                    <p class="member-bio"><?php echo htmlspecialchars($member['bio']); ?></p>

                    <div class="score-display-box">
                        <span class="score-value"><?php echo $member['current_score']; ?></span>
                        <span class="score-star">★</span>
                    </div>

                    <div class="profile-actions">
                        
                        <a href="profile.php?id=<?php echo $id; ?>" class="btn-view-profile">
                            <?php echo ($id === $_SESSION['user_id']) ? 'จัดการโปรไฟล์ของคุณ' : 'ดูโปรไฟล์'; ?>
                        </a>
                        
                        <a href="my_score.php?id=<?php echo $id; ?>" class="btn-view-score">
                            📜 ดูประวัติคะแนน
                        </a>
                        
                    </div>
                </div>
            </div>
            <?php 
            // นับ Rank เพิ่มเฉพาะสมาชิกเท่านั้น
            if (!$is_admin) { 
                $rank++; 
            } 
            endforeach; ?>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>