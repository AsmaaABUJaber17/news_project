<?php
// استدعاء الهيدر
include('includes/header.php');

// الاتصال بقاعدة البيانات
include('config/config.php');

// جلب رقم الخبر من الرابط وتحويله لرقم صحيح
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// إذا لم يتم تمرير رقم خبر صحيح
if ($id <= 0) {
    ?>
    <div style="max-width:600px; margin:40px auto; padding:20px; background:#d4edda; color:#155724; border-radius:6px; text-align:center;">
        <h3> لم يتم تحديد الخبر</h3>
        <p>الخبر الذي تحاول الوصول إليه غير متوفر.</p>
        <a href="index.php" style="display:inline-block; margin-top:10px; padding:8px 16px; background:#228B22; color:white; text-decoration:none; border-radius:4px;">
            العودة للرئيسية
        </a>
    </div>
    <?php
    include('includes/footer.php');
    exit;
}

// جلب بيانات الخبر مع الفئة والكاتب
$sql = "
    SELECT n.*, c.name AS category_name, u.name AS author_name
    FROM news n
    LEFT JOIN categories c ON n.category_id = c.id
    LEFT JOIN users u ON n.user_id = u.id
    WHERE n.id = $id AND n.deleted = 0
    LIMIT 1
";
$res = mysqli_query($conn, $sql);

// التحقق من وجود خبر
if (!$res || mysqli_num_rows($res) === 0) {
    ?>
    <div style="max-width:600px; margin:40px auto; padding:20px; background:# #00b377
; color:#155724; border-radius:6px; text-align:center;">
        <h3> الخبر غير موجود</h3>
        <p>عذرًا، الخبر الذي تبحث عنه غير متوفر أو ربما تمت إزالته.</p>
        <a href="index.php" style="display:inline-block; margin-top:10px; padding:8px 16px; background:#228B22; color:white; text-decoration:none; border-radius:4px;">
            العودة للرئيسية
        </a>
    </div>
    <?php
    include('includes/footer.php');
    exit;
}

// جلب بيانات الخبر
$row = mysqli_fetch_assoc($res);
?>

<!-- عرض تفاصيل الخبر -->
<article class="single-news">

    <!-- عنوان الخبر -->
    <h1><?php echo htmlspecialchars($row['title']); ?></h1>

    <!-- معلومات إضافية: الفئة - الكاتب - تاريخ النشر -->
    <p class="meta">
        <?php echo htmlspecialchars($row['category_name']); ?> —
        بواسطة <?php echo htmlspecialchars($row['author_name']); ?> —
        <?php echo htmlspecialchars($row['created_at']); ?>
    </p>

    <!-- صورة الخبر -->
    <img src="/news_project/uploads/<?php echo htmlspecialchars($row['image'] ?: 'placeholder.PNG'); ?>"
         alt="صورة الخبر"
         class="single-cover">

    <!-- تفاصيل الخبر -->
    <div class="details">
        <?php echo nl2br(htmlspecialchars($row['details'])); ?>
    </div>

</article>

<?php
// استدعاء الفوتر
include('includes/footer.php');
?>
