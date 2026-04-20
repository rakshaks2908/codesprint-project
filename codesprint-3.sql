-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2026 at 07:46 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codesprint`
--

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `Problem_id` int(45) NOT NULL,
  `User_id` int(30) NOT NULL,
  `difficulty` varchar(45) NOT NULL,
  `topic` varchar(46) NOT NULL,
  `problem_title` varchar(34) NOT NULL,
  `platform` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`Problem_id`, `User_id`, `difficulty`, `topic`, `problem_title`, `platform`) VALUES
(1, 3, 'easy', 'array', 'slayarray', 'LeetCode'),
(2, 3, 'hard', 'greedy', 'coinchange', 'HackerRank'),
(3, 4, 'medium', 'dp', 'stairs', 'AtCoder'),
(4, 3, 'Hard', 'Arrays', 'Two Sum', 'LeetCode'),
(5, 3, 'Easy', 'Arrays', '3sum', 'LeetCode'),
(6, 3, 'Easy', 'Arrays', '4sum', 'LeetCode'),
(7, 3, 'Easy', 'Arrays', '5sum', 'LeetCode');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_text` varchar(34) NOT NULL,
  `correct_option` char(1) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_text`, `correct_option`, `quiz_id`, `option_a`, `option_b`, `option_c`, `option_d`) VALUES
('What is 2+2?', 'B', 9, '3', '4', '5', '6'),
('Which is FIFO?', 'B', 9, 'Stack', 'Queue', 'Tree', 'Graph'),
('Time complexity of binary search?', 'B', 9, 'O(n)', 'O(log n)', 'O(n^2)', 'O(1)'),
('ee', 'A', 10, 'ee', 'e', 'ee', 'ee'),
('What is array', 'A', 11, 'aaa', 'ff', 'ddf', 'dd');

-- --------------------------------------------------------

--
-- Table structure for table `quizes`
--

CREATE TABLE `quizes` (
  `quiz_id` int(11) NOT NULL,
  `title` varchar(23) NOT NULL,
  `duration` int(43) NOT NULL,
  `points` int(43) NOT NULL,
  `max_questions` int(44) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizes`
--

INSERT INTO `quizes` (`quiz_id`, `title`, `duration`, `points`, `max_questions`) VALUES
(9, 'sample quiz', 30, 5, 10),
(10, 'sample 2', 30, 10, 1),
(11, 'Array Test', 30, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(34) NOT NULL,
  `score` int(43) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`attempt_id`, `user_id`, `score`, `quiz_id`, `attempted_at`) VALUES
(1, 3, 20, 9, '2026-04-19 19:04:40'),
(2, 4, 10, 10, '2026-04-19 19:05:21'),
(3, 6, 0, 9, '2026-04-19 19:32:02'),
(4, 6, 10, 9, '2026-04-19 19:34:45'),
(5, 3, 10, 10, '2026-04-19 21:23:32'),
(6, 6, 15, 9, '2026-04-19 22:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `role`, `Password`) VALUES
(1, 'Rakshak', 'r@gmail.com', 'admin2', '1234'),
(2, 'Keyuri', 'k@gmail.com', 'admin1', '1000'),
(3, 'Anshuman ', 'a@gmail.com', 'student', '3000'),
(4, 'Rahul', 'rahul@gmail.com', 'student', '123'),
(5, 'Abhay', 'abhay@gmail.com', 'student', '12'),
(6, 'arav', 'arav@gmail.com', 'student', '98'),
(7, 'kshitj', 'ks@gmail.com', 'student', '12'),
(8, 'Arjun', 'arjun@gmail.com', 'student', '12345'),
(9, 'Rohan', 'rohan@gmail.com', 'student', '1234'),
(10, 'Amulya', 'amu@gmail.com', 'student', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`Problem_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD KEY `fk_quiz` (`quiz_id`);

--
-- Indexes for table `quizes`
--
ALTER TABLE `quizes`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`attempt_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `Problem_id` int(45) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quizes`
--
ALTER TABLE `quizes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizes` (`quiz_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
