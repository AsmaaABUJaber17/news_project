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

// متغير لتخزين الأخطاء
$error = '';

// إضافة فئة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);
    if ($name !== '') {
        // تأمين النص المدخل
        $name_sql = mysqli_real_escape_string($conn, $name);
        // تنفيذ الإدخال
        $sql = "INSERT INTO categories (name) VALUES ('$name_sql')";
        if (!mysqli_query($conn, $sql)) {
            $error = 'خطأ أثناء الإضافة: ' . mysqli_error($conn);
        }
    }
}

// حذف فئة
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // تحويل لرقم صحيح
    $sql = "DELETE FROM categories WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: categories.php');
    exit;
}

// جلب كل الفئات
$res = mysqli_query($conn, "SELECT * FROM categories");

// الهيدر الخاص بلوحة التحكم
include ('../includes/admin_header.php');
?>

<h2>إدارة الفئات</h2>

<?php 
// عرض رسالة الخطأ 
if ($error) {
    echo '<p>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
}
?>

<!-- نموذج إضافة فئة جديدة -->
<form method="post">
    <input type="text" name="name" placeholder="اسم الفئة" required>
    <button type="submit">إضافة</button>
</form>

<!-- جدول عرض الفئات -->
<table class="table">
    <thead>
        <tr>
            <th>اسم الفئة</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <!-- اسم الفئة -->
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <!-- زر الحذف -->
            <td>
                 <a class="btn btn-small" href="edit_category.php?id=<?php echo intval($row['id']); ?>">تعديل</a>
                <a  class="btn btn-danger btn-small" href="categories.php?delete=<?php echo intval($row['id']); ?>"
                   onclick="return confirm('حذف الفئة؟');">حذف</a>
            </td>

        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php
// الفوتر
include('../includes/footer.php');

?>
