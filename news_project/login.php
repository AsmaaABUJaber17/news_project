<?php
include('config/config.php'); // يحتوي على الاتصال $conn
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        // البحث عن المستخدم حسب البريد
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = '$email' LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) === 1) {
            $user = mysqli_fetch_assoc($res);

            // مقارنة كلمة المرور 
            if ($password === $user['password']) {
                // نجاح
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // توجيه بناءً على الدور
                if ($user['role'] === 'admin') {
                    header('Location: admin-cp/dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error = 'كلمة المرور غير صحيحة';
            }
        } else {
            $error = 'البريد الإلكتروني غير موجود';
        }
    } else {
        $error = 'يرجى إدخال البريد الإلكتروني وكلمة المرور';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="/news_project/assets/img/favicon.svg">
  <title>تسجيل الدخول</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body{background:hwb(160 50% 19% / 0.25)}
    .login-card{max-width:400px;margin:5% auto}
  </style>
</head>
<body>
<div class="card shadow-sm login-card p-4">
  <h3 class="mb-3 text-center">تسجيل الدخول</h3>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">البريد الإلكتروني</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">كلمة المرور</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success w-100">دخول</button>
  </form>
  <p class="btn btn-outline">
    ليس لديك حساب؟ <a href="signup.php">إنشاء حساب</a>
  </p>
</div>
</body>
</html>
