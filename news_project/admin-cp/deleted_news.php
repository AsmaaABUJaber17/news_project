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

// استرجاع خبر (إلغاء الحذف)
if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']); // تحويل لرقم صحيح
    $sql = "UPDATE news SET deleted = 0 WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: deleted_news.php');
    exit;
}

// حذف خبر نهائياً
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // تحويل لرقم صحيح
    $sql = "DELETE FROM news WHERE id = $id LIMIT 1";
    mysqli_query($conn, $sql);
    header('Location: deleted_news.php');
    exit;
}

// جلب الأخبار المحذوفة
$sql = "
    SELECT n.*, c.name AS category_name, u.name AS author_name
    FROM news n
    LEFT JOIN categories c ON n.category_id = c.id
    LEFT JOIN users u ON n.user_id = u.id
    WHERE n.deleted = 1
    ORDER BY n.created_at DESC
";
$res = mysqli_query($conn, $sql);

// الهيدر الخاص بلوحة التحكم
include ('../includes/admin_header.php');
?>

<h2>الأخبار المحذوفة</h2>

<?php if ($res && mysqli_num_rows($res) > 0): ?>
    <!-- جدول الأخبار المحذوفة -->
    <table class="table">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>الفئة</th>
                <th>الكاتب</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <!-- العنوان -->
                <td><?php echo htmlspecialchars($row['title']); ?></td>

                <!-- الفئة -->
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>

                <!-- الكاتب -->
                <td><?php echo htmlspecialchars($row['author_name']); ?></td>

                <!-- روابط الإجراءات -->
                <td>
                    <a   class="btn btn-small" href="deleted_news.php?restore=<?php echo intval($row['id']); ?>">استرجاع</a>
                    |
                    <a   class="btn btn-danger btn-small" href="deleted_news.php?delete=<?php echo intval($row['id']); ?>"
                       onclick="return confirm('هل أنت متأكد من الحذف النهائي؟');">
                       حذف نهائي
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- في حال لا توجد أخبار محذوفة -->
    <p>لا توجد أخبار محذوفة حالياً.</p>
<?php endif; ?>

<?php
// الفوتر
include('../includes/footer.php');
?>
