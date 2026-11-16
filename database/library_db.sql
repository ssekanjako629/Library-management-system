-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2026 at 09:08 AM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `name`, `country`) VALUES
(6, 'Chinua Achebe', 'Nigeria'),
(7, 'Ngugi wa Thiong\'o', 'Kenya'),
(8, 'J.K. Rowling', 'United Kingdom'),
(9, 'George Orwell', 'United Kingdom'),
(10, 'Wole Soyinka', 'Nigeria');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `publisher` varchar(150) NOT NULL,
  `year_published` year(4) NOT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `available_copies` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `isbn`, `title`, `author_id`, `category_id`, `publisher`, `year_published`, `total_copies`, `available_copies`) VALUES
(11, '9780000000001', 'Things Fall Apart', 6, 3, 'Heinemann', '1958', 5, 5),
(12, '9780000000002', 'No Longer at Ease', 6, 3, 'Heinemann', '1960', 4, 4),
(13, '9780000000003', 'The River Between', 7, 3, 'Heinemann', '1965', 3, 2),
(14, '9780000000004', 'A Grain of Wheat', 7, 3, 'Heinemann', '1967', 4, 2),
(15, '9780000000005', 'Harry Potter', 8, 3, 'Bloomsbury', '1997', 6, 5),
(16, '9780000000006', 'Animal Farm', 9, 3, 'Secker and Warburg', '1945', 5, 5),
(17, '9780000000007', 'Homage to Catalonia', 9, 5, 'Secker and Warburg', '1938', 3, 2),
(18, '9780000000008', 'Death and the Kings Horseman', 10, 3, 'Methuen', '1975', 2, 1),
(19, '9780000000009', 'Introduction to Science', 8, 4, 'Oxford Press', '2020', 4, 3),
(20, '9780000000010', 'African History', 10, 5, 'East African Publishers', '2019', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(3, 'Fiction', 'Fiction stories'),
(4, 'Science', 'Science and technology books'),
(5, 'History', 'Historical books'),
(6, 'Education', 'Learning and textbooks'),
(7, 'Biography', 'Books about peoples lives');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `date_issued` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `date_returned` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `member_id`, `book_id`, `date_issued`, `due_date`, `date_returned`) VALUES
(1, 1, 11, '2026-10-01', '2026-10-15', '2026-10-12'),
(2, 2, 12, '2026-10-05', '2026-10-19', '2026-10-18'),
(3, 3, 13, '2026-10-10', '2026-10-24', NULL),
(4, 4, 14, '2026-10-15', '2026-10-29', NULL),
(5, 5, 15, '2026-10-20', '2026-11-03', NULL),
(6, 6, 16, '2026-10-25', '2026-11-08', '2026-11-05'),
(7, 8, 17, '2026-10-28', '2026-11-11', NULL),
(8, 9, 18, '2026-11-01', '2026-11-15', NULL),
(9, 10, 19, '2026-11-02', '2026-11-16', NULL),
(10, 1, 20, '2026-10-20', '2026-11-03', '2026-11-01'),
(11, 10, 14, '2026-11-15', '2026-11-29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `member_number` varchar(30) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `date_joined` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `member_number`, `full_name`, `email`, `phone`, `status`, `date_joined`) VALUES
(1, 'MEM001', 'John Mukasa', 'john@gmail.com', '0701000001', 'active', '2026-01-10'),
(2, 'MEM002', 'Mary Namusoke', 'mary@gmail.com', '0701000002', 'active', '2026-01-12'),
(3, 'MEM003', 'Peter Ssemanda', 'peter@gmail.com', '0701000003', 'active', '2026-01-15'),
(4, 'MEM004', 'Sarah Nakato', 'sarah@gmail.com', '0701000004', 'active', '2026-01-18'),
(5, 'MEM005', 'David Kato', 'david@gmail.com', '0701000005', 'active', '2026-01-20'),
(6, 'MEM006', 'Grace Akello', 'grace@gmail.com', '0701000006', 'active', '2026-02-01'),
(7, 'MEM007', 'James Okello', 'james@gmail.com', '0701000007', 'suspended', '2026-02-05'),
(8, 'MEM008', 'Ruth Achieng', 'ruth@gmail.com', '0701000008', 'active', '2026-02-10'),
(9, 'MEM009', 'Brian Tumusiime', 'brian@gmail.com', '0701000009', 'active', '2026-02-15'),
(10, 'MEM010', 'Esther Nansubuga', 'esther@gmail.com', '0701000010', 'active', '2026-02-20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `role` enum('admin','librarian') NOT NULL DEFAULT 'librarian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password`, `full_name`, `role`) VALUES
(1, 'admin', '$2y$10$80y9gT8bEMdlLcNLm60Mbu1FvhliNloxeiPid6SpSWeRcRDoEwH/2', 'System Administrator', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `unique_isbn` (`isbn`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `idx_category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `idx_loans_member_id` (`member_id`),
  ADD KEY `idx_loans_book_id` (`book_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_number` (`member_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loans_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
