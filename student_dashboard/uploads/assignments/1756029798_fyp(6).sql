-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2025 at 03:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'nasir', '$2y$10$E3GaLuvU4g7jnelciHrcc.WAv8bduoQYSS9ZkU9nsrZtI15mpin.G'),
(2, 'saeed', '$2y$10$4sIKk1x70KGM5S2EkF2HfOTlHnnTAJuLug8IlSBYIOn4TG1yRZete');

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `assignment_description` text NOT NULL,
  `due_date` date NOT NULL,
  `marks` int(100) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `assignment_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `teacher_id`, `course_id`, `class_id`, `assignment_title`, `assignment_description`, `due_date`, `marks`, `file_path`, `assignment_file`, `created_at`, `updated_at`) VALUES
(5, 10, 32, 28, 'hydrocarbons', 'make 10 names of hydrocarbons', '0010-10-10', 20, NULL, '', '2025-08-07 06:29:07', '2025-08-16 08:48:11'),
(7, 10, 32, 27, 'bio chemistry', 'what is relationship between chemistry and biology', '2025-03-07', 15, NULL, '', '2025-08-13 15:01:14', '2025-08-16 08:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `assignments_data`
--

CREATE TABLE `assignments_data` (
  `assignments_data_id` int(11) NOT NULL,
  `students_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `assignment_uploads` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assignments_data`
--

INSERT INTO `assignments_data` (`assignments_data_id`, `students_id`, `assignment_id`, `assignment_uploads`, `created_at`, `marks`, `course_id`, `class_id`) VALUES
(5, 11, 5, 'uploads/assignments/1754558481_amir doc.docx', '2025-08-07 14:21:21', 70, 32, 28),
(6, 20, 5, 'uploads/assignments/1755096057_amir z-1.pdf', '2025-08-13 19:40:57', 20, 32, 28),
(7, 10, 7, 'uploads/assignments/1755098560_amr doc 4.docx', '2025-08-13 20:22:41', 23, 32, 27);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent') NOT NULL DEFAULT 'present',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `class_id`, `course_id`, `teacher_id`, `date`, `status`, `created_at`) VALUES
(5, 10, 27, 32, 10, '2025-08-15', 'absent', '2025-08-15 09:15:13'),
(6, 12, 27, 32, 10, '2025-08-15', 'present', '2025-08-15 09:15:13'),
(7, 20, 28, 32, 10, '2025-08-15', 'present', '2025-08-15 09:15:47'),
(8, 11, 28, 32, 10, '2025-08-15', 'present', '2025-08-15 09:15:47'),
(9, 20, 28, 32, 10, '2025-08-15', 'absent', '2025-08-15 09:35:51'),
(10, 11, 28, 32, 10, '2025-08-15', 'present', '2025-08-15 09:35:51'),
(11, 18, 24, 32, 10, '2025-08-15', 'absent', '2025-08-15 11:09:38'),
(12, 19, 23, 32, 10, '2025-08-15', 'absent', '2025-08-15 18:21:00'),
(13, 17, 23, 32, 10, '2025-08-15', 'present', '2025-08-15 18:21:00'),
(14, 13, 27, 32, 10, '2025-08-16', 'present', '2025-08-16 08:32:29'),
(15, 10, 27, 32, 10, '2025-08-16', 'present', '2025-08-16 08:32:29'),
(16, 12, 27, 32, 10, '2025-08-16', 'present', '2025-08-16 08:32:29'),
(17, 14, 27, 32, 10, '2025-08-16', 'present', '2025-08-16 08:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`) VALUES
(6, '8'),
(7, 'class 5th'),
(11, 'class 3'),
(13, 'class 4th'),
(19, 'class 1 blue'),
(23, '1st year Pre-Medical'),
(24, '1st Year Pre-enginering'),
(25, '1st year ICT'),
(26, '1st year Arts'),
(27, '2nd year Pre-Medical'),
(28, '2nd Year Pre-enginering'),
(29, '2nd Year Arts'),
(30, '2nd Year ICT');

