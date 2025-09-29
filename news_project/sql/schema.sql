-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2025 at 01:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(10, 'التعليم', '2025-09-29 09:42:04'),
(11, 'الأخبار', '2025-09-29 09:42:11'),
(12, 'عمليات المقاومة', '2025-09-29 09:42:17'),
(13, 'رياضة', '2025-09-29 09:44:02'),
(14, 'الشهداء والجرحى', '2025-09-29 10:13:30'),
(15, 'المؤسسات', '2025-09-29 10:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `details`, `image`, `category_id`, `user_id`, `deleted`, `created_at`) VALUES
(1, 'خبر عاجل', 'قصف إسرائيلي على وسط غزة ، طائرات إسرائيلية استهدفت محيط برج السعدي في مخيم النصيرات وسط قطاع غزة\r\n\r\nاستهداف بالاستطلاع بشارع العشرين محيط ابراج عين جالوت بالنصيرات وسط القطاع\r\n\r\nتقدم دبابة وجرافة عسكرية وتقوم بأعمال هدم وتجريف وسط خانيونس', '1759143021.png', 11, 1, 0, '2025-09-23 19:30:54'),
(2, 'فوز فريق رياضي', 'فريق محلي في غزة يفوز بمباراة ودّية نظّمتها الأكاديميات الشابّة لدعم اللاعبين واستعادة الروح الرياضية شهدت إحدى الملاعب الشعبية في غزة مباراة ودّية جمعت فرقًا شبابية بمشاركة جمهور محدود، في إطار مبادرة لإحياء الأنشطة الرياضية وتخفيف الضغوط عن اللاعبين. المباراة انتهت بفوز الفريق المستضيف بهدفين مقابل هدف، وسط أداء لافت لحرّاس المرمى. القائمون على المبادرة أكدوا أن الهدف الأساسي هو الحفاظ على لياقة اللاعبين ومنحهم مساحة آمنة للتدريب، مع الالتزام بإجراءات السلامة. كما جرى توزيع أطقم رياضية متبرّع بها، وتوفير مياه للشرب وإسعافات أولية خلال اللقاء. وتستمر الأكاديميات في تنظيم بطولات مصغّرة لإبراز المواهب وتمهيد الطريق لعودة المنافسات الرسمية حال استقرار الأوضاع', '1759143031.png', 13, 1, 0, '2025-09-23 19:30:54'),
(3, 'التعليم في غزة', 'مدارس عدّة في غزة استأنفت الدوام الجزئي مع خطط طوارئ للتعليم المدمج وتوزيع مواد إثرائية للطلاب أعلنت مديريات التربية في غزة استئناف الدوام الجزئي في مجموعة من المدارس، مع تطبيق نموذج التعليم المدمج الذي يجمع بين الحضور المباشر والمواد الإثرائية المنزلية. وأشارت إدارات المدارس إلى اعتماد جداول مرنة تراعي أوضاع العائلات المتضررة وصعوبة التنقّل في بعض المناطق. كما بدأت مبادرات مجتمعية في توفير حقائب مدرسية ومواد تعليمية مطبوعة للطلبة الذين لا تتوفر لديهم إنترنت مستقر. المعلّمون نفّذوا جلسات دعم نفسي قصيرة داخل الصفوف، وجرى تخصيص حصص لتعويض الفاقد التعليمي في المواد الأساسية. وتعمل فرق الصيانة على إصلاح النوافذ والأسوار المتضررة، فيما تستمر الشراكات مع مؤسسات الإغاثة لتأمين المياه الصحية والإنارة الاحتياطية داخل المدارس', '1759143038.png', 10, 1, 0, '2025-09-23 19:30:54'),
(4, 'أسر جنود', 'تعلن كتائب القسام انقطاع التواصل مع الأسيرين (عمري ميران ومتان انغريست) نتيجة العمليات العسكرية العنيفة في حيي الصبرة وتل الهوا خلال الساعات الـ48 الأخيرة\r\n\r\nسرايا القدس: سيطرنا على طائرة إسرائيلية من نوع \"كواد كابتر\" خلال تنفيذها مهام استخبارية وسط قطاع غزة. \r\n\r\nاليوم الـ 724.. القسام يواصل القتال والإثخان في قوات العدو', '1759143050.png', 12, 1, 0, '2025-09-23 19:30:54'),
(10, 'شهداء اليوم', 'وصل إلى مستشفيات قطاع غزة 50 شهيدًا، و 184 إصابة خلال الـ24 ساعة الماضية.\r\n\r\n لا يزال عدد من الضحايا تحت الركام وفي الطرقات، حيث تعجز طواقم الإسعاف والدفاع المدني عن الوصول إليهم حتى اللحظة.', '1759143015.png', 14, 1, 0, '2025-09-29 10:36:13'),
(11, 'فتح تسجيل في مؤسسة', 'مؤسسة سنابل الخير تفتح أبواب التسجيل لنازحين فيها وتقوم بتقديم الخدمات عليك الإسراع بالتسجيل بالرابط', '1759143007.png', 15, 1, 0, '2025-09-29 10:41:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '323rgqag', 'admin', '2025-09-23 19:30:54'),
(25, 'adnan', 'adnan@gmail.com', 'adnan1234', 'user', '2025-09-29 11:01:40'),
(26, 'anas', 'anas@gmail.com', 'anas123444', 'user', '2025-09-29 11:03:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `news_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
