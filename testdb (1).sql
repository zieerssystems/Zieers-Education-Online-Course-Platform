-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 17, 2025 at 08:49 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adminsreg`
--

DROP TABLE IF EXISTS `adminsreg`;
CREATE TABLE IF NOT EXISTS `adminsreg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `adminsreg`
--

INSERT INTO `adminsreg` (`id`, `username`, `password`, `created_at`) VALUES
(13, 'haripriya', '$2y$10$isoheW4RMcMwJJe/y7FWg.UOzczH1CVko./Q7IXvMy46/PXSFDnry', '2025-05-26 05:17:17'),
(12, 'ashika', '$2y$10$Km/fjyuRGPEfcGSuYw4gfuDBUFCCA04okqirs2wlvCAz5VzpyKJW2', '2025-04-09 12:52:02'),
(11, 'Haripriya K T', '$2y$10$aLCNEdMtXYxzEhWitFHhee9EIxpSQTTp.NSWU.pnau6J8xl1.W1Wa', '2025-04-09 12:44:45'),
(10, 'hi', '$2y$10$ciQRtGN8TLqmdlvh/Acjxeo4xNEw7GKoBt087mjmJu33nhUrjGZYi', '2025-04-09 11:39:39'),
(9, 'aarya', '$2y$10$D0EaBC7wmvNmY6iMU6yl7OzttEk4h0W2n5s1FOcCc9Ka8GXCHagLm', '2025-04-09 09:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `course_id` int NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `offer_price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `subtotal` decimal(10,2) GENERATED ALWAYS AS ((`offer_price` * `quantity`)) STORED,
  `gst` decimal(10,2) GENERATED ALWAYS AS (((`offer_price` * `quantity`) * 0.18)) STORED,
  `total` decimal(10,2) GENERATED ALWAYS AS (((`offer_price` * `quantity`) * 1.18)) STORED,
  `session_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `order_id`, `course_id`, `course_name`, `price`, `offer_price`, `image`, `quantity`, `session_id`) VALUES
(97, 0, 1, 'Data Science with Python', 0.00, 0.00, '', 1, ''),
(96, 3, 3, 'AI for Computer Vision', 0.00, 0.00, '', 1, ''),
(95, 3, 80, 'ai in this world', 0.00, 0.00, '', 1, ''),
(94, 3, 67, 'Learn CyberSecurity', 0.00, 0.00, '', 1, ''),
(92, 1, 56, 'Data Science with Pythons', 0.00, 0.00, '', 1, ''),
(91, 78, 37, 'AI Mastery: From Fundamentals to Breakthrough', 0.00, 0.00, '', 1, ''),
(93, 1, 56, 'Data Science with Pythons', 0.00, 0.00, '', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

DROP TABLE IF EXISTS `checkout`;
CREATE TABLE IF NOT EXISTS `checkout` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `college_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `current_semester` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hear_about_us` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`id`, `first_name`, `last_name`, `city`, `phone`, `email`, `college_name`, `current_semester`, `hear_about_us`, `order_date`) VALUES
(1, 'HARIPRIYA', ' KT', 'KANNUR', '8590862475', 'haripriyakt33@gmail.com', 'St Aloysius College', '4', 'yy', '2025-06-16 11:08:31'),
(2, 'HARIPRIYA', ' KT', 'KANNUR', '8590862475', 'haripriyakt33@gmail.com', 'St Aloysius College', '4', 'yy', '2025-06-16 11:11:41'),
(3, 'HARIPRIYA', ' KT', 'KANNUR', '8590862475', '2317019haripriya@staloysius.ac.in', 'GASC', '4', 'dd', '2025-06-16 19:49:51');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int DEFAULT NULL,
  `course_name` varchar(255) NOT NULL,
  `description` text,
  `author_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `offer_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `subject_id`, `course_name`, `description`, `author_name`, `price`, `offer_price`, `image`, `created_at`) VALUES
(1, 1, 'Data Science with Python', 'The Ultimate Data Science Journeys', 'Mosh Hamedani', 1199.00, 120.00, '1750102750_1742915757_dbmsl.jpg', '2025-06-16 19:39:10'),
(2, 1, 'ds', 'The Ultimate Data Science Journeys', 'Mosh Hamedani', 120.00, 12.00, '1750102804_1742992520_cld2.jpg', '2025-06-16 19:40:04'),
(3, 2, 'AI for Computer Vision', 'hjjk', 'tom', 1200.00, 11.00, '1750103088_1742455215_ain.jpg', '2025-06-16 19:44:48'),
(4, 2, 'AI Mastery: From Fundamentals to Breakthroughs', 'Complete AI overview', 'Lovleen Bhatia', 1200.00, 125.00, '1750103284_1748528677_1742293983_JS3.jpg', '2025-06-16 19:48:04'),
(5, 4, 'Data Science Crash Course', 'A complete beginner’s guide to data science covering statistics, Python, and machine learning fundamentals.', 'Kirill Eremenko', 1200.00, 599.99, '1750143460_1742474339_d1.jpg', '2025-06-17 06:57:40'),
(6, 5, 'Artificial Intelligence Full Course', 'Introductory AI course exploring concepts like intelligent agents, search algorithms, and AI applications.', 'Andrew Ng', 1700.00, 450.00, '1750143546_1742455216_ain.jpg', '2025-06-17 06:59:06'),
(7, 6, 'Machine Learning for Beginners', 'Stanford’s legendary ML course—covers supervised learning, neural networks, and real-world ML problems.', 'Andrew Ng', 1340.00, 559.99, '1750143645_1742378030_m1.jpg', '2025-06-17 07:00:45'),
(8, 7, 'Java Programming Full Course', 'Complete Java programming fundamentals, covering OOP, control flow, data structures, and more.', 'Tim Buchalka', 1340.00, 450.00, '1750143757_1750103284_1748528677_1742293983_JS3.jpg', '2025-06-17 07:02:37'),
(9, 8, 'JavaScript Full Course', 'In-depth JavaScript course starting from basics to ES6+ features and DOM manipulation', 'Kyle Simpson', 1599.99, 800.00, '1750143843_1742378084_js.jpg', '2025-06-17 07:04:03'),
(10, 9, 'Python for Everybody', 'Beginner-friendly Python course that covers syntax, functions, loops, and practical examples.', 'Dr. Charles Severance', 1200.00, 450.00, '1750143916_1742378182_pyth.jpg', '2025-06-17 07:05:16'),
(11, 10, 'Cyber Security Full Course for Beginners', 'Essential cybersecurity concepts including network security, malware, phishing, and ethical hacking.', 'Mike Chapple', 1699.99, 450.00, '1750143990_1750102804_1742992520_cld2.jpg', '2025-06-17 07:06:30'),
(12, 11, 'Web Development Full Course', 'Covers HTML, CSS, JavaScript, and modern web design practices in one complete beginner-to-advanced course.', 'Angela Yu', 1400.00, 889.99, '1750144577_1750143757_1750103284_1748528677_1742293983_JS3.jpg', '2025-06-17 07:16:17'),
(13, 12, 'Data Structures and Algorithms', 'Deep dive into arrays, linked lists, trees, stacks, queues, and algorithm techniques with visuals.', 'Abdul Bari', 1200.00, 599.97, '1750144657_1742455544_ds0.jpg', '2025-06-17 07:17:37'),
(14, 13, 'PHP Full Course for Beginners', 'PHP fundamentals including form handling, database integration, and building a basic dynamic website.', 'Brad Traversy', 1600.00, 900.00, '1750144857_1742279783_php.jpg', '2025-06-17 07:20:57'),
(15, 14, 'DBMS Complete Tutorial', 'Full DBMS syllabus including ER models, normalization, SQL queries, and transaction management.', 'Saurabh Shukla', 1400.00, 670.00, '1750145076_1742899137_dbms.jpg', '2025-06-17 07:24:36'),
(16, 15, 'APTITUDE', 'Quantitative Aptitude Made Easy simplifies complex math concepts with clear explanations and shortcuts. Ideal for competitive exams, it builds strong problem-solving skills through practice questions and tips.', 'Dhanajay Kumar', 1200.00, 800.00, '1750145348_1745585016_speedmath.png', '2025-06-17 07:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `email`, `address`, `city`, `state`, `zip`, `total_amount`, `created_at`) VALUES
(1, 'HARIPRIYA K T', 'haripriyarajeev03@gmail.com', 'kakkoprath thekke veetil', 'KANNUR', 'Kerala', '670521', NULL, '2025-04-14 10:52:56'),
(2, 'HARIPRIYA K T', 'haripriyarajeev03@gmail.com', 'kakkoprath thekke veetil', 'KANNUR', 'Kerala', '670521', NULL, '2025-04-14 10:53:15'),
(3, 'HARIPRIYA K T', 'haripriyarajeev03@gmail.com', 'kakkoprath thekke veetil', 'KANNUR', 'Kerala', '670521', NULL, '2025-04-15 11:17:17'),
(4, '', '', '', '', '', '', NULL, '2025-04-15 11:41:22'),
(5, '', 'haripriyarajeev03@gmail.com', '', '', '', '', NULL, '2025-04-15 12:36:57'),
(6, '', '', '', '', '', '', NULL, '2025-04-15 12:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `description`, `created_at`) VALUES
(4, 'Data Science', 'Turning raw data into powerful insights that drive decisions and innovation', '2025-06-17 06:46:34'),
(5, 'Artificial Intelligence', 'Machines learning to think, reason, and act like humans—only faster', '2025-06-17 06:48:19'),
(6, 'Machine Learning', 'Teaching machines to learn from experience without being explicitly programmed.', '2025-06-17 06:48:48'),
(7, 'Java', 'Write once, run anywhere—Java powers robust and scalable applications worldwide.', '2025-06-17 06:49:25'),
(8, 'JavaScript', 'The heartbeat of dynamic web experiences, bringing interactivity to life', '2025-06-17 06:49:47'),
(9, 'Python', 'Simple yet powerful—Python makes coding elegant, readable, and fast.', '2025-06-17 06:50:09'),
(10, 'Cyber Security', 'Defending digital worlds from invisible threats, one firewall at a time.', '2025-06-17 06:50:32'),
(11, 'Web Development', 'Crafting digital worlds that connect, inform, and inspire users globally.', '2025-06-17 06:50:55'),
(12, 'Data Structures', 'The backbone of efficient algorithms—organizing data for speed and clarity.', '2025-06-17 06:51:20'),
(13, 'PHP', 'The server-side scripting engine behind countless dynamic websites and apps', '2025-06-17 06:51:50'),
(14, 'Database Management System', 'Structured storage and fast access—keeping data organized and available.', '2025-06-17 06:52:22'),
(15, 'Quantitative Aptitude Made Easy', 'It is a beginner-friendly guide that simplifies math concepts for competitive exams with step-by-step problem-solving techniques.', '2025-06-17 07:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL,
  `course_id` int NOT NULL,
  `video_link` varchar(255) NOT NULL,
  `description` text,
  `pdf_path` varchar(255) DEFAULT NULL,
  `video_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `subject_id`, `course_id`, `video_link`, `description`, `pdf_path`, `video_name`) VALUES
