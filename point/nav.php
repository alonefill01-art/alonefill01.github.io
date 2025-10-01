<nav class="top-nav">
    <div class="nav-brand">Team Score Dashboard</div>
    <div class="nav-links">
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="admin_panel.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_panel.php' ? 'active' : ''; ?>">👑 Admin Panel</a>
        <?php endif; ?>
        <a href="scoreboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'scoreboard.php' ? 'active' : ''; ?>">🔥 Leaderboard</a>
        <a href="announcement.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'announcement.php' ? 'active' : ''; ?>">📢 ประกาศคะแนน</a>
        <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">👤 โปรไฟล์</a>
        <a href="logout.php" class="btn-logout">🚪 ออกจากระบบ</a>
    </div>
</nav>