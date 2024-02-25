-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2024 at 06:02 PM
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
-- Database: `mingala`
--

-- --------------------------------------------------------

--
-- Table structure for table `carton_detail`
--

CREATE TABLE `carton_detail` (
  `id` int(11) NOT NULL,
  `id_carton` varchar(20) NOT NULL,
  `id_item` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carton_mingala`
--

CREATE TABLE `carton_mingala` (
  `id` int(11) NOT NULL,
  `id_carton` varchar(20) NOT NULL,
  `nomor_carton` int(11) NOT NULL,
  `qty_per_carton` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_mingala`
--

CREATE TABLE `item_mingala` (
  `id` int(11) NOT NULL,
  `id_item` varchar(20) NOT NULL,
  `style` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `size` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `mo` varchar(255) NOT NULL,
  `date_wh` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_item`
--

CREATE TABLE `stock_item` (
  `id` int(11) NOT NULL,
  `id_item` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_packing`
--

CREATE TABLE `tb_packing` (
  `id_packing` varchar(255) NOT NULL,
  `id_carton` varchar(255) NOT NULL,
  `qty_carton` int(11) NOT NULL,
  `date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_mingala`
--

CREATE TABLE `user_mingala` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_mingala`
--

INSERT INTO `user_mingala` (`id`, `username`, `role`, `email`, `password`) VALUES
(1, 'IAMMM', 'admin', 'iam12@gmail.com', 'iam123'),
(2, 'Adam', 'user', 'adam12@gmail.com', 'adam123'),
(3, 'Sister', 'packing', 'packing@gmail.com', 'packing123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carton_detail`
--
ALTER TABLE `carton_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carton_mingala`
--
ALTER TABLE `carton_mingala`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_mingala`
--
ALTER TABLE `item_mingala`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_item`
--
ALTER TABLE `stock_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_packing`
--
ALTER TABLE `tb_packing`
  ADD PRIMARY KEY (`id_packing`);

--
-- Indexes for table `user_mingala`
--
ALTER TABLE `user_mingala`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carton_detail`
--
ALTER TABLE `carton_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carton_mingala`
--
ALTER TABLE `carton_mingala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_mingala`
--
ALTER TABLE `item_mingala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_item`
--
ALTER TABLE `stock_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_mingala`
--
ALTER TABLE `user_mingala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
