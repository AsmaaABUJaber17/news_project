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

// جلب رقم الخبر من الرابط
$id = intval($_GET['id'] ?? 0);

// إذا المعرف غير صحيح نرجع لصفحة جميع الأخبار
if ($id <= 0) {
    header('Location: view_news.php');
    exit;
}

// جلب بيانات الخبر الحالي
$res_news = mysqli_query($conn, "SELECT * FROM news WHERE id = $id LIMIT 1");
$news = $res_news ? mysqli_fetch_assoc($res_news) : null;

// إذا الخبر غير موجود نرجع للقائمة
if (!$news) {
    header('Location: view_news.php');
    exit;
}

// متغير لرسالة الخطأ
$error = '';

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // قراءة القيم من النموذج
    $title       = trim($_POST['title'] ?? '');
    $details     = trim($_POST['details'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);

    // التحقق من الحقول الأساسية
    if ($title === '' || $details === '' || $category_id <= 0) {
        $error = 'الرجاء إدخال جميع الحقول المطلوبة.';
    } else {
        // تأمين المدخلات
        $title_sql   = mysqli_real_escape_string($conn, $title);
        $details_sql = mysqli_real_escape_string($conn, $details);

        // التعامل مع الصورة (اختياري)
        $image_name = $news['image']; // نحافظ على القديمة إذا ما تم رفع جديدة

        if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            // استخراج الامتداد بشكل بسيط
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // اسم ملف جديد بسيط بالوقت
            $image_name = time() . '.' . $ext;
            // مسار الحفظ داخل مجلد الرفع
            $dest = __DIR__ . '/../uploads/' . $image_name;
            // نقل الملف
            move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        }

        // تحديث الخبر
        $q = "
            UPDATE news 
            SET title = '$title_sql',
                details = '$details_sql',
                category_id = $category_id,
                image = " . ($image_name !== null ? "'" . mysqli_real_escape_string($conn, $image_name) . "'" : "NULL") . "
            WHERE id = $id
            LIMIT 1
        ";

        if (mysqli_query($conn, $q)) {
            // رجوع للقائمة بعد النجاح
            header('Location: view_news.php');
            exit;
        } else {
            $error = 'خطأ أثناء التحديث.';
        }
    }
}

// جلب الفئات لعرضها في القائمة المنسدلة
$cats = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name ASC");

// الهيدر الخاص بلوحة التحكم
include('../includes/admin_header.php');
?>

<h2>تعديل الخبر</h2>

<?php if ($error): ?>
    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post" action="edit_news.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
    <label>
        عنوان الخبر
        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
    </label>

    <br>

    <label>
        الفئة
        <select name="category_id" required>
            <?php if ($cats && mysqli_num_rows($cats) > 0): ?>
                <?php while ($c = mysqli_fetch_assoc($cats)): ?>
                    <option value="<?php echo (int)$c['id']; ?>"
                        <?php if ((int)$c['id'] === (int)$news['category_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
    </label>

    <br>

    <label>
        تفاصيل الخبر
        <textarea name="details" rows="8" required><?php echo htmlspecialchars($news['details'], ENT_QUOTES, 'UTF-8'); ?></textarea>
    </label>

    <br>

    <label>
        صورة الخبر
        <input type="file" name="image" accept="image/*">
    </label>

    <br>

    <button type="submit">حفظ التعديلات</button>
</form>

<?php
// الفوتر
include('../includes/footer.php');
?>
