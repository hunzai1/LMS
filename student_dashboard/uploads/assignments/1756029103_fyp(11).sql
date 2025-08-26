-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2025 at 11:49 AM
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
(1, 'Nasir Hayat', '$2y$10$P4Y8GfhijOTlF02k31i3KemO/AWunTq.2YNfv3PWtFY7dk2Q0OjJi'),
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
(10, 10, 32, 27, 'hydrocarbon', 'make at least 10 hydro carbon names', '2025-12-21', 15, NULL, '', '2025-08-17 05:06:54', '2025-08-17 05:06:54'),
(11, 10, 32, 27, 'bio chemistry', 'what is relation of chemitry with biology', '2025-12-21', 5, NULL, '', '2025-08-17 05:07:36', '2025-08-17 05:07:36'),
(12, 11, 44, 27, 'general ', 'pakistan k bara ma mazboon likhaa', '2025-08-19', 30, NULL, '', '2025-08-18 08:43:51', '2025-08-18 08:43:51'),
(13, 8, 27, 27, 'addition', 'add 34+23', '2025-12-12', 30, NULL, '', '2025-08-20 09:40:55', '2025-08-20 09:42:14'),
(14, 10, 32, 28, 'assignment 1', 'make assignment on chemistry and its branches', '2025-08-23', 40, NULL, '', '2025-08-22 05:00:54', '2025-08-22 05:00:54');

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
(8, 10, 10, 'uploads/assignments/1755407537_Assignment 1.docx', '2025-08-17 10:12:17', 15, 32, 27),
(9, 10, 11, 'uploads/assignments/1755407560_Assignment 2.docx', '2025-08-17 10:12:40', 5, 32, 27),
(10, 12, 10, 'uploads/assignments/1755413263_amr doc 4.docx', '2025-08-17 11:47:43', 5, 32, 27),
(11, 12, 11, 'uploads/assignments/1755414987_FYP.docx', '2025-08-17 12:16:27', 5, 32, 27),
(12, 10, 12, 'uploads/assignments/1755679551_Ù¾Ø§Ú©Ø³ØªØ§Ù†.docx', '2025-08-19 13:45:51', 25, 44, 27),
(14, 10, 13, 'uploads/assignments/1755683352_updated cv.docx', '2025-08-20 14:49:12', 25, 27, 27),
(15, 24, 14, 'uploads/assignments/1755838891_js for my lms learn.docx', '2025-08-22 10:01:31', 38, 32, 28);

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
(17, 14, 27, 32, 10, '2025-08-16', 'present', '2025-08-16 08:32:29'),
(18, 20, 28, 32, 10, '2025-08-16', 'absent', '2025-08-16 15:10:35'),
(19, 11, 28, 32, 10, '2025-08-16', 'present', '2025-08-16 15:10:35'),
(20, 18, 24, 32, 10, '2025-08-14', 'absent', '2025-08-16 16:35:24'),
(21, 18, 24, 32, 10, '2025-08-13', 'present', '2025-08-16 16:36:09'),
(22, 18, 24, 32, 10, '2025-07-16', 'present', '2025-08-16 16:36:40'),
(23, 18, 24, 32, 10, '2025-08-12', 'present', '2025-08-16 16:37:27'),
(24, 18, 24, 32, 10, '2025-06-16', 'absent', '2025-08-16 16:37:53'),
(25, 13, 27, 32, 10, '2025-08-17', 'present', '2025-08-17 05:01:32'),
(26, 10, 27, 32, 10, '2025-08-17', 'present', '2025-08-17 05:01:32'),
(27, 12, 27, 32, 10, '2025-08-17', 'present', '2025-08-17 05:01:32'),
(28, 14, 27, 32, 10, '2025-08-17', 'present', '2025-08-17 05:01:32'),
(29, 20, 28, 32, 10, '2025-08-17', 'present', '2025-08-17 05:02:23'),
(30, 11, 28, 32, 10, '2025-08-17', 'present', '2025-08-17 05:02:23'),
(31, 18, 24, 32, 10, '2025-08-17', 'present', '2025-08-17 05:02:48'),
(32, 19, 23, 32, 10, '2025-08-17', 'absent', '2025-08-17 07:37:39'),
(33, 17, 23, 32, 10, '2025-08-17', 'present', '2025-08-17 07:37:39'),
(34, 13, 27, 32, 10, '2025-08-19', 'present', '2025-08-19 04:04:39'),
(35, 10, 27, 32, 10, '2025-08-19', 'present', '2025-08-19 04:04:39'),
(36, 12, 27, 32, 10, '2025-08-19', 'present', '2025-08-19 04:04:39'),
(37, 20, 28, 32, 10, '2025-08-19', 'present', '2025-08-19 04:05:15'),
(38, 11, 28, 32, 10, '2025-08-19', 'present', '2025-08-19 04:05:15'),
(39, 20, 28, 27, 8, '2025-08-21', 'present', '2025-08-21 05:38:03'),
(40, 11, 28, 27, 8, '2025-08-21', 'present', '2025-08-21 05:38:03'),
(41, 23, 28, 32, 10, '2025-08-22', 'present', '2025-08-22 04:19:05'),
(42, 20, 28, 32, 10, '2025-08-22', 'present', '2025-08-22 04:19:05'),
(43, 22, 28, 32, 10, '2025-08-22', 'present', '2025-08-22 04:19:05'),
(44, 11, 28, 32, 10, '2025-08-22', 'present', '2025-08-22 04:19:05');

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
(30, '2nd Year ICT'),
(31, 'dra'),
(32, 'jkj');

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
(44, 'Urdu', '2025-07-26 09:34:51', '2025-07-26 09:34:51'),
(45, 'pakistan studies', '2025-07-27 17:38:56', '2025-07-27 17:38:56'),
(46, 'computer science', '2025-07-27 18:15:32', '2025-07-27 18:15:32'),
(47, 'Biology', '2025-07-27 18:16:12', '2025-07-27 18:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `grade_name` varchar(5) NOT NULL,
  `min_percentage` int(11) NOT NULL,
  `max_percentage` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `grade_name`, `min_percentage`, `max_percentage`, `created_at`) VALUES
