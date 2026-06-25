-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 02:49 PM
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
-- Database: `laptop`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `cl_id` int(255) NOT NULL,
  `pc_name` varchar(255) NOT NULL,
  `ip_add` varchar(255) NOT NULL,
  `mac` text NOT NULL,
  `latitude` text NOT NULL,
  `longitude` text NOT NULL,
  `c_id` int(255) NOT NULL,
  `locate` tinyint(1) NOT NULL,
  `scream` int(11) NOT NULL,
  `p_msg` text NOT NULL,
  `camera` int(255) NOT NULL,
  `screenshot` int(255) NOT NULL,
  `datawipe` int(255) NOT NULL,
  `cam_path` varchar(255) NOT NULL,
  `timestamp1` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `system` varchar(50) DEFAULT NULL,
  `node_name` varchar(255) DEFAULT NULL,
  `release_version` varchar(255) DEFAULT NULL,
  `machine` varchar(50) DEFAULT NULL,
  `processor` varchar(255) DEFAULT NULL,
  `cpu_cores_physical` int(11) DEFAULT NULL,
  `cpu_cores_logical` int(11) DEFAULT NULL,
  `cpu_frequency` float DEFAULT NULL,
  `total_memory` float DEFAULT NULL,
  `available_memory` float DEFAULT NULL,
  `used_memory` float DEFAULT NULL,
  `memory_usage` float DEFAULT NULL,
  `total_disk` float DEFAULT NULL,
  `used_disk` float DEFAULT NULL,
  `free_disk` float DEFAULT NULL,
  `disk_usage` float DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`cl_id`, `pc_name`, `ip_add`, `mac`, `latitude`, `longitude`, `c_id`, `locate`, `scream`, `p_msg`, `camera`, `screenshot`, `datawipe`, `cam_path`, `timestamp1`, `system`, `node_name`, `release_version`, `machine`, `processor`, `cpu_cores_physical`, `cpu_cores_logical`, `cpu_frequency`, `total_memory`, `available_memory`, `used_memory`, `memory_usage`, `total_disk`, `used_disk`, `free_disk`, `disk_usage`, `last_updated`) VALUES
(10, 'MAHA3114', '192.168.0.119', 'c5:2c:7c:cf:8c:cd', '19.0393741', '72.8511361', 3, 2, 0, 'hellooo', 0, 1, 0, '', '2025-02-25 10:53:35.259657', 'Windows', 'Maha3114', '11', 'AMD64', 'Intel64 Family 6 Model 140 Stepping 1, GenuineIntel', 2, 4, 2995, 8269570000, 2544940000, 5724630000, 69.2, 83885000000, 4734390000, 79150600000, 5.6, '2025-02-25 10:53:35'),
(13, 'DESKTOP-LTHU7CD', '192.168.0.100', '6c:02:e0:5a:07:53', '', '', 3, 0, 0, 'hii', 0, 1, 0, '', '2025-02-25 10:53:35.259657', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-25 10:53:35');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `c_id` int(255) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_mail` varchar(255) NOT NULL,
  `c_dob` date NOT NULL,
  `c_mob` varchar(15) NOT NULL,
  `c_pass` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`c_id`, `c_name`, `c_mail`, `c_dob`, `c_mob`, `c_pass`, `reset_token`, `reset_expiry`) VALUES
(3, 'sejal', 'sejal@gmail.com', '2025-02-17', '7666840369', '$2y$10$6nbrgOufI6x2jiRvzNxOKePxwSHXBC8cmEgPuiMluUhmVW0umRCmW', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`cl_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`c_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `cl_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `c_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
