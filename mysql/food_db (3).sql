-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2026 at 09:53 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc`
--

CREATE TABLE `admin_acc` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  `store_name` varchar(100) NOT NULL DEFAULT '''My Food Store''',
  `store_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_acc`
--

INSERT INTO `admin_acc` (`id`, `name`, `email`, `password`, `role`, `store_name`, `store_image`) VALUES
(1, 'Administrator', 'admin@gmail.com', 'admin123', 'admin', 'Jamal', '');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('available','unavailable') NOT NULL DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `food_name`, `description`, `price`, `quantity`, `status`, `image`) VALUES
(4, 'kinilaw', 'isda', '50.00', 0, 'available', '1ccf0d8e1f2dc46f7facf793da95952f.jpg'),
(5, 'Fried Chicken', 'chicken pero Fried', '50.00', 0, 'available', '60cd9f62237a136981697866e4449b90.jpg'),
(6, 'Fillet', 'ambot', '60.00', 0, 'available', '5f1480d5cccba2c5d3c8067fc0820c4c.jpg'),
(7, 'Chicken Fillet', 'Favorite sa akong kaila', '50.00', 0, 'available', NULL),
(8, 'Chicken stephen curry', 'Manok nga maayo mo 3 points', '50.00', 7, 'available', NULL),
(12, 'gwapo', 'dsadsad', '50.00', 6, 'available', NULL),
(41, 'Pizza', 'circle ni siya', '60.00', 4, 'available', 'c73e09f052c1f50c720a8aab442fc170.jpg'),
(42, 'Test Adobo', 'Test adobo des', '100.00', 5, 'available', NULL),
(43, 'Test Burger', 'Test Burger des', '150.00', 10, 'available', NULL),
(44, 'Chicken Stephen Curry', 'Gi luto ni Daniel', '99.00', 1, 'available', NULL),
(45, 'Fahhhh', 'Yah', '188.00', 10, 'unavailable', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reservation_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Pending','Approved','Reservation','') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `food_id`, `quantity`, `reservation_time`, `status`) VALUES
(1, 1, 7, 2, '2026-08-19 03:33:08', ''),
(2, 1, 4, 3, '2026-09-30 10:09:00', ''),
(3, 1, 12, 9, '2026-09-30 10:08:42', ''),
(4, 1, 12, 3, '2026-09-30 10:08:36', ''),
(5, 1, 7, 1, '2026-10-02 04:48:41', ''),
(6, 1, 5, 1, '2026-09-30 10:45:46', ''),
(7, 2, 34, 1, '2026-10-02 05:01:56', ''),
(8, 3, 33, 1, '2026-10-02 05:09:28', ''),
(9, 3, 33, 1, '2026-10-02 05:14:20', ''),
(10, 2, 32, 2, '2026-10-02 05:16:53', ''),
(11, 3, 5, 3, '2026-10-02 05:20:04', ''),
(12, 3, 4, 3, '2026-10-02 05:23:01', ''),
(13, 3, 6, 3, '2026-10-02 05:23:51', ''),
(14, 3, 5, 1, '2026-10-02 05:29:36', ''),
(15, 3, 7, 1, '2026-10-02 05:40:06', ''),
(16, 3, 5, 1, '2026-10-02 06:02:16', ''),
(17, 3, 4, 1, '2026-10-02 06:04:57', ''),
(18, 2, 33, 1, '2026-10-02 06:20:09', ''),
(19, 4, 4, 1, '2026-10-02 06:09:41', ''),
(20, 4, 13, 1, '2026-10-02 06:20:14', ''),
(21, 4, 4, 1, '2026-10-02 06:23:26', ''),
(22, 4, 33, 1, '2026-10-02 06:27:57', ''),
(23, 4, 5, 1, '2026-10-02 06:31:11', ''),
(24, 2, 33, 1, '2026-10-02 06:31:20', ''),
(25, 2, 4, 1, '2026-10-02 06:41:23', ''),
(26, 2, 7, 1, '2026-10-02 06:46:14', ''),
(27, 2, 8, 1, '2026-10-02 07:35:48', ''),
(28, 2, 8, 1, '2026-10-02 07:40:18', ''),
(29, 2, 8, 1, '2026-10-02 07:35:32', ''),
(30, 3, 36, 1, '2026-10-02 07:45:33', 'Approved'),
(31, 3, 41, 1, '2026-10-02 08:07:55', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` enum('user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'User', 'user@gmail.com', 'user123', 'user'),
(2, 'Jamal', 'jamalgarcia294@gmail.com', 'jam123', 'user'),
(3, 'james', '2092617@g.cu.edu.ph', 'james123', 'user'),
(4, 'Damian', 'datadamian06@gmail.com', 'dam123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_acc`
--
ALTER TABLE `admin_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_acc`
--
ALTER TABLE `admin_acc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
