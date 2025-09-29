<?php


// استدعاء ملفات الهيدر والاتصال بقاعدة البيانات
include('includes/header.php');
include('config/config.php');

// ---------------------
// استلام كلمة البحث من الفورم
// ---------------------
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// ---------------------
// تجهيز الاستعلام
// ---------------------
if ($search !== '') {
    // إذا فيه كلمة بحث
    $like = "%$search%";
    $sql = "
        SELECT n.*, c.name AS category_name, u.name AS author_name
        FROM news n
        LEFT JOIN categories c ON n.category_id = c.id
        LEFT JOIN users u ON n.user_id = u.id
        WHERE n.deleted = 0
          AND (n.title LIKE '$like' OR n.details LIKE '$like')
        ORDER BY n.created_at DESC
    ";
} else {
    // إذا ما فيه كلمة بحث
    $sql = "
        SELECT n.*, c.name AS category_name, u.name AS author_name
        FROM news n
        LEFT JOIN categories c ON n.category_id = c.id
        LEFT JOIN users u ON n.user_id = u.id
        WHERE n.deleted = 0
        ORDER BY n.created_at DESC
    ";
}

// ---------------------
// تنفيذ الاستعلام
// ---------------------
$res = mysqli_query($conn, $sql);

// ---------------------
// إيقاف البرنامج لو صار خطأ
// ---------------------
if ($res === false) {
    die('خطأ في الاستعلام');
}
?>

<!-- قسم الهيرو -->
<section class="hero">
    <h1>أحدث الأخبار</h1>
    <form class="search" method="get" action="index.php">
        <input type="text" name="q" placeholder="ابحث عن خبر..." value="<?php echo $search; ?>">
        <button type="submit">بحث</button>
    </form>
</section>

<!-- قائمة الأخبار -->
<section class="news-list">
<?php while($row = mysqli_fetch_assoc($res)): ?>
    <article class="news-card">
        <img src="/news_project/uploads/<?php echo $row['image'] ?: 'placeholder.png'; ?>" alt="">
        <div class="news-body">
            <p class="excerpt"><?php echo substr($row['details'], 0, 200); ?>...</p>
            <a class="btn" href="/news_project/view.php?id=<?php echo $row['id']; ?>">قراءة المزيد</a>
        </div>
    </article>
<?php endwhile; ?>
</section>

<?php include ('includes/footer.php'); ?>
