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

// قراءة رقم الفئة من الرابط
$id = intval($_GET['id'] ?? 0);

// إذا المعرف غير صحيح نرجع لقائمة الفئات
if ($id <= 0) {
    header('Location: categories.php');
    exit;
}

// جلب بيانات الفئة الحالية
$res = mysqli_query($conn, "SELECT id, name FROM categories WHERE id = $id LIMIT 1");
$cat = $res ? mysqli_fetch_assoc($res) : null;

// إذا الفئة غير موجودة نرجع للقائمة
if (!$cat) {
    header('Location: categories.php');
    exit;
}

// متغير لرسالة الخطأ
$error = '';

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // قراءة الاسم الجديد
    $name = trim($_POST['name'] ?? '');

    // التحقق من أن الاسم ليس فارغًا
    if ($name === '') {
        $error = 'الرجاء إدخال اسم الفئة.';
    } else {
        // تأمين النص
        $name_sql = mysqli_real_escape_string($conn, $name);

        // تحديث اسم الفئة
        $q = "UPDATE categories SET name = '$name_sql' WHERE id = $id LIMIT 1";
        if (mysqli_query($conn, $q)) {
            // بعد التحديث نرجع لقائمة الفئات
            header('Location: categories.php');
            exit;
        } else {
            $error = 'حدث خطأ أثناء التحديث: ' . mysqli_error($conn);
        }
    }
}

// الهيدر الخاص بلوحة التحكم
include ('/../includes/admin_header.php');
?>

<h2>تعديل الفئة</h2>

<?php
// عرض رسالة الخطأ 
if ($error) {
    echo '<p>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
}
?>

<!-- نموذج تعديل اسم الفئة -->
<form method="post" action="edit_category.php?id=<?php echo $id; ?>">
    <label>
        اسم الفئة
        <input type="text" name="name" value="<?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
    </label>
    <br>
    <button type="submit">حفظ</button>
</form>

<?php
// الفوتر
include('../includes/footer.php');
?>
