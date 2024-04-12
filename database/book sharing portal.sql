-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Apr 11, 2024 at 10:24 AM
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
-- Database: `bookflix`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_info`
--

CREATE TABLE `book_info` (
  `book_id` int(11) NOT NULL,
  `Title` varchar(20) NOT NULL,
  `Author` varchar(20) NOT NULL,
  `Location` varchar(20) NOT NULL,
  `Language` varchar(20) NOT NULL,
  `Dimensions` varchar(20) NOT NULL,
  `Review` text NOT NULL,
  `Edition` varchar(20) NOT NULL,
  `Publication` varchar(20) NOT NULL,
  `Pages` int(100) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `image` varchar(10) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_info`
--

INSERT INTO `book_info` (`book_id`, `Title`, `Author`, `Location`, `Language`, `Dimensions`, `Review`, `Edition`, `Publication`, `Pages`, `Price`, `category_name`, `image`, `date`, `user_id`, `added_by`) VALUES
(15, 'Introduction to Huma', 'David C. Perry', 'Pune', 'English ', '20*30', 'It introduces students to central concepts in anthropology, psychology, sociology, political science, economics, and history. Hence, students are made aware of the interdependence of the social sciences.', '1', 'Oxford University Pr', 300, 600, 'Arts', 'art1.png', '2024-04-04 23:24:52', NULL, 104),
(16, 'Art: A Brief History', 'Marilyn Stokstad', 'Kansas', 'English ', ' 21.34 x 3.81 x 27.4', 'This comprehensive survey of world art is based on the most recent scholarship, and includes coverage of arts inspired by all the major religions, the contribution of women and minorities, and contains discussions of all the arts, including ceramics, glass, metal, enamel, and the fiber arts.', '6', 'Pearson', 622, 850, 'Arts', 'art2.png', '2024-04-05 00:48:10', NULL, 104),
(17, 'The Art Book   ', 'Phaidon Editors', 'London and New York ', 'English', '245 × 210 mm', 'An A-Z guide to 500 great painters and sculptors from medieval to modern times, it debunks art-historical classifications by throwing together brilliant examples of all periods, schools, visions and techniques.', '3', 'Phaidon Press', 515, 1200, 'Arts', 'art3.png', '2024-04-05 00:52:34', NULL, 104),
(18, 'The Elements of Typo', 'Robert Bringhurst', 'Pune', 'English ', '22.8 x 2.4 x 13.3 cm', 'The Elements of Typographic Style by Robert Bringhurst (Book Summary) This book contains more than I\'ve ever wanted to know about typography. It describes not only how to use fonts, but also how to create them, explaining the math and science involved in font design.', '4', ' Hartley & Marks  ', 320, 1100, 'Arts', 'art4.png', '2024-04-05 00:54:40', NULL, 104),
(19, 'Concepts of Physics ', 'H.C. Verma', 'Pune', 'English ', '19.8 x 12.9 x 1.9 cm', 'The concepts of physics include factors like heat, light, motion, energy, matter, and electricity. In addition to this, it also talks about the relation between matter and energy with the help of mathematics.', '2', 'Bharati Bhawan  ', 462, 750, 'Pure Science', 'sci1.png', '2024-04-05 00:58:55', NULL, 104),
(20, 'Chemistry: The Centr', 'Theodore L. Brown', 'Pune', 'English', '‎27.94 x 22.1 x 4.32', 'Chemistry is often called the central science because of its role in connecting the physical sciences, which include chemistry, with the life sciences, pharmaceutical sciences and applied sciences such as medicine and engineering.', '14', 'Pearson', 1248, 900, 'Pure Science', 'sci2.png', '2024-04-05 01:00:46', NULL, 104),
(21, 'CLAT 2024: Comprehen', 'Rajesh Sharma', 'Pune', 'English ', '27 x 22 x 1.5 cm', 'Against this backdrop, GKP\'s \"CLAT Guide\" will be a perfect preparation book for law aspirants. Containing solved papers of the 2023 CLAT and AILET examination, the book will help students correctly assess their preparation levels. Divided into dedicated modulesSolved papers of 2023 exam.', '5', 'Universal Law Public', 600, 900, 'CLAT', 'clat1.jpg', '2024-04-05 01:09:34', NULL, 104),
(22, 'Legal Awareness and ', 'A.P. Bhardwaj ', 'Pune', 'English ', '20.3 x 25.4 x 4.7 cm', 'Legal Aptitude or Legal Reasoning is a section in law entrance exams that holds maximum weightage and demands candidates to possess good knowledge of fundamentals. Through this section, the exam conducting authorities test the legal awareness, analytical skills and problem-solving ability of candidates.', '3', 'McGraw Hill Educatio', 616, 800, 'CLAT', 'clat2.png', '2024-04-05 01:12:26', NULL, 104),
(23, 'Biology', 'Peter H. Raven', 'Pune', 'English ', '20*30', 'Biology is a traditional, comprehensive introductory biology textbook, with coverage from cell structure and function to the conservation of biodiversity', '11', 'McGraw-Hill Educatio', 710, 1300, 'Pure Science', 'sci3.png', '2024-04-06 18:37:32', NULL, 104),
(24, 'nkvjkdfn', 'nknj', 'pune', 'vkf v', ', ,vmf', 'm,vfdkn', '1', 'knvjfdnvj', 200, 500, 'GATE', 'uuu.png', '2024-04-09 13:09:57', NULL, NULL),
(25, 'hvhjfb', 'nmcn', 'nvvfn', 'cm,', ',m f', ',mf vm', '1', 'nkcn ds', 500, 500, 'Fiction', 'uuu.png', '2024-04-09 13:21:24', NULL, NULL),
(26, ',mfnkjbjge', 'lkgkb', 'pune', 'm.,gfm b', 'lbkgmblk', 'lbgfmnb', 'v nfd', 'n fkc', 500, 500, 'Arts', 'uuu.png', '2024-04-09 17:11:52', NULL, 0),
(27, 'v fnj', 'nbgnf', ',mfv', 'n k', 'ml mf', ',v mgfn', '1', 'nitjng', 5, 20, 'Arts', 'uuu.png', '2024-04-09 17:13:38', NULL, 109);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `book_id` int(20) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(20) NOT NULL,
  `image` varchar(25) NOT NULL,
  `quantity` int(25) NOT NULL,
  `total` double(10,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `book_id`, `user_id`, `name`, `price`, `image`, `quantity`, `total`, `date`) VALUES
