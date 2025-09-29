<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<nav class="nav">
    <div class="brand">
    <a href="index.php">نظام إدارة الأخبار</a></div>
    <ul class="nav-links">
        <li><a href="index.php">الرئيسية</a></li>
        <li><a href="index.php">الأخبار</a></li>
        <?php if($is_admin): ?>
        <li><a href="news_project/admin/dashboard.php">لوحة التحكم</a></li>
        <?php endif; ?>
    </ul>
    <div class="nav-actions">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-name">مرحبا، <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a class="btn" href="logout.php">تسجيل خروج</a>
        <?php else: ?>
            <a class="btn" href="login.php">تسجيل دخول</a>
            <a class="btn btn-outline" href="signup.php">إنشاء حساب</a>
        <?php endif; ?>
    </div>
</nav>
