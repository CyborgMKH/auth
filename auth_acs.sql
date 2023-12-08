-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20230212.f19d22c671
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2023 at 04:02 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auth_acs`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`) VALUES
(5, 'Suman Mukhiya', 'suman.chaudhary4848@gmail.com', '$2y$10$JqFJ1KAW56l6v1WO.Nh9jejHU1zwNMtnpF4M8H0ZKmF.8EZDegCgu'),
(7, 'Suman Mukhiya', 'suman.chaudhary1114848@gmail.com', '$2y$10$B0QIXG/.OlpCV57Fe7TjlePsbyoiUWUR6eIZJs9KijtAXkdWHVK9e'),
(8, 'Suman Mukhiya', 'suman.chaudhary48481111@gmail.com', '$2y$10$.4I7.CHRP4lC2Gv.5tsb6ukJlgXpP.XYqjW5ShmJh6aGWditkTbp6'),
(9, '646545446464', 'suman.chauhsjfhkds@gmail.com', '$2y$10$giJtLanDvwbWKDxm93w/Qe/mkGr03Up0xaHhtHdF.6YcRJEHNzxQm'),
(10, 'Suman Mukhiya', 'suman@gmail.com', '$2y$10$NkM1KZ0uSJbcDjAr3KFuzePw1h4faRKNS4rU1dW3RBYvltG8mUIGm'),
(11, 'Suman Mukhiya', 'suman.chaudhary1111114848@gmail.com', '$2y$10$UutjeaF7De..jnhyupICHeRnvpSYrZJk.2mwulqgWz0dYxY3bFnQm'),
(12, 'Suman Mukhiya', 'suman.chaudhary484812345@gmail.com', '$2y$10$mKH2AKw1NxwByFiPJQFziOx/ocop0MI5b75QaKCgFEdueey6E7NWe'),
(13, 'Suman Mukhiya', 'suman.chaudhary48696948@gmail.com', '$2y$10$BGMQIsml6xKO85U5t5a0O.cj/bAthPtN8A6N.jKFG01EA86T.Z3gu'),
(14, 'Suman Mukhiya', 'suman.chaudhary4869696948@gmail.com', '$2y$10$.w3ci.B34S0RxWhQne7t9.6oEmxkzkchcqQqW48EScR71bOA9WlsG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