(6, 26, 105, ',mfnkjbjge', 500, 'uuu.png', 1, 500.00, '2024-04-11 10:57:47');

-- --------------------------------------------------------

--
-- Table structure for table `confirm_order`
--

CREATE TABLE `confirm_order` (
  `order_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` int(12) NOT NULL,
  `address` varchar(500) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `total_books` varchar(500) NOT NULL,
  `order_date` varchar(100) NOT NULL,
  `payment_status` varchar(100) NOT NULL DEFAULT 'pending',
  `date` varchar(20) NOT NULL,
  `total_price` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirm_order`
--

INSERT INTO `confirm_order` (`order_id`, `user_id`, `name`, `email`, `number`, `address`, `payment_method`, `total_books`, `order_date`, `payment_status`, `date`, `total_price`) VALUES
(31, 51, 'rtyrty', 'pawan@gmail.coma', 0, 'yey, brtb, hrthgeg,  6ygege - 6546', 'cash on delivery', ' pawan #46,(1)  yukuyk #44,(1)  sdfsd #54,(1) ', '24-Feb-2023', 'completed', '24.02.2023', 7102.00),
(32, 51, 'dsdd', 'pawan@gmail.coma', 2147483647, 'hrthgerg, 747hfh, brt,  ygege - 6546', 'Debit card', ' iuji #89,(1)  Ray Bearer #88,(1) ', '24-Feb-2023', 'completed', '10.03.2023', 6424.00),
(0, 105, 'sanchit', 'sai@gmail.com', 2147483647, 'pune, pune, Maharashtra, india - 411005', 'cash on delivery', ' Biology #0,(1) ', '09-Apr-2024', 'pending', '', 1300.00),
(0, 105, 'sanchit', 'sai@gmail.com', 2147483647, 'pune, pune, Maharashtra, india - 411005', 'Amazon Pay', '', '09-Apr-2024', 'pending', '', 0.00),
(0, 109, 'Thakaji', 'khemnarst21.comp@coeptech.ac.in', 2147483647, 'Ganesh Colony Loni kd. Tal-Rahata, Ahmednagar, Maharashtra, India - 413713', 'cash on delivery', ' Biology #23,(3)  Legal Awareness and  #22,(3) ', '09-Apr-2024', 'pending', '', 6300.00),
(0, 109, 'Thakaji', 'khemnarst21.comp@coeptech.ac.in', 2147483647, 'Ganesh Colony Loni kd. Tal-Rahata, Ahmednagar, Maharashtra, India - 413713', 'cash on delivery', ' Legal Awareness and  #22,(1)  Biology #23,(2) ', '09-Apr-2024', 'pending', '', 3400.00);

-- --------------------------------------------------------

--
-- Table structure for table `donated_books`
--

CREATE TABLE `donated_books` (
  `don_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donated_books`
--

INSERT INTO `donated_books` (`don_id`, `title`, `author`, `location`, `image_path`, `donation_date`, `name`) VALUES
(17, 'nvdbhv', 'kvjnf', 'nlkvnjf', 'uuu.png', '2024-04-08 18:36:28', 'sai'),
(18, 'hdbhcjb', 'vnfd', 'pune', 'uuu.png', '2024-04-09 12:30:40', 'sanchit'),
(19, 'jcjxchjsdhv', 'hjjhn', 'jcnhsdjk', 'uuu (1).png', '2024-04-11 05:21:42', 'sai');

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `ebook_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`ebook_id`, `title`, `author`, `file_path`, `image`) VALUES
(8, 'lk mvm', 'hc verma', 'Yoga Club Report 2023-2024.docx', 'uuu (1).png'),
(9, 'vhbf', 'm.lakshimikant', 'saplings.docx', 'uuu.png'),
(10, 'gyuyvcy', 'm.lakshimikant', 'Yoga Club Report 2023-2024.docx', 'uuu.png');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` varchar(50) NOT NULL,
  `receiver_id` varchar(50) NOT NULL,
  `message_content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message_content`, `timestamp`) VALUES
(1, 'admin', '108', 'A book has been successfully donated to your NGO.', '2024-04-08 08:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `msg`
--

CREATE TABLE `msg` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `number` int(20) NOT NULL,
  `msg` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msg`