(88, 8, 74, 'https://youtu.be/JL_grPUnXzY?si=F1u1nrkh7xc0W9Gc', 'Learn how to extract insights from data using statistics, visualization, and tools like Python, R, and SQL. Ideal for analysts, scientists, and decision-makers.', '', 'WHAT IS DATASCIENCE ?'),
(89, 8, 56, 'https://youtu.be/-ETQ97mXXF0?si=1q2scGYt1NSI56Pv', 'video will help you understand and learn Data Science Algorithms in detail. This Data Science Tutorial is ideal for both beginners as well as professionals who want to master Data Science Algorithms.', '1748270609_1744113145_data-science-course-syllabus.pdf', 'Learn Data Science Tutorial'),
(63, 10, 37, 'https://www.youtube.com/watch?v=JMUxmLyrhSk', 'Artificial Intelligence Full Course will provide you with a comprehensive and detailed knowledge of Artificial Intelligence concepts with hands-on examples', '1746606396_artifical intelligence (oe).pdf', 'Artificial Intelligence Tutorial'),
(64, 11, 63, 'https://www.youtube.com/watch?v=wR0jg0eQsZA', 'This course covers the fundamentals of Database Management Systems (DBMS), including data models, SQL, and database design. Students gain hands-on experience with tools like MySQL, focusing on querying, normalization, and security. It prepares learners for careers in software development, data management, and related fields.', '1746612921_1744113127_1742198304_dbms1.pdf', 'DATABASE EDUCATION SYSTEM'),
(65, 13, 44, 'https://www.youtube.com/watch?v=rfscVS0vtbw', 'Learn the Python programming language in this full course for beginners! You will learn the fundamentals of Python and code two Python programs line-by-line. No previous programming experience is necessary before watching this course.', '1746612997_1744113087_1742280386_python-course-syllabus.pdf', 'Python for Beginners'),
(66, 14, 40, 'https://www.youtube.com/watch?v=grEKMHGYyns', 'Master Java – a must-have language for software development, Android apps, and more! This beginner-friendly course takes you from basics to real coding skills', '1746613074_1744113069_1742279180_java.pdf', 'Java Full Course for Beginners	'),
(79, 15, 39, 'https://youtu.be/EerdGm-ehJQ?si=_8l6u7JwE0cGkdI2', 'This JavaScript tutorial and JavaScript full course is a project based series of JavaScript tutorials for software engineers. Each JavaScript tutorial builds on a project and provides some JavaScript exercises to practice what we learned. By the end, we\'ll learn how to create complex, interactive websites with JavaScript, HTML, and CSS, which will help you become a web developer and software engineer.', '1746687679_1744113205_javascript-syllabus.pdf', 'JavaScript Tutorial Full Course'),
(68, 16, 41, ' https://www.youtube.com/watch?v=OK_JCtrrv-c', 'Learn the PHP programming language in this full course / tutorial. The course is designed for new programmers, and will introduce common programming topics using the PHP language.	', '1746613202_1742280488_Syllabus_of_PHP_&_MY_Sql.pdf', 'PHP Programming Language Tutorial'),
(69, 18, 42, ' https://www.youtube.com/watch?v=BZQ9AeXvxXQ', 'This Edureka Big Data & Hadoop Full Course video will help you understand and learn Hadoop concepts in detail. This Big Data & Hadoop Tutorial is ideal for both beginners as well as professionals who want to master the Hadoop Ecosystem. Below are the topics covered in this Big Data & Hadoop Tutorial.', '1746613283_1742280657_Essentials_of_Big_Data_Griet (1).pdf', 'Hadoop Tutorial For Beginners'),
(70, 79, 67, 'https://www.youtube.com/watch?v=ciNHn38EyRc', 'This Post Graduate Program in Cyber Security will help you learn comprehensive approaches to protecting your infrastructure and securing data, including risk analysis, mitigation, and compliance. You will get foundational to advanced skills through industry-leading cyber security certification courses that are part of the program.', '1746613356_1744113258_Cyber SYLLABUS 2023.pdf', 'What Is Cyber Security | How It Works?'),
(71, 77, 65, ' https://www.youtube.com/watch?v=zJJjx8Q_ixQ', 'Learn full-stack web development in this full course for beginners. First, you will learn the basics of HTML, CSS, and JavaScript. Then, you will learn how to put everything together to create a frontend movie search app. Finally, you will learn how to create a backend API to create movie reviews and connect the frontend to the backend. The backend uses Node.js, Express, and MongoDB.', '1746613451_1744113269_22619 -  Web Based Application development with PHP.pdf', 'Full Stack Web Development for Beginners'),
(72, 76, 64, 'https://www.youtube.com/watch?v=8hly31xKli0', 'Learn and master the most common data structures in this full course from Google engineer William Fiset. This course teaches data structures to beginners using high quality animations to represent the data structures visually. You will learn how to code various data structures together with simple to follow step-by-step instructions. Every data structure presented will be accompanied by some working source code (in Java) to solidify your understanding.	\r\n', '1746613561_DATA STRUCTURES.pdf', 'Data Structures Easy to Advanced Course'),
(86, 82, 72, 'https://youtu.be/7ZRNGrkU_Zs?si=cqubR2h5v6C8ltO7', 'Finding the LCM of Fractions seems to be a tedious and time taking process, but it is no more a problem. Thanks to the tricks of aptitude which is brought to you by \"Quant Guru\"', '', 'Finding LCM Fractions'),
(87, 82, 72, 'https://youtu.be/oixonnGEOxg?si=YbxAWOBWM9HX8q2B', 'Speed Maths is an art of using numbers, formulae or strategies to get the answer in speedy manner.  People who excel at mathematics use better strategies than the rest, they don\'t necessarily have better brains.', '', 'Speed MATHS'),
(84, 82, 72, 'https://youtu.be/rpUdIImgH0s?si=R6aQUJwlgsoXWu_u', 'Ratio and Proportion is one of the very important topics in the Quantitative Aptitude. Here we have explained the easiest method to find the Ratio of any 3 ', '', 'Ratio of a, b & c'),
(85, 82, 72, 'https://youtu.be/77jORSVyHp4?si=Ve69ST2JBRI4RRmT', 'Ratio and Proportion is one of the very important topics in the Quantitative Aptitude. Here we have explained the easiest method to find the Ratio of any numbers.\r\nRatio A,B,C And D\r\n', '', 'Ratio of a, b, c, d'),
(91, 8, 77, 'https://www.youtube.com/live/i4yFBXOUPxg?si=oMkRwaflWcqcZQsg', 'Unlock the power of data with cutting-edge analytics and machine learning to drive your business decisions.', '', 'Data Science'),
(98, 1, 2, 'https://youtube.com/shorts/ScGYeJeY6BA?si=Yfs4mUurgyA1Htxx', 'hkmnmrg', '1750102882_1750075419_1748936191_data-science-course-syllabus.pdf', 'hello'),
(94, 8, 78, 'https://youtu.be/4DlsTsqpY84?si=h6XppPG_QWhNu7-N', 'This is the first of seven courses in the Google Advanced Data Analytics Certificate, which will help you develop the skills you need to apply to more advanced data professional roles as an entry-level data scientist, advanced-level data analyst, or similar. Data professionals analyze data to help businesses make better decisions. To do this, they use powerful techniques like data storytelling, statistics, and machine learning. In this course, you’ll begin your learning journey by exploring the role of data professionals in the workplace. You’ll also learn about the project workflow PACE (Plan, Analyze, Construct, Execute) and how it can help you organize data projects.', '1750075419_1748936191_data-science-course-syllabus.pdf', 'Simply Learn DataScience'),
(95, 8, 79, 'https://youtu.be/4DlsTsqpY84?si=-b_3XHYd-Q7kJTGw', 'This is the first of seven courses in the Google Advanced Data Analytics Certificate, which will help you develop the skills you need to apply to more advanced data professional roles as an entry-level data scientist, advanced-level data analyst, or similar. Data professionals analyze data to help businesses make better decisions. To do this, they use powerful techniques like data storytelling, statistics, and machine learning. In this course, you’ll begin your learning journey by exploring the role of data professionals in the workplace. You’ll also learn about the project workflow PACE (Plan, Analyze, Construct, Execute) and how it can help you organize data projects.', '1750076684_1748936191_data-science-course-syllabus.pdf', 'Simply Learn DataScience'),
(97, 1, 1, 'https://youtube.com/shorts/cueClFR-mNk?si=haAAT6uH8mLguMT-', 'hi', '1750102843_1750075419_1748936191_data-science-course-syllabus.pdf', 'i know'),
(99, 2, 3, 'https://www.youtube.com/watch?v=Cu9BuwJ5GGU45', 'hi', '1750103124_1750102843_1750075419_1748936191_data-science-course-syllabus.pdf', 'Artificial Intelligence Tutorial'),
(100, 2, 4, 'https://www.youtube.com/watch?v=Cu9BuwJ5GGU4', 'ddd', '1750103330_1748270609_1744113145_data-science-course-syllabus.pdf', 'Artificial Intelligence Tutorial'),
(101, 4, 5, 'https://youtu.be/ua-CiDNNj30?si=M_IfbLhJMYKTB7qj', 'Learn Data Science is this full tutorial course for absolute beginners. Data science is considered the \"sexiest job of the 21st century.\" You\'ll learn the important elements of data science. You\'ll be introduced to the principles, practices, and tools that make data science the powerful medium for critical insight in business and research. You\'ll have a solid foundation for future learning and applications in your work. With data science, you can do what you want to do, and do it better. This course covers the foundations of data science, data sourcing, coding, mathematics, and statistics.', '1750145459_1744113145_data-science-course-syllabus.pdf', 'Learn Data Science Tutorial - Full Course for Beginners'),
(102, 5, 6, 'https://youtu.be/JMUxmLyrhSk?si=Z4vhCb44EdmrzBgk', 'This course introduces the core principles of Artificial Intelligence, including intelligent agents, search algorithms, and problem-solving techniques.\r\nIt explores machine learning basics, logic-based agents, and natural language processing.\r\nIdeal for beginners, it blends theory with real-world AI applications.\r\nTaught by AI expert Andrew Ng, it\'s a top choice to start your AI journey', '1750145619_1746606140_1744113185_artifical intelligence (oe).pdf', 'Artificial Intelligence Full Course'),
(103, 6, 7, 'https://youtu.be/Gv9_4yMHFhI?si=Insy9P3CVxA-4C1l', 'Machine Learning is one of those things that is chock full of hype and confusion terminology. In this StatQuest, we cut through all of that to get at the most basic ideas that make a foundation for the whole thing. These ideas are simple and easy to understand. After watching this StatQuest, you\'ll be ready to learn all kinds of new and exciting things about Machine Learning.\r\n', '1750145720_ML Course Syllabus-Aug2022.pdf', ' A Gentle Introduction to Machine Learning'),
(104, 7, 8, 'https://youtu.be/GoXwIVyNvX0?si=0V3VSQhwSO3RHUqq', 'Java is a general-purpose programming language. Learn how to program in Java in this full tutorial course. This is a complete Java course meant for absolute beginners. No prior programming experience is required.', '1750145789_1743409427_java.pdf', 'Intro to Java Programming - Course for Absolute Beginners'),
(105, 8, 9, 'https://youtu.be/EerdGm-ehJQ?si=m5zDKRnjlJj7vDL0', '🎓 Enroll to get a Certificate of Completion and an improved learning experience (breakdown into smaller videos covering specific topics, ad-free content, and progress tracking).\r\n✅ Don\'t worry if you\'re halfway through the course or finished the course, you can skip the lessons you already finished and take the final test to get your Certificate.\r\n❤️ Thanks for supporting SuperSimpleDev!', '1750145889_1744113205_javascript-syllabus.pdf', 'JavaScript Tutorial Full Course - Beginner to Pro'),
(106, 9, 10, 'https://youtu.be/8DvywoWv6fI?si=jj3gqHLTKYszUtqO', 'This Python 3 tutorial course aims to teach everyone the basics of programming computers using Python. The course has no pre-requisites and avoids all but the simplest mathematics. ', '1750145962_1742209206_py1.pdf', 'Python for Everybody - Full University Python Course'),
(107, 10, 11, 'https://youtu.be/inWWhr5tnEA?si=011xyvXjKK5Gz-bo', 'What Is Cyber Security In 7 Minutes will explain what is cyber security, how it works, why cyber security, who is a cyber security expert, and what are the different types of cyberattacks with examples. Now, let\'s begin this cyber security video!', '1750146046_1744113258_Cyber SYLLABUS 2023.pdf', 'What Is Cyber Security'),
(108, 11, 12, 'https://youtu.be/Q33KBiDriJY?si=VZK7whQdysXaGCHz', ' Web Development Full Course video will help you understand and learn Web Development in detail. You will learn about the Web Development Complete Roadmap to become a successful web developer. This Web Development Tutorial is ideal for both beginners as well as professionals who want to master Web Development technologies. Below are the web development topics covered in this Web Development ', '1750146169_22619 -  Web Based Application development with PHP.pdf', 'Web Development Full Course - 10 Hours'),
(109, 12, 13, 'https://youtu.be/RBSGKlAvoiM?si=I6O8GMeH5sqPdtlT', 'Learn and master the most common data structures in this full course from Google engineer William Fiset. This course teaches data structures to beginners using high quality animations to represent the data structures visually.', '1750146245_DATA STRUCTURES.pdf', 'Data Structures Easy to Advanced Course '),
(110, 13, 14, 'https://youtu.be/OK_JCtrrv-c?si=dx8m7afTuhoevQ85', 'Learn the PHP programming language in this full course / tutorial. The course is designed for new programmers, and will introduce common programming topics using the PHP language.', '1750146377_1744113225_1742280488_Syllabus_of_PHP_&_MY_Sql.pdf', 'PHP Programming Language Tutorial '),
(111, 14, 15, 'https://youtu.be/ztHopE5Wnpc?si=T4s9udH2_ZcwSDv9', 'This database design course will help you understand database concepts and give you a deeper grasp of database design. \r\n\r\nDatabase design is the organisation of data according to a database model. The designer determines what data must be stored and how the data elements interrelate. With this information, they can begin to fit the data to the database model.', '1750146510_CS4550_DBMS.pdf', 'Database Design Course - Learn how to design and plan a database for beginners'),
(112, 15, 16, 'https://youtu.be/lW_tM1rBaEg?si=6X-R3kU0uvYziJ_N', 'Quant Guru is a initiative to teach Quantitative aptitude to everyone without any cost. Quant is an integral part of aptitude exams (both in Govt. Exams & Placement Drive) in India. It tests the quantitative skills along with logical and analytical skills. One can test their own number of handling techniques and problem-solving skills by solving these questions.', '', 'Syllogism'),
(113, 15, 16, 'https://www.youtube.com/live/FI6W88NvWyc?si=oHuM6f5sbVIj9ksk', 'EASY METHOD', '', 'Calendar'),
(114, 15, 16, 'https://youtu.be/4Q4oqaIk_a4?si=OeJPjbV5J1-5e-oW', '\r\nQuant Guru is a initiative to teach Quantitative aptitude to everyone without any cost. Quant is an integral part of aptitude exams (both in Govt. Exams & Placement Drive) in India. It tests the quantitative skills along with logical and analytical skills. One can test their own number of handling techniques and problem-solving skills by solving these questions.', '', 'BLOOD RELATION '),
(115, 4, 17, 'https://youtu.be/ua-CiDNNj30?si=0qBj44Duw15f5Gx8', 'Learn Data Science is this full tutorial course for absolute beginners. Data science is considered the \"sexiest job of the 21st century.\" You\'ll learn the important elements of data science. You\'ll be introduced to the principles, practices, and tools that make data science the powerful medium for critical insight in business and research. You\'ll have a solid foundation for future learning and applications in your work. With data science, you can do what you want to do, and do it better. This course covers the foundations of data science, data sourcing, coding, mathematics, and statistics.', '1750147022_data-science-course-syllabus.pdf', 'Simply Learn DataScience');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
