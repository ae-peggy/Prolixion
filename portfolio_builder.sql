-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 17, 2024 at 09:49 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portfolio_builder`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `achievement_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `date_received` date DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`achievement_id`, `user_id`, `title`, `date_received`, `description`, `created_at`, `updated_at`) VALUES
(11, 1, 'Dean\'s List Honors', '2024-06-02', 'Award given to students with a GPA of 3.5 and above', '2024-12-14 21:14:38', '2024-12-14 21:14:38'),
(14, 5, 'Energy Management Certification (EMC)', '2023-01-01', 'UNIDO', '2024-12-16 17:14:57', '2024-12-16 17:14:57'),
(15, 5, 'Dean’s List Honors', '2018-01-01', 'Ashesi University- Awarded to students with GPA 3.5 and above', '2024-12-16 17:16:53', '2024-12-16 17:16:53'),
(16, 5, 'Dean’s List Honors, ', '2019-01-01', 'Ashesi University', '2024-12-16 17:17:18', '2024-12-16 17:17:18');

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int NOT NULL,
  `user_id` int NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `user_id`, `activity_name`, `role`, `start_date`, `end_date`, `description`, `created_at`, `updated_at`) VALUES
(5, 1, 'Ashesi Volleyball', 'Libro', '2023-01-09', '2023-09-14', 'Team player for team Chaos', '2024-12-14 21:25:40', '2024-12-14 21:25:40'),
(6, 5, 'Ashesi University, Ashesi Premier League', 'Team captain', '2023-09-01', '2024-12-16', '•	Played 5 seasons for Red Army Football Club as club captain. ', '2024-12-16 17:23:37', '2024-12-16 17:23:37'),
(7, 5, 'Kingdom Vessels Band', 'Band director & Bass player', '2017-05-01', '2024-12-16', '•	Served as the band’s director for the past year performing tasks such as scheduling rehearsals and facilitating effective communication among band members during live sessions.\r\n•	Served as the band’s bass guitar player since 2017.\r\n', '2024-12-16 17:24:40', '2024-12-16 17:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `education_entries`
--

CREATE TABLE `education_entries` (
  `education_id` int NOT NULL,
  `user_id` int NOT NULL,
  `institution` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `education_entries`
--

INSERT INTO `education_entries` (`education_id`, `user_id`, `institution`, `degree`, `start_date`, `end_date`, `description`, `created_at`, `updated_at`) VALUES
(11, 1, 'Ashesi University', 'BSc in Management Information Systems', '2023-01-01', '2026-06-11', 'I am a third-year student.', '2024-12-14 21:12:15', '2024-12-14 21:12:15'),
(12, 1, 'Wesley Girls High School ', 'WASSCE Certificate', '2019-11-21', '2022-09-22', 'Graduated with flying colors', '2024-12-14 21:13:38', '2024-12-14 21:13:38'),
(13, 5, 'ETH Zurich', 'MSc in Mechatronics', '2022-01-03', '2024-12-20', 'Full time student- Zurich, Switzerland', '2024-12-16 17:11:04', '2024-12-16 17:11:04'),
(14, 5, 'Ashesi University', 'BSc Electrical and Electronic Engineering', '2016-09-01', '2020-07-08', 'Full-time student- Berekuso', '2024-12-16 17:13:44', '2024-12-16 17:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `experience_entries`
--

CREATE TABLE `experience_entries` (
  `experience_id` int NOT NULL,
  `user_id` int NOT NULL,
  `company` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `experience_entries`
--

INSERT INTO `experience_entries` (`experience_id`, `user_id`, `company`, `position`, `start_date`, `end_date`, `description`, `created_at`, `updated_at`) VALUES
(6, 1, 'Fafali Organization', 'Volunteer', '2024-10-07', '2024-10-01', 'Taught underserved children in Anumle Python and MS Word', '2024-12-14 21:16:20', '2024-12-14 21:16:20'),
(7, 5, 'Praise Export Services Ltd. ', 'Management Trainee                             ', '2021-08-11', '2022-01-12', '•	Presented technical progress reports as well as financial expenditure reports for the engineering department during management meetings and made recommendations such as changes in work procedure or more efficient equipment that would lead to improved performance and overall staff wellbeing in the department.\r\n•	Presented a cost breakdown and justification to management for every equipment procured, component purchased, or service rendered on behalf of the engineering department which led to a decrease in expenditure on equipment by the company compared to previous years.  \r\n', '2024-12-16 17:18:18', '2024-12-16 17:18:18'),
(8, 5, ' Eviosys Packaging West Africa (formerly Mivisa) ', 'Electrical Engineering Intern                         ', '2018-07-11', '2018-08-17', '•	Resolved faults causing abrupt cessation of production through the identification of faulty sensors, actuators, and switches. 	 	     \r\n•	Installation of limit switches and other protective devices leading to safer and improved working environments for humans interacting with the machinery. \r\n•	Improved expertise in best practices health, safety and environment needed for industry.\r\n', '2024-12-16 17:19:28', '2024-12-16 17:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reviewer_id` int DEFAULT NULL,
  `feedback_text` text NOT NULL,
  `rating` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `reviewer_id`, `feedback_text`, `rating`, `created_at`) VALUES
(9, 5, 5, 'This is a very impressive resume, Godwin. Well done!!', 5, '2024-12-16 17:33:52'),
(10, 5, 5, 'You are truly everyone\'s boss🤛🏽', 4, '2024-12-16 17:34:34'),
(11, 1, 1, 'Very impressive porfolio, Peggy👏🏽👏🏽', 5, '2024-12-17 09:33:05'),
(12, 1, 1, 'Very impressive porfolio, Peggy👏🏽👏🏽', 5, '2024-12-17 09:34:22'),
(13, 1, 1, 'Very impressive porfolio, Peggy👏🏽👏🏽', 5, '2024-12-17 09:34:43');

-- --------------------------------------------------------

--
-- Table structure for table `generated_resumes`
--

CREATE TABLE `generated_resumes` (
  `resume_id` int NOT NULL,
  `user_id` int NOT NULL,
  `resume_name` varchar(100) NOT NULL,
  `resume_data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `generated_resumes`
--

INSERT INTO `generated_resumes` (`resume_id`, `user_id`, `resume_name`, `resume_data`, `created_at`, `updated_at`) VALUES
(17, 1, 'Peggy CV', '{\"skills\": [{\"user_id\": 1, \"skill_id\": 15, \"created_at\": \"2024-12-14 21:25:54\", \"skill_name\": \"Communication\", \"updated_at\": \"2024-12-14 21:25:54\"}, {\"user_id\": 1, \"skill_id\": 16, \"created_at\": \"2024-12-14 21:26:00\", \"skill_name\": \"MySQL\", \"updated_at\": \"2024-12-14 21:26:00\"}, {\"user_id\": 1, \"skill_id\": 17, \"created_at\": \"2024-12-14 21:26:04\", \"skill_name\": \"Python\", \"updated_at\": \"2024-12-14 21:26:04\"}, {\"user_id\": 1, \"skill_id\": 18, \"created_at\": \"2024-12-14 21:26:12\", \"skill_name\": \"Customer Service\", \"updated_at\": \"2024-12-14 21:26:12\"}, {\"user_id\": 1, \"skill_id\": 19, \"created_at\": \"2024-12-14 21:26:23\", \"skill_name\": \"Attention to Detail\", \"updated_at\": \"2024-12-14 21:26:23\"}, {\"user_id\": 1, \"skill_id\": 20, \"created_at\": \"2024-12-14 21:26:33\", \"skill_name\": \"Sales\", \"updated_at\": \"2024-12-14 21:26:33\"}, {\"user_id\": 1, \"skill_id\": 21, \"created_at\": \"2024-12-14 21:26:38\", \"skill_name\": \"Accounting\", \"updated_at\": \"2024-12-14 21:26:38\"}, {\"user_id\": 1, \"skill_id\": 22, \"created_at\": \"2024-12-14 21:26:50\", \"skill_name\": \"Teamwork\", \"updated_at\": \"2024-12-14 21:26:50\"}, {\"user_id\": 1, \"skill_id\": 26, \"created_at\": \"2024-12-14 22:06:46\", \"skill_name\": \"Java\", \"updated_at\": \"2024-12-14 22:06:46\"}, {\"user_id\": 1, \"skill_id\": 28, \"created_at\": \"2024-12-14 22:07:33\", \"skill_name\": \"R\", \"updated_at\": \"2024-12-14 22:07:33\"}, {\"user_id\": 1, \"skill_id\": 32, \"created_at\": \"2024-12-14 22:10:01\", \"skill_name\": \".\", \"updated_at\": \"2024-12-14 22:10:01\"}], \"projects\": [{\"role\": \"Project Lead\", \"title\": \"AI Sports Prediction App\", \"user_id\": 1, \"end_date\": \"2024-06-12\", \"created_at\": \"2024-12-14 21:17:43\", \"project_id\": 7, \"start_date\": \"2024-05-01\", \"updated_at\": \"2024-12-14 21:17:43\", \"description\": \"Created an AI model that predicts player ratings based on skills, lifestyle habits etc.\"}], \"education\": [{\"degree\": \"BSc in Management Information Systems\", \"user_id\": 1, \"end_date\": \"2026-06-11\", \"created_at\": \"2024-12-14 21:12:15\", \"start_date\": \"2023-01-01\", \"updated_at\": \"2024-12-14 21:12:15\", \"description\": \"I am a third-year student.\", \"institution\": \"Ashesi University\", \"education_id\": 11}, {\"degree\": \"WASSCE Certificate\", \"user_id\": 1, \"end_date\": \"2022-09-22\", \"created_at\": \"2024-12-14 21:13:38\", \"start_date\": \"2019-11-21\", \"updated_at\": \"2024-12-14 21:13:38\", \"description\": \"Graduated with flying colors\", \"institution\": \"Wesley Girls High School \", \"education_id\": 12}], \"activities\": [{\"role\": \"Libro\", \"user_id\": 1, \"end_date\": \"2023-09-14\", \"created_at\": \"2024-12-14 21:25:40\", \"start_date\": \"2023-01-09\", \"updated_at\": \"2024-12-14 21:25:40\", \"activity_id\": 5, \"description\": \"Team player for team Chaos\", \"activity_name\": \"Ashesi Volleyball\"}], \"experience\": [{\"company\": \"Fafali Organization\", \"user_id\": 1, \"end_date\": \"2024-10-01\", \"position\": \"Volunteer\", \"created_at\": \"2024-12-14 21:16:20\", \"start_date\": \"2024-10-07\", \"updated_at\": \"2024-12-14 21:16:20\", \"description\": \"Taught underserved children in Anumle Python and MS Word\", \"experience_id\": 6}], \"achievements\": [{\"title\": \"Best Student\", \"user_id\": 1, \"created_at\": \"2024-12-14 22:05:47\", \"updated_at\": \"2024-12-14 22:05:47\", \"description\": \"Awarded in high school\", \"date_received\": \"2024-12-03\", \"achievement_id\": 12}, {\"title\": \"Best Sister Award\", \"user_id\": 1, \"created_at\": \"2024-12-14 22:11:02\", \"updated_at\": \"2024-12-14 22:11:02\", \"description\": \"Awarded to Tracy Edem\", \"date_received\": \"2024-09-02\", \"achievement_id\": 13}, {\"title\": \"Dean\'s List Honors\", \"user_id\": 1, \"created_at\": \"2024-12-14 21:14:38\", \"updated_at\": \"2024-12-14 21:14:38\", \"description\": \"Award given to students with a GPA of 3.5 and above\", \"date_received\": \"2024-06-02\", \"achievement_id\": 11}]}', '2024-12-15 21:28:12', '2024-12-15 21:28:12'),
(18, 5, 'Godwin-CV', '{\"skills\": [{\"user_id\": 5, \"skill_id\": 34, \"created_at\": \"2024-12-16 17:25:23\", \"skill_name\": \",MATLAB\", \"updated_at\": \"2024-12-16 17:25:23\"}, {\"user_id\": 5, \"skill_id\": 45, \"created_at\": \"2024-12-16 17:27:14\", \"skill_name\": \"Basketball\", \"updated_at\": \"2024-12-16 17:27:14\"}, {\"user_id\": 5, \"skill_id\": 40, \"created_at\": \"2024-12-16 17:26:23\", \"skill_name\": \"Eagle\", \"updated_at\": \"2024-12-16 17:26:23\"}, {\"user_id\": 5, \"skill_id\": 37, \"created_at\": \"2024-12-16 17:26:01\", \"skill_name\": \"Excel\", \"updated_at\": \"2024-12-16 17:26:01\"}, {\"user_id\": 5, \"skill_id\": 35, \"created_at\": \"2024-12-16 17:25:38\", \"skill_name\": \"LT Spice\", \"updated_at\": \"2024-12-16 17:25:38\"}, {\"user_id\": 5, \"skill_id\": 36, \"created_at\": \"2024-12-16 17:25:46\", \"skill_name\": \"MATLAB Simulink\", \"updated_at\": \"2024-12-16 17:25:46\"}, {\"user_id\": 5, \"skill_id\": 41, \"created_at\": \"2024-12-16 17:26:40\", \"skill_name\": \"Microsoft Word\", \"updated_at\": \"2024-12-16 17:26:40\"}, {\"user_id\": 5, \"skill_id\": 43, \"created_at\": \"2024-12-16 17:26:55\", \"skill_name\": \"Power Point\", \"updated_at\": \"2024-12-16 17:26:55\"}, {\"user_id\": 5, \"skill_id\": 39, \"created_at\": \"2024-12-16 17:26:21\", \"skill_name\": \"Proteus\", \"updated_at\": \"2024-12-16 17:26:21\"}, {\"user_id\": 5, \"skill_id\": 33, \"created_at\": \"2024-12-16 17:25:16\", \"skill_name\": \"Python\", \"updated_at\": \"2024-12-16 17:25:16\"}, {\"user_id\": 5, \"skill_id\": 38, \"created_at\": \"2024-12-16 17:26:10\", \"skill_name\": \"SolidWorks\", \"updated_at\": \"2024-12-16 17:26:10\"}, {\"user_id\": 5, \"skill_id\": 46, \"created_at\": \"2024-12-16 17:27:20\", \"skill_name\": \"Swimming\", \"updated_at\": \"2024-12-16 17:27:20\"}, {\"user_id\": 5, \"skill_id\": 47, \"created_at\": \"2024-12-16 17:27:45\", \"skill_name\": \"Table Tennis\", \"updated_at\": \"2024-12-16 17:27:45\"}, {\"user_id\": 5, \"skill_id\": 44, \"created_at\": \"2024-12-16 17:27:07\", \"skill_name\": \"Volleyball\", \"updated_at\": \"2024-12-16 17:27:07\"}], \"projects\": [{\"role\": \"Lead\", \"title\": \"LQR-based Mobile Robot\", \"user_id\": 5, \"end_date\": \"2023-09-27\", \"created_at\": \"2024-12-16 17:22:38\", \"project_id\": 10, \"start_date\": \"2023-03-29\", \"updated_at\": \"2024-12-16 17:22:38\", \"description\": \"•\\tImplemented the Linear Quadratic Regulator optimal control algorithm to enable a four wheeled robot to move to any given distance based on the input from a user.\"}, {\"role\": \"Team Member \", \"title\": \"MP3 Algorithm Project\", \"user_id\": 5, \"end_date\": \"2022-03-11\", \"created_at\": \"2024-12-16 17:20:51\", \"project_id\": 8, \"start_date\": \"2022-01-13\", \"updated_at\": \"2024-12-16 17:20:51\", \"description\": \"•\\tCollaborated with a colleague to develop an algorithm that replicates MP3 audio compression to reduce the file size of an audio recording from hundreds of megabytes in magnitude to only a single digit.\"}, {\"role\": \"Team Lead\", \"title\": \"Monte Carlo Simulations for Reliability and Risk \", \"user_id\": 5, \"end_date\": \"2022-05-12\", \"created_at\": \"2024-12-16 17:21:39\", \"project_id\": 9, \"start_date\": \"2022-01-12\", \"updated_at\": \"2024-12-16 17:21:39\", \"description\": \"•\\tCo-led a team to implement Monte Carlo simulations to determine the likely time of failure of a generator configuration.\"}], \"education\": [{\"degree\": \"MSc in Mechatronics\", \"user_id\": 5, \"end_date\": \"2024-12-20\", \"created_at\": \"2024-12-16 17:11:04\", \"start_date\": \"2022-01-03\", \"updated_at\": \"2024-12-16 17:11:04\", \"description\": \"Full time student- Zurich, Switzerland\", \"institution\": \"ETH Zurich\", \"education_id\": 13}, {\"degree\": \"BSc Electrical and Electronic Engineering\", \"user_id\": 5, \"end_date\": \"2020-07-08\", \"created_at\": \"2024-12-16 17:13:44\", \"start_date\": \"2016-09-01\", \"updated_at\": \"2024-12-16 17:13:44\", \"description\": \"Full-time student- Berekuso\", \"institution\": \"Ashesi University\", \"education_id\": 14}], \"activities\": [{\"role\": \"Team captain\", \"user_id\": 5, \"end_date\": \"2024-12-16\", \"created_at\": \"2024-12-16 17:23:37\", \"start_date\": \"2023-09-01\", \"updated_at\": \"2024-12-16 17:23:37\", \"activity_id\": 6, \"description\": \"•\\tPlayed 5 seasons for Red Army Football Club as club captain. \", \"activity_name\": \"Ashesi University, Ashesi Premier League\"}, {\"role\": \"Band director & Bass player\", \"user_id\": 5, \"end_date\": \"2024-12-16\", \"created_at\": \"2024-12-16 17:24:40\", \"start_date\": \"2017-05-01\", \"updated_at\": \"2024-12-16 17:24:40\", \"activity_id\": 7, \"description\": \"•\\tServed as the band’s director for the past year performing tasks such as scheduling rehearsals and facilitating effective communication among band members during live sessions.\\r\\n•\\tServed as the band’s bass guitar player since 2017.\\r\\n\", \"activity_name\": \"Kingdom Vessels Band\"}], \"experience\": [{\"company\": \"Praise Export Services Ltd. \", \"user_id\": 5, \"end_date\": \"2022-01-12\", \"position\": \"Management Trainee                             \", \"created_at\": \"2024-12-16 17:18:18\", \"start_date\": \"2021-08-11\", \"updated_at\": \"2024-12-16 17:18:18\", \"description\": \"•\\tPresented technical progress reports as well as financial expenditure reports for the engineering department during management meetings and made recommendations such as changes in work procedure or more efficient equipment that would lead to improved performance and overall staff wellbeing in the department.\\r\\n•\\tPresented a cost breakdown and justification to management for every equipment procured, component purchased, or service rendered on behalf of the engineering department which led to a decrease in expenditure on equipment by the company compared to previous years.  \\r\\n\", \"experience_id\": 7}, {\"company\": \" Eviosys Packaging West Africa (formerly Mivisa) \", \"user_id\": 5, \"end_date\": \"2018-08-17\", \"position\": \"Electrical Engineering Intern                         \", \"created_at\": \"2024-12-16 17:19:28\", \"start_date\": \"2018-07-11\", \"updated_at\": \"2024-12-16 17:19:28\", \"description\": \"•\\tResolved faults causing abrupt cessation of production through the identification of faulty sensors, actuators, and switches. \\t \\t     \\r\\n•\\tInstallation of limit switches and other protective devices leading to safer and improved working environments for humans interacting with the machinery. \\r\\n•\\tImproved expertise in best practices health, safety and environment needed for industry.\\r\\n\", \"experience_id\": 8}], \"achievements\": [{\"title\": \"Energy Management Certification (EMC)\", \"user_id\": 5, \"created_at\": \"2024-12-16 17:14:57\", \"updated_at\": \"2024-12-16 17:14:57\", \"description\": \"UNIDO\", \"date_received\": \"2023-01-01\", \"achievement_id\": 14}, {\"title\": \"Dean’s List Honors, \", \"user_id\": 5, \"created_at\": \"2024-12-16 17:17:18\", \"updated_at\": \"2024-12-16 17:17:18\", \"description\": \"Ashesi University\", \"date_received\": \"2019-01-01\", \"achievement_id\": 16}, {\"title\": \"Dean’s List Honors\", \"user_id\": 5, \"created_at\": \"2024-12-16 17:16:53\", \"updated_at\": \"2024-12-16 17:16:53\", \"description\": \"Ashesi University- Awarded to students with GPA 3.5 and above\", \"date_received\": \"2018-01-01\", \"achievement_id\": 15}]}', '2024-12-16 17:28:27', '2024-12-16 17:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `description` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `user_id`, `title`, `role`, `description`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(7, 1, 'AI Sports Prediction App', 'Project Lead', 'Created an AI model that predicts player ratings based on skills, lifestyle habits etc.', '2024-05-01', '2024-06-12', '2024-12-14 21:17:43', '2024-12-14 21:17:43'),
(8, 5, 'MP3 Algorithm Project', 'Team Member ', '•	Collaborated with a colleague to develop an algorithm that replicates MP3 audio compression to reduce the file size of an audio recording from hundreds of megabytes in magnitude to only a single digit.', '2022-01-13', '2022-03-11', '2024-12-16 17:20:51', '2024-12-16 17:20:51'),
(9, 5, 'Monte Carlo Simulations for Reliability and Risk ', 'Team Lead', '•	Co-led a team to implement Monte Carlo simulations to determine the likely time of failure of a generator configuration.', '2022-01-12', '2022-05-12', '2024-12-16 17:21:39', '2024-12-16 17:21:39'),
(10, 5, 'LQR-based Mobile Robot', 'Lead', '•	Implemented the Linear Quadratic Regulator optimal control algorithm to enable a four wheeled robot to move to any given distance based on the input from a user.', '2023-03-29', '2023-09-27', '2024-12-16 17:22:38', '2024-12-16 17:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int NOT NULL,
  `user_id` int NOT NULL,
  `skill_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `user_id`, `skill_name`, `created_at`, `updated_at`) VALUES
(15, 1, 'Communication', '2024-12-14 21:25:54', '2024-12-14 21:25:54'),
(16, 1, 'MySQL', '2024-12-14 21:26:00', '2024-12-14 21:26:00'),
(17, 1, 'Python', '2024-12-14 21:26:04', '2024-12-14 21:26:04'),
(18, 1, 'Customer Service', '2024-12-14 21:26:12', '2024-12-14 21:26:12'),
(19, 1, 'Attention to Detail', '2024-12-14 21:26:23', '2024-12-14 21:26:23'),
(20, 1, 'Sales', '2024-12-14 21:26:33', '2024-12-14 21:26:33'),
(21, 1, 'Accounting', '2024-12-14 21:26:38', '2024-12-14 21:26:38'),
(22, 1, 'Teamwork', '2024-12-14 21:26:50', '2024-12-14 21:26:50'),
(28, 1, 'R', '2024-12-14 22:07:33', '2024-12-14 22:07:33'),
(33, 5, 'Python', '2024-12-16 17:25:16', '2024-12-16 17:25:16'),
(34, 5, ',MATLAB', '2024-12-16 17:25:23', '2024-12-16 17:25:23'),
(35, 5, 'LT Spice', '2024-12-16 17:25:38', '2024-12-16 17:25:38'),
(36, 5, 'MATLAB Simulink', '2024-12-16 17:25:46', '2024-12-16 17:25:46'),
(37, 5, 'Excel', '2024-12-16 17:26:01', '2024-12-16 17:26:01'),
(38, 5, 'SolidWorks', '2024-12-16 17:26:10', '2024-12-16 17:26:10'),
(39, 5, 'Proteus', '2024-12-16 17:26:21', '2024-12-16 17:26:21'),
(40, 5, 'Eagle', '2024-12-16 17:26:23', '2024-12-16 17:26:23'),
(41, 5, 'Microsoft Word', '2024-12-16 17:26:40', '2024-12-16 17:26:40'),
(43, 5, 'Power Point', '2024-12-16 17:26:55', '2024-12-16 17:26:55'),
(44, 5, 'Volleyball', '2024-12-16 17:27:07', '2024-12-16 17:27:07'),
(45, 5, 'Basketball', '2024-12-16 17:27:14', '2024-12-16 17:27:14'),
(46, 5, 'Swimming', '2024-12-16 17:27:20', '2024-12-16 17:27:20'),
(47, 5, 'Table Tennis', '2024-12-16 17:27:45', '2024-12-16 17:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `social_profiles`
--

CREATE TABLE `social_profiles` (
  `social_id` int NOT NULL,
  `user_id` int NOT NULL,
  `platform` varchar(50) NOT NULL,
  `profile_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `social_profiles`
--

INSERT INTO `social_profiles` (`social_id`, `user_id`, `platform`, `profile_url`, `created_at`, `updated_at`) VALUES
(5, 1, 'linkedin', 'https://www.linkedin.com/in/fake-profile/', '2024-12-14 21:10:01', '2024-12-14 21:10:01'),
(6, 1, 'github', 'https://github.com/fake-user/', '2024-12-14 21:10:01', '2024-12-14 21:10:01'),
(7, 1, 'twitter', 'https://twitter.com/fakeprofile', '2024-12-14 21:10:01', '2024-12-14 21:10:01'),
(8, 1, 'instagram', 'https://www.instagram.com/fakeprofile/', '2024-12-14 21:10:01', '2024-12-14 21:10:01'),
(9, 5, 'linkedin', 'https://www.linkedin.com/in/fake-profile/', '2024-12-16 17:08:20', '2024-12-16 17:08:20'),
(10, 5, 'github', 'https://github.com/fake-user/', '2024-12-16 17:08:20', '2024-12-16 17:08:20'),
(11, 5, 'twitter', 'https://twitter.com/fakeprofile', '2024-12-16 17:08:21', '2024-12-16 17:08:21'),
(12, 5, 'instagram', 'https://www.instagram.com/fakeprofile/', '2024-12-16 17:08:21', '2024-12-16 17:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `system_activities`
--

CREATE TABLE `system_activities` (
  `activity_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `activity_type` enum('login','logout','user_creation','user_update','user_deletion','profile_update','content_change','settings_change') NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_activities`
--

INSERT INTO `system_activities` (`activity_id`, `user_id`, `activity_type`, `description`, `ip_address`, `created_at`) VALUES
(11, 1, 'login', 'User logged in successfully', '::1', '2024-12-16 16:53:15'),
(12, 5, 'login', 'User logged in successfully', '::1', '2024-12-16 16:54:41'),
(13, 4, 'login', 'User logged in successfully', '::1', '2024-12-16 17:36:01'),
(14, 4, 'login', 'User logged in successfully', '::1', '2024-12-16 17:36:31'),
(15, 5, 'login', 'User logged in successfully', '::1', '2024-12-16 17:36:39'),
(16, 1, 'login', 'User logged in successfully', '::1', '2024-12-16 19:22:30'),
(17, 1, 'login', 'User logged in successfully', '::1', '2024-12-16 19:24:51'),
(18, 4, 'login', 'User logged in successfully', '::1', '2024-12-16 19:28:07'),
(19, 1, 'login', 'User logged in successfully', '::1', '2024-12-17 09:00:30'),
(20, 1, 'login', 'User logged in successfully', '::1', '2024-12-17 09:29:08'),
(21, 4, 'login', 'User logged in successfully', '::1', '2024-12-17 09:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int NOT NULL DEFAULT '2',
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text,
  `job_title` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `password`, `role`, `profile_picture`, `bio`, `job_title`, `created_at`, `updated_at`) VALUES
(1, 'Peggy', 'Elorm', 'peggy@gmail.com', '$2y$10$7kgAbD6xJ7MvhxYX1mfPa.lxH5gyRljdDTm06hzUZMQ2q.p6SwaCO', 2, 'uploads/67607edb2c36f.jpg', 'I am a girl after His own heart🥹🤍', NULL, '2024-12-12 21:48:59', '2024-12-16 19:26:19'),
(4, 'Super', 'Admin', 'superadmin@gmail.com', '$2y$10$c3xlUbgsDIFU4K/YSUTBgu/Y6S9HdnQBPinVidffHFVTRdRIo9.vi', 1, NULL, NULL, NULL, '2024-12-16 04:19:35', '2024-12-16 04:26:08'),
(5, 'Godwin', 'Adordie Jr', 'godwin@gmail.com', '$2y$10$uKB.rOl6pjdKv3Fqq6pBx.BqjfL7E.sWBKPH5A0Iw6UE0NTpjllP.', 2, 'uploads/67605e4753995.jpg', 'I am an Electrical Engineering grad passionate about robotics, automation, IoT, and machine learning. Currently pursuing a Masters in Mechatronics at Ashesi University & ETH Zurich to deepen my expertise in integrating electrical, mechanical, and computer engineering.', NULL, '2024-12-16 16:54:04', '2024-12-16 17:29:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`achievement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `education_entries`
--
ALTER TABLE `education_entries`
  ADD PRIMARY KEY (`education_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_education_dates` (`start_date`,`end_date`);

--
-- Indexes for table `experience_entries`
--
ALTER TABLE `experience_entries`
  ADD PRIMARY KEY (`experience_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_experience_dates` (`start_date`,`end_date`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `generated_resumes`
--
ALTER TABLE `generated_resumes`
  ADD PRIMARY KEY (`resume_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_projects_featured` (`title`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD UNIQUE KEY `unique_user_skill` (`user_id`,`skill_name`),
  ADD KEY `idx_skills_category` (`skill_name`);

--
-- Indexes for table `social_profiles`
--
ALTER TABLE `social_profiles`
  ADD PRIMARY KEY (`social_id`),
  ADD UNIQUE KEY `unique_user_platform` (`user_id`,`platform`),
  ADD KEY `idx_social_platform` (`platform`);

--
-- Indexes for table `system_activities`
--
ALTER TABLE `system_activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `idx_system_activities_user` (`user_id`),
  ADD KEY `idx_system_activities_type` (`activity_type`),
  ADD KEY `idx_system_activities_date` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `achievement_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `education_entries`
--
ALTER TABLE `education_entries`
  MODIFY `education_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `experience_entries`
--
ALTER TABLE `experience_entries`
  MODIFY `experience_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `generated_resumes`
--
ALTER TABLE `generated_resumes`
  MODIFY `resume_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `social_profiles`
--
ALTER TABLE `social_profiles`
  MODIFY `social_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `system_activities`
--
ALTER TABLE `system_activities`
  MODIFY `activity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `education_entries`
--
ALTER TABLE `education_entries`
  ADD CONSTRAINT `education_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `experience_entries`
--
ALTER TABLE `experience_entries`
  ADD CONSTRAINT `experience_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `generated_resumes`
--
ALTER TABLE `generated_resumes`
  ADD CONSTRAINT `generated_resumes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `social_profiles`
--
ALTER TABLE `social_profiles`
  ADD CONSTRAINT `social_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `system_activities`
--
ALTER TABLE `system_activities`
  ADD CONSTRAINT `system_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
