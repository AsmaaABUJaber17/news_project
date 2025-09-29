<?php
// تشغيل السيشن
if (session_status() == PHP_SESSION_NONE) session_start();

// ربط قاعدة البيانات
include('../config/config.php');

// تأكد أن المستخدم أدمن
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: /news_project/login.php");
    exit;
}

// جلب اسم المستخدم من السيشن
$adminName = $_SESSION['user_name'] ?? 'أدمن';

// تجيب عدد الأخبار 
$resNews = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM news WHERE deleted = 0");
$newsCount = $resNews ? mysqli_fetch_assoc($resNews)['cnt'] : 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>لوحة التحكم</title>
  <link rel="icon" type="image/svg+xml" href="/news_project/assets/img/favicon.svg">
<link rel="stylesheet" href="/news_project/assets/css/style.css">
</head>
<body>
<nav class="admin-header">
  <div class="container container-narrow">
    <a class="brand" href="../admin-cp/dashboard.php">
      نظام الأخبار - لوحة التحكم
    </a>

    <div class="nav-admin">
      <span class="hello">مرحباً، <?php echo htmlspecialchars($adminName); ?></span>
      <span class="badge bg-info">الأخبار: <?php echo $newsCount; ?></span>
    
      <a class="btn btn-sm btn-outline" href="../index.php">الموقع</a>
      <a class="btn btn-sm btn-danger" href="../logout.php">خروج</a>
    </div>
  </div>
</nav>

<main class="container container-narrow my-4">