(3, 'A+', 80, 100, '2025-08-20 10:33:44'),
(5, 'A', 70, 79, '2025-08-20 10:35:07'),
(6, 'B', 60, 69, '2025-08-20 10:35:22'),
(7, 'C', 50, 59, '2025-08-20 10:35:37'),
(8, 'D', 40, 49, '2025-08-20 10:35:57'),
(9, 'fail', 0, 39, '2025-08-20 10:36:11');

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
(6, 10, 32, 27, 'general mcqs', 20, '2025-08-14 12:27:00', '2025-08-14 14:27:00', 20, 'Choose the Best Answer', '2025-08-14 07:40:22'),
(7, 11, 44, 27, 'general', 10, '2025-08-20 13:40:00', '2025-08-20 13:50:00', 10, 'Choose the Best Answer', '2025-08-20 08:40:52'),
(8, 10, 32, 27, 'formulas', 10, '2025-08-21 12:07:00', '2025-08-21 12:15:00', 4, 'Choose the Best Answer', '2025-08-21 07:05:40');

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
(7, 10, 5, 5.00, '2025-08-14 14:47:29'),
(8, 12, 5, 4.00, '2025-08-17 20:40:45'),
(9, 10, 7, 4.00, '2025-08-20 13:41:32'),
(10, 10, 8, 4.00, '2025-08-21 12:07:28');

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
(12, 6, 'amino acid present in :', 'carbohydrates', 'vitiminsA', 'protein', 'fats', 'A'),
(13, 7, 'pakistan kab bana?', '1947', '2019', '1800', '1829', 'A'),
(14, 7, 'urdu k kya mani ha?', 'neshani', 'watan', 'lashkar', 'malik', 'C'),
(15, 7, 'national game kya ha?', 'walliball', 'hockey', 'tennis', 'bedmanten', 'B'),
(16, 7, 'national janwar konsa ha?', 'cow', 'billi', 'kutaa', 'markhor', 'D'),
(17, 7, '------ ko shaira mashriq kaha jata ha?', 'allama iqbal', 'quaid-e-azam', 'mirza ghalib', 'nazeer akbar abadi', 'A'),
(18, 8, 'what is formula for water', 'co2', 'h2o', 'n20', 'ha', 'B'),
(19, 8, 'salt formula', 'nacl', 'he', 'ar', 'a', 'A');

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
  `quiz_obtained` decimal(10,2) DEFAULT NULL,
  `quiz_total` int(11) DEFAULT 0,
  `assignment_obtained` decimal(10,2) DEFAULT NULL,
  `assignment_total` int(11) DEFAULT 0,
  `exam_obtained` decimal(10,2) DEFAULT NULL,
  `exam_total` int(11) DEFAULT 0,
  `percentage` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`result_id`, `teacher_id`, `course_id`, `class_id`, `student_id`, `quiz_obtained`, `quiz_total`, `assignment_obtained`, `assignment_total`, `exam_obtained`, `exam_total`, `percentage`, `created_at`, `updated_at`) VALUES
