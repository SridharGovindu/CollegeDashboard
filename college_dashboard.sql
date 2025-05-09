-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2025 at 03:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `title`, `description`, `filename`, `course`, `semester`, `branch`, `start_date`, `end_date`, `created_at`) VALUES
(3, 'SDE', 'Assignment5', '1746772488_Lab Evaluation Assignment 5.pdf', NULL, 6, 'CSE', '2025-05-09', '2025-05-13', '2025-05-09 06:34:48'),
(4, 'Resume UPD', 'Updated resume', '1746772702_Resume.pdf', NULL, 6, 'CSE', '2025-05-09', '2025-05-13', '2025-05-09 06:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`id`, `student_id`, `title`, `filename`, `submitted_at`) VALUES
(1, 2, 'SDE', '1746553612_BL.EN.U4CSE22117_Lab_Assignment_5.docx', '2025-05-06 17:46:52'),
(2, 2, 'SDE', '1746553620_BL.EN.U4CSE22117_Lab_Assignment_5.docx', '2025-05-06 17:47:00'),
(3, 5, 'SDE', '1746553682_BL.EN.U4CSE22117_Lab_Assignment_5.docx', '2025-05-06 17:48:02'),
(4, 5, 'Resume', '1746773676_Lab_Evaluation_Assignment_4_CO4.pdf', '2025-05-09 06:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('Present','Absent') DEFAULT NULL,
  `marked_by` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `date`, `status`, `marked_by`, `subject`) VALUES
(3, 2, '2025-05-09', 'Present', NULL, 'Software'),
(4, 5, '2025-05-09', 'Present', NULL, 'Software'),
(5, 2, '2025-05-09', 'Present', NULL, 'Software'),
(6, 5, '2025-05-09', 'Present', NULL, 'Software'),
(7, 2, '2025-05-09', 'Present', NULL, 'SSk'),
(8, 5, '2025-05-09', 'Present', NULL, 'SSk'),
(9, 2, '2025-05-09', 'Present', NULL, 'SSk'),
(10, 5, '2025-05-09', 'Present', NULL, 'SSk'),
(11, 2, '2025-05-09', 'Present', NULL, 'SSk'),
(12, 5, '2025-05-09', 'Absent', NULL, 'SSk');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `created_at`, `start_date`, `end_date`) VALUES
(1, 'Semester Exams', 'Even Semester Exam Dates', '0000-00-00', '2025-05-06 15:05:30', '2025-05-08', '2025-05-22');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reg_no` varchar(50) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `reg_no`, `course`, `semester`, `department`, `branch`, `created_at`) VALUES
(1, 2, 'N/A', 'B.Tech', 6, 'Computer Science', 'CSE', '2025-05-06 17:23:39'),
(2, 5, 'N/A', 'N/A', 6, NULL, 'CSE', '2025-05-06 17:44:36');

-- --------------------------------------------------------

--
-- Table structure for table `study_materials`
--

CREATE TABLE `study_materials` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_materials`
--

INSERT INTO `study_materials` (`id`, `teacher_id`, `subject`, `filename`, `branch`, `semester`, `uploaded_at`) VALUES
(1, 4, 'SDE_intro', '1746774349_Overview of Process Models_lec 1.docx', 'CSE', 6, '2025-05-09 12:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `similarity_score` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `student_id`, `assignment_id`, `file_path`, `submitted_at`, `similarity_score`) VALUES
(1, 5, 2, '681da0983c646_Resume.pdf', '2025-05-09 11:58:40', NULL),
(2, 5, 2, '681da0a1d909c_Time_Series_T04 (1).pdf', '2025-05-09 11:58:49', NULL),
(3, 5, 4, '681da3e10fad1_Resume.pdf', '2025-05-09 12:12:41', NULL),
(4, 5, 4, '681da5bea1622_Scala-Part1.pdf', '2025-05-09 12:20:38', NULL),
(5, 5, 4, '681da60c2352c_Resume.pdf', '2025-05-09 12:21:56', NULL),
(6, 5, 4, '681da66762d03_Lab_Evaluation_Assignment_4_CO4.pdf', '2025-05-09 12:23:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `subject`, `department`, `phone`) VALUES
(1, 3, 'N/A', NULL, NULL),
(2, 4, 'N/A', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'sridhargovindu24@gmail.com', '$2y$10$NX.5hPmUZlvCI6Q5kL5ooO1XqE5684X7BWlWKo08Woz6OlNCuz3C6', 'admin', '2025-05-06 13:33:17'),
(2, 'Maddipudi Janakiram', 'janakiram7283@gmail.com', '$2y$10$PEgbhae.NVivAZ0PoSImGOcHApUl3XLMtawigz4FBYq6QXCih4v02', 'student', '2025-05-06 14:57:56'),
(3, 'Hemanth', 'hemanthsaga@gmail.com', '$2y$10$nH/OqHUT/4dX1nZmMY3YnOydJdWGgZAmF2eMBUsgJ0yQdjI//P48y', 'teacher', '2025-05-06 14:58:44'),
(4, 'Hemanth injeti', 'injetihemanth@gmail.com', '$2y$10$2AWoiOiR2.UgsvdZWqidMeSJ9c01RtH2MRaoQP0jjQkdlDZmZ5xxe', 'teacher', '2025-05-06 15:40:29'),
(5, 'Vishnu Ram Sai', 'vishnuram@gmail.com', '$2y$10$fnXQnaZcsQjhdX9FQIn44OjLRxxWN71RUrV2Z4Vb0sUbGlOjNOy3i', 'student', '2025-05-06 17:44:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `marked_by` (`marked_by`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `study_materials`
--
ALTER TABLE `study_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `assignment_submissions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`marked_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD CONSTRAINT `study_materials_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
