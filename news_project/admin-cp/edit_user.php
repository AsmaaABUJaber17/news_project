<?php
// ربط قاعدة البيانات
include('../config/config.php');

// بدء الجلسة
session_start();

// التحقق من صلاحيات الأدمن
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /news_project/login.php');
    exit;
}

// قراءة معرف المستخدم من الرابط
$id = intval($_GET['id'] ?? 0);

// في حال المعرف غير صحيح نرجع للقائمة
if ($id <= 0) {
    header('Location: users.php');
    exit;
}

// جلب بيانات المستخدم الحالي
$res = mysqli_query($conn, "SELECT id, name, email, role, password FROM users WHERE id = $id LIMIT 1");
$user = $res ? mysqli_fetch_assoc($res) : null;

// إذا المستخدم غير موجود نرجع للقائمة
if (!$user) {
    header('Location: users.php');
    exit;
}

// متغير لرسالة الخطأ
$error = '';

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // قراءة القيم من النموذج
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = trim($_POST['role']  ?? 'user'); // user أو admin
    $pass  = trim($_POST['password'] ?? '');  // اختياري

    // تحقق أساسي
    if ($name === '' || $email === '') {
        $error = 'الرجاء إدخال الاسم والبريد.';
    } else {
        // تأمين النصوص
        $name_sql  = mysqli_real_escape_string($conn, $name);
        $email_sql = mysqli_real_escape_string($conn, $email);
        $role_sql  = ($role === 'admin') ? 'admin' : 'user';

        // بناء استعلام التحديث
        if ($pass !== '') {
            // ملاحظة: المشروع الحالي لا يستخدم تجزئة كلمة المرور
            $pass_sql = mysqli_real_escape_string($conn, $pass);
            $q = "
                UPDATE users
                SET name = '$name_sql',
                    email = '$email_sql',
                    role = '$role_sql',
                    password = '$pass_sql'
                WHERE id = $id
                LIMIT 1
            ";
        } else {
            $q = "
                UPDATE users
                SET name = '$name_sql',
                    email = '$email_sql',
                    role = '$role_sql'
                WHERE id = $id
                LIMIT 1
            ";
        }

        // تنفيذ التحديث
        if (mysqli_query($conn, $q)) {
            header('Location: users.php');
            exit;
        } else {
            $error = 'حدث خطأ أثناء التحديث.';
        }
    }
}

// الهيدر
include('../includes/admin_header.php');
?>

<h2>تعديل المستخدم</h2>

<?php
// عرض رسالة الخطأ إن وجدت
if ($error) {
    echo '<p>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
}
?>

<!-- نموذج تعديل المستخدم -->
<form method="post" action="edit_user.php?id=<?php echo (int)$user['id']; ?>">
    <label>
        الاسم الكامل
        <input type="text" name="name" required
               value="<?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>">
    </label>

    <label>
        البريد الإلكتروني
        <input type="email" name="email" required
               value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>">
    </label>

    <label>
        الدور
        <select name="role" required>
            <option value="user"  <?php if ($user['role'] === 'user')  echo 'selected'; ?>>مستخدم</option>
            <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>أدمن</option>
        </select>
    </label>

    <label>
        كلمة المرور (اختياري)
        <input type="password" name="password" placeholder="اتركها فارغة للإبقاء على الحالية">
    </label>

    <button type="submit">حفظ</button>
</form>

<?php
// الفوتر
include('../includes/footer.php');
?>
