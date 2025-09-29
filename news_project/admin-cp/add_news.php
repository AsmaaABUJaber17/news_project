<?php
// ربط قاعدة البيانات
include('../config/config.php');

// بدء الجلسة
session_start();

// التحقق من صلاحيات الأدمن
// إذا المستخدم مش أدمن بنرجعه لتسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /news_project/login.php');
    exit;
}

// متغير للأخطاء
$error = '';

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // قراءة القيم من النموذج
    $title       = trim($_POST['title'] ?? '');
    $details     = trim($_POST['details'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $user_id     = intval($_SESSION['user_id']);

    // رفع الصورة (اختياري)
    $image_name = '';
    if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $image_name);
    }

    // تأمين النصوص
    $title_sql   = mysqli_real_escape_string($conn, $title);
    $details_sql = mysqli_real_escape_string($conn, $details);
    $image_sql   = mysqli_real_escape_string($conn, $image_name);

    // استعلام الإضافة
    $sql = "
        INSERT INTO news (title, details, category_id, user_id, image, created_at, deleted)
        VALUES ('$title_sql', '$details_sql', $category_id, $user_id, '$image_sql', NOW(), 0)
    ";

    if (mysqli_query($conn, $sql)) {
        // بعد الحفظ نرجع لصفحة عرض الأخبار
        header('Location: view_news.php');
        exit;
    } else {
        $error = 'حدث خطأ أثناء الإضافة: ' . mysqli_error($conn);
    }
}

// جلب الفئات لعرضها في القائمة المنسدلة
$cats = mysqli_query($conn, "SELECT * FROM categories");

// الهيدر الخاص بلوحة التحكم
include ("../includes/admin_header.php");
?>

<h2>إضافة خبر</h2>

<?php 
// عرض رسالة الخطأ 
if ($error) {
    echo '<p>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
}
?>

<!-- نموذج إضافة خبر جديد -->
<form method="post" action="add_news.php" enctype="multipart/form-data">
    <label>
        عنوان الخبر
        <input type="text" name="title" required>
    </label>

    <label>
        الفئة
        <select name="category_id" required>
            <?php while ($c = mysqli_fetch_assoc($cats)): ?>
                <option value="<?php echo intval($c['id']); ?>">
                    <?php echo htmlspecialchars($c['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </label>

    <label>
        تفاصيل الخبر
        <textarea name="details" rows="8" required></textarea>
    </label>

    <label>
        صورة الخبر
        <input type="file" name="image" accept="image/*">
    </label>

    <button type="submit">حفظ</button>
</form>

<?php
// الفوتر
include('../includes/footer.php');
?>
