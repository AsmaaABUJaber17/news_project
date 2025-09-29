<?php
// ربط قاعدة البيانات
include('../config/config.php');

// بدء الجلسة
session_start();

// التحقق من صلاحيات الأدمن
// إذا المستخدم مش أدمن بنرجعه لتسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location:news_project/login.php');
    exit;
}

// جلب عدد الأخبار غير المحذوفة
$newsCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM news WHERE deleted = 0")
)['cnt'];

// جلب عدد المستخدمين
$usersCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM users")
)['cnt'];

// جلب عدد الفئات
$catsCount  = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM categories")
)['cnt'];
?>

<?php 
// الهيدر الخاص بلوحة التحكم
include ('../includes/admin_header.php');
?>

<!-- عنوان لوحة التحكم -->
<h1 class="page-title">لوحة التحكم</h1>

<!-- إحصائيات  -->
<div class="stats">
    <div class="stat">
        <h3>الأخبار</h3>
        <p><?php echo $newsCount; ?></p>
        <small>المنشورة حالياً</small>
    </div>

    <div class="stat">
        <h3>المستخدمون</h3>
        <p><?php echo $usersCount; ?></p>
        <small>إجمالي الحسابات</small>
    </div>

    <div class="stat">
        <h3>الفئات</h3>
        <p><?php echo $catsCount; ?></p>
        <small>منظمة حسب التصنيفات</small>
    </div>
</div>

<!-- روابط إلى صفحات الإدارة -->
<div class="admin-actions">
    <a href="add_news.php" class="btn">➕ إضافة خبر</a>
    <a href="view_news.php" class="btn">📄 عرض الأخبار</a>
    <a href="categories.php" class="btn">✏️ إدارة الفئات</a>
    <a href="users.php" class="btn">👥 إدارة المستخدمين</a>
    <a href="deleted_news.php" class="btn btn-danger">🗑️ الأخبار المحذوفة</a>
</div>