(38, 10, 32, 27, 10, 19.62, 30, 20.00, 20, 45.00, 50, 84.62, '2025-08-19 08:12:24', '2025-08-22 05:03:06'),
(41, 10, 32, 27, 13, 0.00, 30, 0.00, 20, NULL, 50, 0.00, '2025-08-19 08:15:10', '2025-08-22 05:03:06'),
(42, 10, 32, 27, 12, 4.62, 30, 10.00, 20, NULL, 50, 14.62, '2025-08-19 08:15:32', '2025-08-22 05:03:06'),
(64, 10, 32, 28, 11, 0.00, 10, 0.00, 40, 69.00, 60, 62.73, '2025-08-19 09:57:08', '2025-08-22 05:05:56'),
(65, 10, 32, 28, 20, 0.00, 10, 0.00, 40, 50.00, 60, 45.45, '2025-08-19 09:57:08', '2025-08-22 05:05:56'),
(66, 10, 32, 27, 14, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, '2025-08-19 10:01:33', '2025-08-22 05:03:06'),
(67, 11, 44, 27, 10, 4.00, 10, 33.33, 40, 48.00, 60, 77.57, '2025-08-20 08:48:59', '2025-08-20 09:28:33'),
(68, 11, 44, 27, 13, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-20 08:48:59', '2025-08-20 09:28:19'),
(69, 11, 44, 27, 12, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-20 08:48:59', '2025-08-20 09:28:19'),
(70, 11, 44, 27, 14, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-20 08:48:59', '2025-08-20 09:28:19'),
(71, 8, 27, 27, 10, 0.00, 20, 33.33, 20, 0.00, 60, 33.00, '2025-08-20 09:51:10', '2025-08-21 04:29:47'),
(72, 8, 27, 27, 13, 0.00, 20, 0.00, 20, 0.00, 60, 0.00, '2025-08-21 04:28:39', '2025-08-21 04:29:47'),
(73, 8, 27, 27, 12, 0.00, 20, 0.00, 20, 0.00, 60, 0.00, '2025-08-21 04:28:39', '2025-08-21 04:29:47'),
(74, 8, 27, 27, 14, 0.00, 20, 0.00, 20, 0.00, 60, 0.00, '2025-08-21 04:28:39', '2025-08-21 04:29:47'),
(75, 8, 27, 28, 11, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, '2025-08-21 05:36:30', '2025-08-21 05:36:35'),
(76, 8, 27, 28, 20, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, '2025-08-21 05:36:30', '2025-08-21 05:36:35'),
(77, 10, 32, 28, 23, 0.00, 10, 0.00, 40, 28.00, 60, 25.45, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(78, 10, 32, 28, 22, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(79, 10, 32, 28, 32, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(80, 10, 32, 28, 34, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(81, 10, 32, 28, 37, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(82, 10, 32, 28, 35, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(83, 10, 32, 28, 36, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(84, 10, 32, 28, 24, 0.00, 10, 38.00, 40, 56.00, 60, 85.45, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(85, 10, 32, 28, 26, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(86, 10, 32, 28, 27, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(87, 10, 32, 28, 33, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(88, 10, 32, 28, 29, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(89, 10, 32, 28, 30, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(90, 10, 32, 28, 31, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(91, 10, 32, 28, 28, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56'),
(92, 10, 32, 28, 25, 0.00, 10, 0.00, 40, 0.00, 60, 0.00, '2025-08-22 05:04:05', '2025-08-22 05:05:56');

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
(5, 'aliyan', 'sakhi', 'aliyan123@gmail.com', '03445662278', 'Male', 'pakistan', 'gilgit', 'hunza', '5th', '$2y$10$tnl0jLw.qycALuh7Cuu4kuBZPaiHi6XL1ckzrFF1SlbxuCAOe9BOi', NULL, '2025-04-13 15:41:19', '2025-08-22 04:03:51', 7),
(6, 'naeem', 'aman', 'naeem123@gmail.com', '034891849142', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'class 5th look', '$2y$10$qcokZqZB23hlL/7dWscOluZkbqJB6aDPkANZ5bQPfuxze4MckoLRS', '67fbf67404d31_IMG_20230506_150834_9.jpg', '2025-04-13 17:37:56', '2025-06-05 09:35:10', 7),
(10, 'Neeha', 'ata', 'neeha123@gmail.com', '035674938762', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$3upfqGpkwPZ2NSrMnnvje.pN4zaplrbWPMCsx/3Mr5h23jaNq6zB2', '1755848199_neeha ata.jpg', '2025-07-26 09:01:43', '2025-08-22 07:36:39', 27),
(11, 'Nasir ', 'hayat', 'nasir123@gmail.com', '035674938765', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$n3nMebU3vVlzZu0BBpUnxedh1GrFUKQ4XOzdlHIFxDTupT7NTSsuW', '68849bc421972_nasir.jpg', '2025-07-26 09:11:32', '2025-07-26 09:23:33', 28),
(12, 'Saeed', 'alam', 'saeed123@gmail.com', '03567543535', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$7OTA/QrY1rpW7V2jfB7pm.GVEJXI17GwlKXXImYDRRxpYj./XduVO', '1755848379_saeed.jpg', '2025-07-26 09:42:17', '2025-08-22 07:39:39', 27),
(13, 'fatima', 'khan', 'fatima123@gmail.com', '0344562728722', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$bMtqEElBmJIItPJPh4kckuL7I2aFzmblh58eAfsRzFniWkxg.RmiO', '1755848044_fatima khan.jpg', '2025-07-26 12:12:39', '2025-08-22 07:34:04', 27),
(14, 'yasir', 'khan', 'yasir123@gmail.com', '0344562728111', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$t5dE2JM5p.lVIAlSqNPv0OV.zt7TK1dheq7ciQW.tZRvP8ReBRAL2', '6884c67112237_Capture.PNG', '2025-07-26 12:13:37', '2025-07-26 12:13:37', 27),
(15, 'muiz', 'alam', 'muiz123@gmail.com', '0311562728111', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$KS5q4v0X8eqj2XjqO.7HeuKtaXeZWb9CmReLeNjC5HVmgdQzNHCDK', '6884c69b7437a_Capture.PNG', '2025-07-26 12:14:19', '2025-07-26 12:14:19', 30),
(16, 'anjeela', 'alam', 'anjeela123@gmail.com', '0311162728111', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$0oYgtTyLkJlCk6XWYP89zOSbLtsj0VC9yNXsM/DAEWpGXO8ABVkc6', '6884c6be211a3_Capture.PNG', '2025-07-26 12:14:54', '2025-07-26 12:14:54', 30),
(17, 'rubina', 'khan', 'rubina123@gmail.com', '0311133333331', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$ajXLTFAred60Xh.Gk1lOx.M3h8bJfCQuULFjVbGaEbz1oQJYKLWhm', '6884c763288e7_Capture.PNG', '2025-07-26 12:17:39', '2025-07-26 12:17:39', 23),
(18, 'urooj', 'khan', 'urooj123@gmail.com', '0311144333331', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$w8YZHLhQNODiQb3MgBUa3e7o5WFvEFlKj4dIgVSYZAr8InU7M6Wea', '6884c788bf0e7_Capture.PNG', '2025-07-26 12:18:16', '2025-07-26 12:18:16', 24),
(19, 'aleena', 'hayat', 'aleenahayat321@gmail.com', '03457762762', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', '', '$2y$10$SK10dd6JRxWIbjGedgvfNef24f1QsA.Y402PXWO2bHm.JSA1gMoEK', '6891c0dd0c34b_img.jpg', '2025-08-05 08:29:17', '2025-08-05 08:29:17', 23),
(20, 'izhar', 'karim', 'izhar123@gmail.com', '23494258284242', 'Male', 'pakistan', 'Gilgit_Baltistan', 'skardu', '', '$2y$10$aItcka./kCplpN.6GYKU1eekw55KQYIBZCUH.nIyOItFFRw0MKofO', '689ca2c07301e_tt.PNG', '2025-08-13 14:35:44', '2025-08-13 14:35:44', 28),
(21, 'amir', 'hayat', 'amir123@gmail.com', '92994927489', 'Male', 'ddqdq', '', '0', '', '$2y$10$Zfq1fcJJpx0SL0qcAvIGV.YHro1RRN46e39aEEiNyKH7Q4gToyF9i', '1755833984_IMG_20230426_170608_5.jpg', '2025-08-22 03:39:44', '2025-08-22 03:39:44', 25),
(22, 'kamil', 'ali', 'kamil123@gmail.com', '0376527848', 'Male', 'pakistan', '', '0', '', '$2y$10$WSp3GXxyww.4b5/3eIQu8.zmLDb9/Zs8F6DmHAphgH3r6gupqijrO', '1755834221_IMG_20230426_170739_6.jpg', '2025-08-22 03:43:41', '2025-08-22 03:43:41', 28),
(23, 'ali', 'muhmmad', 'ali12345@gmail.com', '03445948985', 'Male', 'pakistan', '', '0', '', '$2y$10$ZbeMH8WUQPCQkXP3POq3dONpLZ9/dBJ9.PJuqXGRb0Qz8K3WW7pEm', '1755834368_IMG_20230427_212626_3.jpg', '2025-08-22 03:46:09', '2025-08-22 03:46:09', 28),
(24, 'Bilall', 'ahmed', 'bilal123@gmail.com', '035765784678', 'Male', 'pakistan', 'gilgit', '0', '', '$2y$10$fce.t3OQUMnAd5vbDo.vcO1NHeFlhK1QKMJQY.Zt2GYglc0/5U5dS', '1755846198_c1.jpg', '2025-08-22 04:39:56', '2025-08-22 07:04:59', 28),
(25, 'Danish', 'ali', 'danish123@gmail.com', '03568938973', 'Male', 'pakistan', '', '0', '', '$2y$10$FzPGEGJAXoS6S1XqB94amOz1CJzWkmBy7KPkqJV3Skf0PQ0A6mH1G', '1755837696_c2.jpg', '2025-08-22 04:41:36', '2025-08-22 04:41:36', 28),
(26, 'Ehsan', 'ali', 'Ehsan123@gmail.com', '037847632987', 'Male', 'pakistan', '', '0', '', '$2y$10$0QUtYnmdKpe8j83anKSRCO.u7sMK4TJN4LZMWkA/XSmWhWamwdp/a', '1755837757_c3.jpg', '2025-08-22 04:42:37', '2025-08-22 04:42:37', 28),
(27, 'Fahad', 'mustafa', 'Fahad123@gmail.com', '036526586782', 'Male', 'pakistan', '', '0', '', '$2y$10$Hhf9P7Mm0sUiWC5J7NaM3uswMeBJ8VJ878w960nVAecHZuHwshX5O', '1755837824_c4.jpg', '2025-08-22 04:43:44', '2025-08-22 04:43:44', 28),
(28, 'Ghaffar', 'hussain', 'Ghaffar123@gmail.com', '0387347893', 'Male', 'pakistan', '', '0', '', '$2y$10$Nmn1wD/h704XNhOWMGFmaeo6v35TcFztNJcIcDhIlkI70voyi6wjq', '1755837888_c5.jpg', '2025-08-22 04:44:48', '2025-08-22 04:44:48', 28),
(29, 'Haider', 'karim', 'Haider123@gmail.com', '0356273576', 'Male', 'pakistan', '', '0', '', '$2y$10$bIEw7NMnezfUIC.bxYRFwuvtOO944FSvzKR2DntrIzomatsW7bzXW', '1755837944_c6.jpg', '2025-08-22 04:45:45', '2025-08-22 04:45:45', 28),
(30, 'Ibrahim', 'mosa', 'Ibrahim123@gmail.com', '0378297424', 'Male', 'pakistan', '', '0', '', '$2y$10$dO53pao7KsPdgkygEyQsB.tokkgN4Nf6JCUDlGqHHLeZBnmpPIg/e', '1755838007_c7.jpg', '2025-08-22 04:46:47', '2025-08-22 04:46:47', 28),
(31, 'Javed', 'ali', 'Javed123@gmail.com', '0389789334', 'Male', 'pakistan', '', '0', '', '$2y$10$JnVKSEdVtKdvkzdMpK0qZOzYlzSTQF/jFcMgFctoVtP3fng5kJcGS', '1755838071_c8.jpg', '2025-08-22 04:47:51', '2025-08-22 04:47:51', 28),
(32, 'ahtisham', 'ali', 'ahtisham123@gmail.com', '03657163783', 'Male', 'pakistan', '', '0', '', '$2y$10$BacOWQTJm.O14PkWsxouoOfvS4tIjIvAhxPKcsxJVtrcZpE2zBK/m', '1755838139_c9.jpg', '2025-08-22 04:48:59', '2025-08-22 04:48:59', 28),
(33, 'sardar', 'khan', 'sardar123@gmail.com', '034676233424', 'Male', 'pakistan', '', '0', '', '$2y$10$CrNLobv2U0NGEN0SyEJxnujb4VOxBo2sWn4ZmR63ZWT3IZXV90PNu', '1755838196_c10.jpg', '2025-08-22 04:49:56', '2025-08-22 04:49:56', 28),
(34, 'Aisha', 'bano', 'Aisha123@gmail.com', '0376562532787', 'Female', 'pakistan', '', '0', '', '$2y$10$Pj6BSDVeD5kc25dVy3oqCuGHmHxO8V3KoCLQ6pXbYq/ATyOggCnrW', '1755838360_g1.jpg', '2025-08-22 04:52:40', '2025-08-22 04:52:40', 28),
(35, 'Amna', 'khan', 'Amna123@gmail.com', '0365761576123', 'Female', 'pakistan', '', '0', '', '$2y$10$7EFuIwXRzHRsCqxpa1huXugT/rJ42f8/e5Kj4t24.cd50L7MKyNQu', '1755838417_g2.jpg', '2025-08-22 04:53:37', '2025-08-22 04:53:37', 28),
(36, 'Anum', 'mansur', 'Anum123@gmail.com', '035614561361', 'Female', 'pakistan', '', '0', '', '$2y$10$bbnF4aFxzZotT6cVVzJC.Oq0B7MZiD3tNgdqSB0E819ConrvBO5u6', '1755838472_g4.jpg', '2025-08-22 04:54:32', '2025-08-22 04:54:32', 28),
(37, 'Alishba', 'khan', 'Alishba123@gmail.com', '036786783', 'Female', 'pakistan', '', '0', '', '$2y$10$UiGAQHihnKn9SjuLy87DBeaJsssdtunlezwMkSH.7rdn4xvLR/oey', '1755838536_g6.jpg', '2025-08-22 04:55:36', '2025-08-22 04:55:36', 28);

-- --------------------------------------------------------

--
-- Table structure for table `students_marks`
--

CREATE TABLE `students_marks` (
  `students_marks_id` int(11) NOT NULL,
  `students_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `assignments_total_marks` int(11) NOT NULL,
  `assignments_total_obtained_marks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(1, 10, 5, 2, 'A'),
(2, 10, 7, 13, 'A'),
(3, 10, 7, 14, 'A'),
(4, 10, 7, 15, 'B'),
(5, 10, 7, 16, 'D'),
(6, 10, 7, 17, 'C'),
(7, 10, 8, 18, 'B'),
(8, 10, 8, 19, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `student_assignment_summary`
--

CREATE TABLE `student_assignment_summary` (
  `summary_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `total_obtained_marks` int(11) DEFAULT 0,
  `total_marks` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quiz_total` int(11) DEFAULT 0,
  `quiz_obtained` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_assignment_summary`
--

INSERT INTO `student_assignment_summary` (`summary_id`, `student_id`, `course_id`, `class_id`, `teacher_id`, `total_obtained_marks`, `total_marks`, `created_at`, `updated_at`, `quiz_total`, `quiz_obtained`) VALUES
(72, 10, 32, 27, 10, 20, 20, '2025-08-17 13:11:08', '2025-08-21 07:08:13', 26, 17),
(73, 12, 32, 27, 10, 10, 20, '2025-08-17 13:11:08', '2025-08-21 07:08:13', 26, 4),
(74, 13, 32, 27, 10, 0, 20, '2025-08-17 13:11:08', '2025-08-21 07:08:13', 26, 0),
(75, 14, 32, 27, 10, 0, 20, '2025-08-17 13:11:08', '2025-08-21 07:08:13', 26, 0),
(102, 10, 44, 27, 11, 25, 30, '2025-08-20 09:27:34', '2025-08-20 09:27:34', 10, 4),
(103, 12, 44, 27, 11, 0, 30, '2025-08-20 09:27:34', '2025-08-20 09:27:34', 10, 0),
(104, 13, 44, 27, 11, 0, 30, '2025-08-20 09:27:34', '2025-08-20 09:27:34', 10, 0),
(105, 14, 44, 27, 11, 0, 30, '2025-08-20 09:27:34', '2025-08-20 09:27:34', 10, 0),
(109, 10, 27, 27, 8, 25, 30, '2025-08-20 09:50:58', '2025-08-20 09:50:58', 0, 0),
(111, 11, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(112, 20, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(113, 22, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(114, 23, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(115, 24, 32, 28, 10, 38, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(116, 25, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(117, 26, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(118, 27, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(119, 28, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(120, 29, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(121, 30, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(122, 31, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(123, 32, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(124, 33, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(125, 34, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(126, 35, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(127, 36, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0),
(128, 37, 32, 28, 10, 0, 40, '2025-08-22 05:02:06', '2025-08-22 05:02:15', 0, 0);

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
(6, 5, 7, 37),
(7, 6, 7, 37),
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
(31, 18, 24, 32),
(32, 13, 27, 32),
(33, 19, 23, 32),
(34, 17, 23, 32),
(35, 20, 28, 45),
(36, 14, 27, 32),
(40, 13, 27, 44),
(41, 12, 27, 44),
(42, 14, 27, 44),
(43, 13, 27, 27),
(44, 12, 27, 27),
(45, 14, 27, 27),
(46, 5, 7, 47),
(47, 6, 7, 47),
(49, 5, 7, 47),
(50, 6, 7, 47),
(51, 5, 7, 27),
(52, 6, 7, 27),
(54, 23, 28, 32),
(55, 22, 28, 32),
(56, 32, 28, 32),
(57, 34, 28, 32),
(58, 37, 28, 32),
(59, 35, 28, 32),
(60, 36, 28, 32),
(61, 24, 28, 32),
(62, 26, 28, 32),
(63, 27, 28, 32),
(64, 33, 28, 32),
(65, 29, 28, 32),
(66, 30, 28, 32),
(67, 31, 28, 32),
(68, 28, 28, 32),
(69, 25, 28, 32);

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
(10, 'Anni', 'aziz', 'ani123@gmail.com', '03123456789', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'chemistry', 'BS maths', '1755849532_g8.jpg', '$2y$10$0gpFuRwsrtvyp.gFfj9UcuLWP.1fX9TAyjz8Wd6d5WfQDZK9F//J6', '2025-07-26 08:55:59', '2025-08-22 07:58:52'),
(11, 'Nama', 'rani', 'nama123@gmail.com', '03123456789', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Urdu', 'MA Urdu', 'uploads/teacher_profiles/MYXJ_20211214082910797_save.jpg', '$2y$10$xiIpPpapl4HyaIYQ.AzYq.3D2K9yt5vEX.wSDF86vovavjDCqiawS', '2025-07-26 08:56:44', '2025-07-26 09:35:49'),
(12, 'hussun', 'bano', 'hussun123@gmail.com', '034567657625', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Computer sciences', 'BS computerscience', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$It7S6jor18RatYrENJ.r5ehcUxdI6KRaExgnuEaF28ReowO33CJgG', '2025-07-26 12:31:46', '2025-07-26 12:31:46'),
(13, 'Ejaz', 'Karim', 'ejaz123@gmail.com', '034567657335', 'Male', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Pakistan studies', 'MA Pakistan Studies', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$VkJKsFf.RAlixDv/WJ24NeaInq/9W7zcXLeUYm.ogNgjgJo2/gfOO', '2025-07-26 12:33:15', '2025-07-26 12:33:15'),
(14, 'Salma', 'Amir', 'salma123@gmail.com', '034567623335', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Pakistan studies', 'MA URDU/Islamiyat', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$iirZWyqzDJz2IAMevg9vLOx3NsIQ0OlJLHbazb8mVmNuPWUWLS6uy', '2025-07-26 12:34:08', '2025-07-26 12:34:08'),
(15, 'Rizwana', 'khan', 'Rizwana123@gmail.com', '034567623335', 'Female', 'pakistan', 'Gilgit_Baltistan', 'Hunza', 'Pakistan studies', 'MA SST', 'uploads/teacher_profiles/Capture.PNG', '$2y$10$QJR/LbwYLvRUhqT.M2.RBuxtJEKH9V2Joo11a6Yjk858eDERs/k4m', '2025-07-26 12:34:50', '2025-07-26 12:34:50'),
(17, 'mr', 'bean', 'bean123@gmail.com', '1234568905', 'Male', 'america', 'newyok', 'kjaafa', 'english', 'MA', '1755756128_hunzai.jpg', '$2y$10$VrpVItTeGXxICGO5kN0qC.JzdPHbzoXvOVoQK/tuJVfjE8m0Pixr2', '2025-08-21 06:02:08', '2025-08-21 06:21:45'),
(18, 'mr', 'nolan', 'nolan123@gmail.com', '6713183716378', 'Male', 'england', 'posps', 'polam', 'french', 'MSC', '1755757154_MYXJ_20211214082910797_save.jpg', '$2y$10$ghWeHZKFwwaX2Hh8hc4KZeIZFq4sXOd8KldWER/qDL74qEDA1PzNq', '2025-08-21 06:19:14', '2025-08-21 06:19:14');

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
(37, 8, 27, 28, 'uploads/course_outlines/689394e046361.docx'),
(39, 10, 32, 27, 'uploads/course_outlines/6893670356e2e.pdf'),
(40, 10, 32, 28, 'uploads/course_outlines/6893998bd2366.docx'),
(41, 10, 32, 24, NULL),
(42, 10, 32, 23, NULL),
(44, 11, 44, 23, NULL),
(45, 11, 44, 24, NULL),
(46, 11, 44, 26, NULL),
(47, 11, 44, 27, NULL),
(48, 11, 44, 28, NULL),
(49, 11, 44, 29, NULL),
(50, 11, 44, 30, NULL),
(52, 13, 45, 28, NULL),
(54, 15, 45, 28, NULL),
(55, 12, 37, 28, NULL),
(58, 8, 27, 7, NULL);

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
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`);

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
  ADD UNIQUE KEY `uniq_result` (`student_id`,`course_id`,`class_id`),
  ADD KEY `fk_result_class` (`class_id`),
  ADD KEY `fk_result_course` (`course_id`),
  ADD KEY `fk_result_teacher` (`teacher_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`students_id`),
  ADD UNIQUE KEY `students_email` (`students_email`),
  ADD KEY `fk_class` (`class_id`);

--
-- Indexes for table `students_marks`
--
ALTER TABLE `students_marks`
  ADD PRIMARY KEY (`students_marks_id`),
  ADD KEY `student_key_1` (`students_id`),
  ADD KEY `teacher_id_1` (`teacher_id`),
  ADD KEY `class_key_1` (`class_id`),
  ADD KEY `course_key_1` (`course_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `student_assignment_summary`
--
ALTER TABLE `student_assignment_summary`
  ADD PRIMARY KEY (`summary_id`),
  ADD UNIQUE KEY `uniq_student_course_class` (`student_id`,`course_id`,`class_id`),
  ADD KEY `fk_sas_course` (`course_id`),
  ADD KEY `fk_sas_class` (`class_id`),
  ADD KEY `fk_sas_teacher` (`teacher_id`);

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
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `assignments_data`
--
ALTER TABLE `assignments_data`
  MODIFY `assignments_data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `students_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `students_marks`
--
ALTER TABLE `students_marks`
  MODIFY `students_marks_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_assignment_summary`
--
ALTER TABLE `student_assignment_summary`
  MODIFY `summary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `student_course_assign`
--
ALTER TABLE `student_course_assign`
  MODIFY `student_course_assign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `teacher_assign`
--
ALTER TABLE `teacher_assign`
  MODIFY `teacher_assign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
  ADD CONSTRAINT `fk_result_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `fk_result_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `fk_result_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`),
  ADD CONSTRAINT `fk_result_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  ADD CONSTRAINT `student_key` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_key` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students_marks`
--
ALTER TABLE `students_marks`
  ADD CONSTRAINT `class_key_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_key_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_key_1` FOREIGN KEY (`students_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_id_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_assignment_summary`
--
ALTER TABLE `student_assignment_summary`
  ADD CONSTRAINT `fk_sas_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sas_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sas_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`students_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sas_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