-- --------------------------------------------------------

--
-- Stand-in structure for view `class_totals`
-- (See below for the actual view)
--
CREATE TABLE `class_totals` (
`class_id` int(11)
,`class_name` varchar(255)
,`Girls_total` decimal(22,0)
,`Boys_total` decimal(22,0)
,`Total` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `created_at`, `updated_at`) VALUES
(27, 'maths', '2025-04-18 16:58:03', '2025-04-18 17:03:39'),
(32, 'chemistry', '2025-04-20 07:59:42', '2025-04-20 07:59:42'),
(37, 'Drawing', '2025-05-04 04:12:02', '2025-05-04 04:12:02'),
(43, 'psychology', '2025-07-25 09:09:31', '2025-07-25 09:09:31'),
(44, 'Urdu', '2025-07-26 09:34:51', '2025-07-26 09:34:51'),
(45, 'pakistan studies', '2025-07-27 17:38:56', '2025-07-27 17:38:56'),
(46, 'computer science', '2025-07-27 18:15:32', '2025-07-27 18:15:32'),
(47, 'Biology', '2025-07-27 18:16:12', '2025-07-27 18:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `sender_type` enum('teacher','student') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `teacher_id`, `student_id`, `course_id`, `class_id`, `sender_type`, `message`, `created_at`) VALUES
(11, 10, 10, 32, 27, 'teacher', 'how are you', '2025-08-15 16:49:25'),
(12, 10, 13, 32, 27, 'teacher', 'kya hal ha', '2025-08-15 16:56:02'),
(13, 10, 13, 32, 27, 'teacher', 's', '2025-08-15 17:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `time_limit` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `total_marks` int(11) NOT NULL,
  `quiz_type` varchar(50) DEFAULT 'Choose the Best Answer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `teacher_id`, `course_id`, `class_id`, `title`, `time_limit`, `start_time`, `end_time`, `total_marks`, `quiz_type`, `created_at`) VALUES
(5, 10, 32, 27, 'chemical formulas', 10, '2025-08-14 14:45:00', '2025-08-14 14:55:00', 2, 'Choose the Best Answer', '2025-08-14 05:13:17'),
(6, 10, 32, 27, 'general mcqs', 20, '2025-08-14 12:27:00', '2025-08-14 14:27:00', 20, 'Choose the Best Answer', '2025-08-14 07:40:22');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `attempted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`attempt_id`, `student_id`, `quiz_id`, `score`, `attempted_at`) VALUES
(5, 10, 6, 8.00, '2025-08-14 14:09:05'),
(7, 10, 5, 2.00, '2025-08-14 14:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`question_id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(2, 5, 'how many ears', '2', '10', '4', '5', 'A'),
(3, 6, 'water formula is :', 'H20', 'Co2', 'Nh3', 'H2So4', 'A'),
(4, 6, 'which gas is essential for breathing:', 'C2', 'O2', 'N2', 'H2', 'B'),
(5, 6, 'at night plant produce', 'C02', 'No2', 'O2', 'H3', 'A'),
(6, 6, 'acid rain is formed from :', 'zn', 'C2', 'N2', 'No2', 'D'),
(7, 6, 'ozone layer formula is:', 'O2', 'O3', 'O4', 'O7', 'B'),
(8, 6, 'methane is :', 'C2h6', 'C-H', 'CH3', 'CH4', 'D'),
(9, 6, 'ethyne is :', 'C2H2', 'C2H6', 'C2H', 'C2H9', 'A'),
(10, 6, 'C6H12O6 is', 'chloric acid', 'hydrochloricacid', 'sulphuric acid', 'acid', 'B'),
(11, 6, 'Fe is:', 'iron', 'zinc', 'argon', 'platinum', 'A'),
(12, 6, 'amino acid present in :', 'carbohydrates', 'vitiminsA', 'protein', 'fats', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `result_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_obtained` int(11) DEFAULT 0,
  `quiz_total` int(11) DEFAULT 0,
  `assignment_obtained` int(11) DEFAULT 0,
  `assignment_total` int(11) DEFAULT 0,
  `exam_obtained` int(11) DEFAULT 0,
  `exam_total` int(11) DEFAULT 0,
  `percentage` decimal(5,2) GENERATED ALWAYS AS ((`quiz_obtained` + `assignment_obtained` + `exam_obtained`) / nullif(`quiz_total` + `assignment_total` + `exam_total`,0) * 100) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `students_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `students_email` varchar(255) NOT NULL,
  `contact_num` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `country` varchar(100) NOT NULL,
  `provience` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `class` varchar(255) NOT NULL,
  `students_password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`students_id`, `first_name`, `last_name`, `students_email`, `contact_num`, `gender`, `country`, `provience`, `district`, `class`, `students_password`, `profile_picture`, `created_at`, `updated_at`, `class_id`) VALUES
(4, 'azeem', 'aman', 'azeem123@gmail.com', '03445662269', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Class A', '$2y$10$L/FjO4B7zpSXqWsU9YzIAugpC9XuKaM7EdDvew0RgLUikxiu47FX2', '67fbda2068967_IMG_20230426_170620_9.jpg', '2025-04-13 15:37:04', '2025-05-10 10:57:11', 6),
(5, 'aliyan', 'sakhi', 'aliyan123@gmail.com', '03445662278', 'Male', 'pakistan', 'gilgit', 'hunza', '5th', 'aliyan123', NULL, '2025-04-13 15:41:19', '2025-06-05 09:32:38', 7),
(6, 'naeem', 'aman', 'naeem123@gmail.com', '034891849142', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'class 5th look', '$2y$10$qcokZqZB23hlL/7dWscOluZkbqJB6aDPkANZ5bQPfuxze4MckoLRS', '67fbf67404d31_IMG_20230506_150834_9.jpg', '2025-04-13 17:37:56', '2025-06-05 09:35:10', 7),
(9, 'sultan', 'khan', 'sultan123@gmail.com', '0371647718417', 'Male', 'pakistan', 'india', 'hunza', 'jka', 'sultan123', NULL, '2025-07-25 09:46:41', '2025-07-25 09:46:41', 19),
(10, 'Neeha', 'ata', 'neeha123@gmail.com', '035674938762', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$3upfqGpkwPZ2NSrMnnvje.pN4zaplrbWPMCsx/3Mr5h23jaNq6zB2', '6884997731fbd_nasir.jpg', '2025-07-26 09:01:43', '2025-07-26 09:23:06', 27),
(11, 'Nasir ', 'hayat', 'nasir123@gmail.com', '035674938765', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$n3nMebU3vVlzZu0BBpUnxedh1GrFUKQ4XOzdlHIFxDTupT7NTSsuW', '68849bc421972_nasir.jpg', '2025-07-26 09:11:32', '2025-07-26 09:23:33', 28),
(12, 'Saeed', 'alam', 'saeed123@gmail.com', '03567543535', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$7OTA/QrY1rpW7V2jfB7pm.GVEJXI17GwlKXXImYDRRxpYj./XduVO', '6884a2f922540_nasir.jpg', '2025-07-26 09:42:17', '2025-07-26 09:43:52', 27),
(13, 'fatima', 'khan', 'fatima123@gmail.com', '0344562728722', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$bMtqEElBmJIItPJPh4kckuL7I2aFzmblh58eAfsRzFniWkxg.RmiO', '6884c63785736_Capture.PNG', '2025-07-26 12:12:39', '2025-07-26 12:12:39', 27),
(14, 'yasir', 'khan', 'yasir123@gmail.com', '0344562728111', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$t5dE2JM5p.lVIAlSqNPv0OV.zt7TK1dheq7ciQW.tZRvP8ReBRAL2', '6884c67112237_Capture.PNG', '2025-07-26 12:13:37', '2025-07-26 12:13:37', 27),
(15, 'muiz', 'alam', 'muiz123@gmail.com', '0311562728111', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$KS5q4v0X8eqj2XjqO.7HeuKtaXeZWb9CmReLeNjC5HVmgdQzNHCDK', '6884c69b7437a_Capture.PNG', '2025-07-26 12:14:19', '2025-07-26 12:14:19', 30),
(16, 'anjeela', 'alam', 'anjeela123@gmail.com', '0311162728111', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$0oYgtTyLkJlCk6XWYP89zOSbLtsj0VC9yNXsM/DAEWpGXO8ABVkc6', '6884c6be211a3_Capture.PNG', '2025-07-26 12:14:54', '2025-07-26 12:14:54', 30),
(17, 'rubina', 'khan', 'rubina123@gmail.com', '0311133333331', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$ajXLTFAred60Xh.Gk1lOx.M3h8bJfCQuULFjVbGaEbz1oQJYKLWhm', '6884c763288e7_Capture.PNG', '2025-07-26 12:17:39', '2025-07-26 12:17:39', 23),
(18, 'urooj', 'khan', 'urooj123@gmail.com', '0311144333331', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$w8YZHLhQNODiQb3MgBUa3e7o5WFvEFlKj4dIgVSYZAr8InU7M6Wea', '6884c788bf0e7_Capture.PNG', '2025-07-26 12:18:16', '2025-07-26 12:18:16', 24),
(19, 'aleena', 'hayat', 'aleenahayat321@gmail.com', '03457762762', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$SK10dd6JRxWIbjGedgvfNef24f1QsA.Y402PXWO2bHm.JSA1gMoEK', '6891c0dd0c34b_img.jpg', '2025-08-05 08:29:17', '2025-08-05 08:29:17', 23),
(20, 'izhar', 'karim', 'izhar123@gmail.com', '23494258284242', 'Male', 'pakistan', 'Gilgit_Baltistan', 'skardu', '', '$2y$10$aItcka./kCplpN.6GYKU1eekw55KQYIBZCUH.nIyOItFFRw0MKofO', '689ca2c07301e_tt.PNG', '2025-08-13 14:35:44', '2025-08-13 14:35:44', 28);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `student_id`, `quiz_id`, `question_id`, `selected_option`) VALUES
(1, 10, 5, 2, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `student_course_assign`
--

CREATE TABLE `student_course_assign` (
  `student_course_assign_id` int(11) NOT NULL,
  `students_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_course_assign`
--

INSERT INTO `student_course_assign` (`student_course_assign_id`, `students_id`, `class_id`, `course_id`) VALUES
(1, 5, 7, 32),
(2, 6, 7, 32),
(3, 5, 7, 32),
(4, 6, 7, 32),
(5, 4, 6, 32),
(6, 5, 7, 37),
(7, 6, 7, 37),
(8, 9, 19, 37),
(9, 5, 7, 43),
(10, 6, 7, 43),
(14, 11, 28, 27),
(15, 10, 27, 27),
(17, 11, 28, 32),
(18, 10, 27, 44),
(19, 11, 28, 44),
(21, 12, 27, 32),
(22, 11, 28, 45),
(23, 11, 28, 32),
(24, 11, 28, 45),
(25, 11, 28, 37),
(26, 20, 28, 27),
(27, 20, 28, 32),
(29, 10, 27, 32),
(30, 14, 27, 32),
(31, 18, 24, 32),
(32, 13, 27, 32),
(33, 19, 23, 32),
(34, 17, 23, 32),
(35, 20, 28, 45);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `teachers_email` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `country` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `subject_expertise` varchar(100) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `teachers_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `first_name`, `last_name`, `teachers_email`, `contact_number`, `gender`, `country`, `province`, `district`, `subject_expertise`, `qualification`, `profile_picture`, `teachers_password`, `created_at`, `updated_at`) VALUES
(3, 'karim', 'aman', 'karim123@gmail.com', '036561565132', 'Male', 'pakistan', 'gilgit', 'hunza', 'maths', 'BSCS', 'uploads/teacher_profiles/IMG_20230427_212626_3.jpg', '$2y$10$aXFa.pcblXTKklRe64PWreVrECq.iW5tv.rHdlSizl2uibe5AF.F.', '2025-04-14 08:16:35', '2025-04-14 08:16:35'),
(6, 'kamarn', 'ali', 'kamran123@gmail.com', '982093804984', 'Male', 'pakisyai', 'wegwg', 'gwgw', 'gwgw', 'gw', '', '', '2025-05-05 08:09:34', '2025-05-05 08:09:34'),
(8, 'qaimat', 'khan', 'qaimat123@gmail.com', '03123456789', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'maths', 'BS maths', 'uploads/teacher_profiles/nasir.jpg', '$2y$10$CEdbTbiUbYI7/tEgeiobluyFd9Ydkz2hJVVZEoPUwncbyCptVdiQu', '2025-07-26 08:55:05', '2025-07-26 08:55:05'),
(10, 'Anni', 'Aziz', 'ani123@gmail.com', '03123456789', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'chemistry', 'BS maths', 'uploads/teacher_profiles/MYXJ_20211214082910797_save.jpg', '$2y$10$8VjkYuwRdPJ8AfW/w4H56.uwCUOytgqfXe.vGioJnw1IsKGt/u0FC', '2025-07-26 08:55:59', '2025-07-26 08:55:59'),
(11, 'Nama', 'rani', 'nama123@gmail.com', '03123456789', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Urdu', 'MA Urdu', 'uploads/teacher_profiles/MYXJ_20211214082910797_save.jpg', '$2y$10$xiIpPpapl4HyaIYQ.AzYq.3D2K9yt5vEX.wSDF86vovavjDCqiawS', '2025-07-26 08:56:44', '2025-07-26 09:35:49'),
(12, 'hussun', 'bano', 'hussun123@gmail.com', '034567657625', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Computer sciences', 'BS computerscience', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$It7S6jor18RatYrENJ.r5ehcUxdI6KRaExgnuEaF28ReowO33CJgG', '2025-07-26 12:31:46', '2025-07-26 12:31:46'),
(13, 'Ejaz', 'Karim', 'ejaz123@gmail.com', '034567657335', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Pakistan studies', 'MA Pakistan Studies', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$VkJKsFf.RAlixDv/WJ24NeaInq/9W7zcXLeUYm.ogNgjgJo2/gfOO', '2025-07-26 12:33:15', '2025-07-26 12:33:15'),
(14, 'Salma', 'Amir', 'salma123@gmail.com', '034567623335', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Pakistan studies', 'MA URDU/Islamiyat', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$iirZWyqzDJz2IAMevg9vLOx3NsIQ0OlJLHbazb8mVmNuPWUWLS6uy', '2025-07-26 12:34:08', '2025-07-26 12:34:08'),
(15, 'Rizwana', 'khan', 'Rizwana123@gmail.com', '034567623335', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Pakistan studies', 'MA SST', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$QJR/LbwYLvRUhqT.M2.RBuxtJEKH9V2Joo11a6Yjk858eDERs/k4m', '2025-07-26 12:34:50', '2025-07-26 12:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assign`
--

CREATE TABLE `teacher_assign` (
  `teacher_assign_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `course_outline` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teacher_assign`
--

INSERT INTO `teacher_assign` (`teacher_assign_id`, `teacher_id`, `course_id`, `class_id`, `course_outline`) VALUES
(14, 3, 32, 7, NULL),
(17, 6, 32, 7, NULL),
(19, 3, 32, 6, NULL),
(21, 3, 37, 7, NULL),
(27, 3, 37, 19, NULL),
(28, 3, 37, 19, NULL),
(29, 6, 43, 7, NULL),
(36, 8, 27, 24, 'uploads/course_outlines/689378a7a63b8.pdf'),
(37, 8, 27, 28, 'uploads/course_outlines/689394e046361.docx'),
(38, 8, 27, 27, NULL),
(39, 10, 32, 27, 'uploads/course_outlines/6893670356e2e.pdf'),
(40, 10, 32, 28, 'uploads/course_outlines/6893998bd2366.docx'),
(41, 10, 32, 24, NULL),
(42, 10, 32, 23, NULL),
(43, 11, 44, 25, NULL),
(44, 11, 44, 23, NULL),
(45, 11, 44, 24, NULL),
(46, 11, 44, 26, NULL),
(47, 11, 44, 27, NULL),
(48, 11, 44, 28, NULL),
(49, 11, 44, 29, NULL),
(50, 11, 44, 30, NULL),
(51, 10, 32, 27, 'uploads/course_outlines/6893670356e2e.pdf'),
(52, 13, 45, 28, NULL),
(53, 10, 32, 28, 'uploads/course_outlines/6893998bd2366.docx'),
(54, 15, 45, 28, NULL),
(55, 12, 37, 28, NULL);

-- --------------------------------------------------------

--
-- Structure for view `class_totals`
--
DROP TABLE IF EXISTS `class_totals`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `class_totals`  AS SELECT `c`.`class_id` AS `class_id`, `c`.`class_name` AS `class_name`, sum(case when `s`.`gender` = 'female' then 1 else 0 end) AS `Girls_total`, sum(case when `s`.`gender` = 'male' then 1 else 0 end) AS `Boys_total`, count(`s`.`students_id`) AS `Total` FROM (`classes` `c` left join `students` `s` on(`c`.`class_id` = `s`.`class_id`)) GROUP BY `c`.`class_id`, `c`.`class_name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `assignments_data`
--
ALTER TABLE `assignments_data`
  ADD PRIMARY KEY (`assignments_data_id`),
  ADD KEY `students_id` (`students_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `classkey` (`class_id`),
  ADD KEY `course` (`course_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `fk_attendance_student` (`student_id`),
  ADD KEY `fk_attendance_class` (`class_id`),
  ADD KEY `fk_attendance_course` (`course_id`),
  ADD KEY `fk_attendance_teacher` (`teacher_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_name` (`course_name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `teacher_key` (`teacher_id`),
  ADD KEY `course_key` (`course_id`),
  ADD KEY `class_key` (`class_id`),
  ADD KEY `student_key` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`students_id`),
  ADD UNIQUE KEY `students_email` (`students_email`),
  ADD KEY `fk_class` (`class_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `student_course_assign`
--
ALTER TABLE `student_course_assign`
  ADD PRIMARY KEY (`student_course_assign_id`),
  ADD KEY `students_id` (`students_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `teachers_email` (`teachers_email`);

--
-- Indexes for table `teacher_assign`
--
ALTER TABLE `teacher_assign`
  ADD PRIMARY KEY (`teacher_assign_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `assignments_data`
--
ALTER TABLE `assignments_data`
  MODIFY `assignments_data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `students_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_course_assign`
--
ALTER TABLE `student_course_assign`
  MODIFY `student_course_assign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `teacher_assign`
--
ALTER TABLE `teacher_assign`
  MODIFY `teacher_assign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `assignments_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `assignments_data`
--
ALTER TABLE `assignments_data`
  ADD CONSTRAINT `assignments_data_ibfk_1` FOREIGN KEY (`students_id`) REFERENCES `students` (`students_id`),
  ADD CONSTRAINT `assignments_data_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`),
  ADD CONSTRAINT `classkey` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quizzes_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `class_key` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_key` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_key` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_key` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_course_assign`
--
ALTER TABLE `student_course_assign`
  ADD CONSTRAINT `student_course_assign_ibfk_1` FOREIGN KEY (`students_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_course_assign_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_course_assign_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_assign`
--
ALTER TABLE `teacher_assign`
  ADD CONSTRAINT `teacher_assign_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `teacher_assign_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `teacher_assign_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
