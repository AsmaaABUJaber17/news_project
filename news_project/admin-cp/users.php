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

// حذف مستخدم بشكل نهائي (اختياري)
// عند الضغط على "حذف" بنحذف السجل من جدول users
if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']); // تحويل لرقم صحيح
    if ($uid > 0) {
        $sql_del = "DELETE FROM users WHERE id = $uid LIMIT 1";
        mysqli_query($conn, $sql_del);
    }
    // نرجع لنفس الصفحة مع باراميتر بسيط كرسالة نجاح
    header('Location: users.php?msg=deleted');
    exit;
}

// جلب جميع المستخدمين
// الأعمدة المتوقعة: id, name, email, password, role, created_at
$sql = "SELECT id, name, email, role, created_at
        FROM users
        ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);

// الهيدر الخاص بلوحة التحكم
include_once __DIR__ . '/../includes/admin_header.php';
?>

<h2>إدارة المستخدمين</h2>

<?php
// رسالة بسيطة عند نجاح الحذف
if (!empty($_GET['msg']) && $_GET['msg'] === 'deleted') {
    echo '<p>تم حذف المستخدم بنجاح.</p>';
}
?>

<?php if ($res && mysqli_num_rows($res) > 0): ?>
    <!-- جدول عرض المستخدمين -->
    <table class="table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد</th>
                <th>الدور</th>
                <th>تاريخ الإنشاء</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <!-- الاسم -->
                <td><?php echo htmlspecialchars($row['name']); ?></td>

                <!-- البريد -->
                <td><?php echo htmlspecialchars($row['email']); ?></td>

                <!-- الدور -->
                <td><?php echo htmlspecialchars($row['role']); ?></td>

                <!-- تاريخ الإنشاء -->
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>

                <!-- روابط الإجراءات: تعديل / حذف -->
                <td>
                    <a  class="btn btn-small" href="edit_user.php?id=<?php echo intval($row['id']); ?>">تعديل</a>
                   
                    <a  class="btn btn-danger btn-small" href="users.php?delete=<?php echo intval($row['id']); ?>"
                       onclick="return confirm('هل تريد حذف هذا المستخدم نهائيًا؟');">
                       حذف
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- في حال لا توجد بيانات -->
    <p>لا يوجد مستخدمون للعرض حالياً.</p>
<?php endif; ?>

<?php
// الفوتر
include('../includes/footer.php');
?>