--

INSERT INTO `msg` (`id`, `user_id`, `name`, `email`, `number`, `msg`, `date`) VALUES
(6, 51, 'pawan ', 'pawan@gmail.com', 2147483647, 'MOST DANGEROUS GAME AND OTHER STORIES OF ADVENTURE, THE \r\n\r\nis there availability', '2023-02-23 13:41:50'),
(7, 51, 'dfbgf', 'hdgfh@vszv', 0, 'dh', '2023-02-23 13:47:26'),
(8, 51, 'sv', 'jisaxih205@minterp.c', 0, 'xhf', '2023-02-23 13:49:56'),
(0, 0, 'sanchit', 'khemnarst21.comp@coe', 2147483647, 'this is a good website', '2024-04-04 17:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(225) NOT NULL,
  `user_id` int(100) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `pincode` int(6) NOT NULL,
  `book` varchar(50) NOT NULL,
  `unit_price` double(10,2) NOT NULL,
  `quantity` int(10) NOT NULL,
  `sub_total` double(10,2) NOT NULL,
  `payment_status` varchar(100) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address`, `city`, `state`, `country`, `pincode`, `book`, `unit_price`, `quantity`, `sub_total`, `payment_status`) VALUES
(0, 109, 'Ganesh Colony Loni kd. Tal-Rahata', 'Ahmednagar', 'Maharashtra', 'India', 413713, 'Legal Awareness and ', 800.00, 1, 800.00, 'pending'),
(0, 109, 'Ganesh Colony Loni kd. Tal-Rahata', 'Ahmednagar', 'Maharashtra', 'India', 413713, 'Biology', 1300.00, 2, 2600.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `Id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`Id`, `name`, `surname`, `email`, `password`, `user_type`) VALUES
(100, 'sanchit', 'khemnar', 'saisanchitkhemnar@gm', '12345', 'User'),
(104, 'sanchit', 'khemnar', 'sai@gmail.com', '12345', 'Admin'),
(105, 'sai', 'sai', 'kh@coeptech.ac.in', '123456', 'User'),
(108, 'foundation', 'sai', 'sanchit@gamil.com', '123456', 'NGO'),
(109, 'sanchit', 'khemnar', 'sanchit@gmail.com', '123456', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_info`
--
ALTER TABLE `book_info`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donated_books`
--
ALTER TABLE `donated_books`
  ADD PRIMARY KEY (`don_id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`ebook_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_info`
--
ALTER TABLE `book_info`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `donated_books`
--
ALTER TABLE `donated_books`
  MODIFY `don_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `ebook_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_info`
--
ALTER TABLE `book_info`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users_info` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
