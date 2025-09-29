<?php
// ربط ملف الاتصال بقاعدة البيانات
include('config/config.php');

// بدء الجلسة
session_start();

// متغيرات للرسائل
$error = '';
$success = '';

// التحقق من إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // أخذ القيم من النموذج
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // التحقق من أن الحقول غير فارغة
    if ($name === '' || $email === '' || $password === '') {
        $error = 'الرجاء إدخال جميع الحقول.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // التحقق من صحة البريد الإلكتروني
        $error = 'البريد الإلكتروني غير صالح.';
    } else {
        // تأمين المدخلات
        $name_sql  = mysqli_real_escape_string($conn, $name);
        $email_sql = mysqli_real_escape_string($conn, $email);
        $pass_sql  = mysqli_real_escape_string($conn, $password);

        // التحقق هل البريد مستخدم مسبقًا
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_sql' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            $error = 'هذا البريد مسجّل بالفعل.';
        } else {
            // إدخال بيانات المستخدم الجديد 
            $q = "INSERT INTO users (name, email, password, role, created_at)
                  VALUES ('$name_sql', '$email_sql', '$pass_sql', 'user', NOW())";
            if (mysqli_query($conn, $q)) {
                $success = 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.';
                // تفريغ الحقول
                $name = $email = $password = '';
            } else {
                $error = 'حدث خطأ أثناء إنشاء الحساب.';
            }
        }
    }
}

// استدعاء الهيدر
include('includes/header.php');
?>

<div class="section container" dir="rtl">
    <h2>إنشاء حساب</h2>

    <?php if ($error): ?>
      <!-- رسالة خطأ   -->
      <div style="background:#d4edda; color:#155724; padding:10px; margin:12px 0; border-radius:5px;">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <!-- رسالة نجاح باللون الأخضر -->
      <div style="background:#d4edda; color:#155724; padding:10px; margin:12px 0; border-radius:5px;">
        <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <div class="card" style="max-width:640px;">
      <div class="card-body">
        <form method="post" action="signup.php">
          <div class="input-group" style="margin-bottom:10px;">
            <label style="width:100%;">الاسم الكامل
              <input type="text" name="name" required
                     value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </label>
          </div>

          <div class="input-group" style="margin-bottom:10px;">
            <label style="width:100%;">البريد الإلكتروني
              <input type="email" name="email" required
                     value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </label>
          </div>

          <div class="input-group" style="margin-bottom:14px;">
            <label style="width:100%;">كلمة المرور
              <input type="password" name="password" required>
            </label>
          </div>

          <button type="submit" class="btn">إنشاء حساب</button>
        </form>
      </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
