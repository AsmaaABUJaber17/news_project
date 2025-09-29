<?php
//  ربط قاعدة البيانات
include('../config/config.php');

//  بدء الجلسة
session_start();

//  التحقق من صلاحيات الأدمن
//     إذا المستخدم مش أدمن بنرجعه لتسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /news_project/login.php');
    exit;
}

//  الحذف الناعم (Soft Delete) عبر GET
//     عند الضغط على "حذف" بنغيّر قيمة deleted إلى 1
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // نحولها لرقم صحيح للحماية
    if ($id > 0) {
        $sql = "UPDATE news SET deleted = 1 WHERE id = $id";
        mysqli_query($conn, $sql);
    }
    // نرجع لنفس الصفحة مع باراميتر بسيط كرسالة نجاح
    header('Location: view_news.php?msg=deleted');
    exit;
}

//  جلب جميع الأخبار غير المحذوفة
$sql = "
    SELECT n.*, c.name AS category_name, u.name AS author_name
    FROM news n
    LEFT JOIN categories c ON n.category_id = c.id
    LEFT JOIN users u ON n.user_id = u.id
    WHERE (n.deleted = 0 OR n.deleted IS NULL)
    ORDER BY n.created_at DESC
";
$res = mysqli_query($conn, $sql);

//  الهيدر الخاص بلوحة التحكم
include('../includes/admin_header.php');
?>

<h2>جميع الأخبار</h2>

<?php
//  رسالة بسيطة عند نجاح النقل للمحذوفات
if (!empty($_GET['msg']) && $_GET['msg'] === 'deleted') {
    echo '<p>تم نقل الخبر إلى سلة المحذوفات.</p>';
}
?>

<?php if ($res && mysqli_num_rows($res) > 0): ?>
    <!--  جدول عرض الأخبار -->
    <table class="table">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>الفئة</th>
                <th>الكاتب</th>
                <th>الصورة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <!--  عنوان الخبر -->
                <td><?php echo htmlspecialchars($row['title']); ?></td>

                <!--  الفئة -->
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>

                <!--  الكاتب -->
                <td><?php echo htmlspecialchars($row['author_name']); ?></td>

                <!--  صورة مصغرة -->
                <td>
                    <img class="thumb"
                         src="/news_project/uploads/<?php echo htmlspecialchars($row['image'] ?: 'placeholder.png'); ?>"
                         alt="">
                </td>

                <!--  روابط الإجراءات: تعديل / حذف (Soft) -->
                <td>
                    <a class="btn btn-small" href="edit_news.php?id=<?php echo intval($row['id']); ?>">تعديل</a>
                    <a class="btn btn-danger btn-small"
                       href="view_news.php?delete=<?php echo intval($row['id']); ?>"
                       onclick="return confirm('هل تريد نقل الخبر إلى المحذوفات؟');">
                        حذف
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <!--  في حال لا توجد أخبار -->
    <p>لا توجد أخبار حالياً للعرض.</p>
<?php endif; ?>

<!--  رابط إلى صفحة الأخبار المحذوفة -->
<p><a class="btn" href="deleted_news.php">عرض الأخبار المحذوفة</a></p>

<?php
//  الفوتر
include('../includes/footer.php');
?>
