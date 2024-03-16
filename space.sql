-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2024 at 11:05 PM
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
-- Database: `space`
--
CREATE DATABASE IF NOT EXISTS `space` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `space`;

-- --------------------------------------------------------

--
-- Table structure for table `mission`
--

DROP TABLE IF EXISTS `mission`;
CREATE TABLE `mission` (
  `mission_id` int(11) NOT NULL,
  `astronaut_count` int(11) NOT NULL,
  `launch_location` varchar(128) NOT NULL,
  `launch_date` datetime NOT NULL,
  `status` enum('Success','Failure','Partial Failure','Prelaunch Failure') NOT NULL,
  `rocket_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mission_rover`
--

DROP TABLE IF EXISTS `mission_rover`;
CREATE TABLE `mission_rover` (
  `mission_id` int(11) NOT NULL,
  `rover_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moon`
--

DROP TABLE IF EXISTS `moon`;
CREATE TABLE `moon` (
  `moon_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `planet_id` int(11) NOT NULL,
  `radius` float NOT NULL,
  `density` float NOT NULL,
  `magnitude` float NOT NULL,
  `albedo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planets`
--

DROP TABLE IF EXISTS `planets`;
CREATE TABLE `planets` (
  `planet_id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `mass` float NOT NULL,
  `diameter` int(11) NOT NULL,
  `density` int(11) NOT NULL,
  `gravity` float NOT NULL,
  `escape_velocity` float NOT NULL,
  `rotation_period` float NOT NULL,
  `mission_count` int(11) NOT NULL,
  `moon_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rocket`
--

DROP TABLE IF EXISTS `rocket`;
CREATE TABLE `rocket` (
  `rocket_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `company` varchar(32) NOT NULL,
  `status` enum('Retired','Active','Planned') NOT NULL,
  `liftoff_thrust` int(11) NOT NULL,
  `payload_to_leo` float NOT NULL,
  `stages` int(11) NOT NULL,
  `side_strap_count` int(11) NOT NULL,
  `rocket_height` float DEFAULT NULL,
  `cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rover`
--

DROP TABLE IF EXISTS `rover`;
CREATE TABLE `rover` (
  `rover_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `country` varchar(64) NOT NULL,
  `agency` varchar(16) DEFAULT NULL,
  `landing_date` int(11) NOT NULL,
  `landing_longitude` varchar(16) NOT NULL,
  `landing_latitude` varchar(16) NOT NULL,
  `operational_days` int(11) NOT NULL,
  `distance_travelled` float NOT NULL,
  `moon_id` int(11) NOT NULL,
  `planet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`mission_id`),
  ADD KEY `MISSION_ROCKET_ID_FK` (`rocket_id`);

--
-- Indexes for table `mission_rover`
--
ALTER TABLE `mission_rover`
  ADD PRIMARY KEY (`rover_id`,`mission_id`),
  ADD KEY `MISSION_ROVER_MISSION_ID_FK` (`mission_id`);

--
-- Indexes for table `moon`
--
ALTER TABLE `moon`
  ADD PRIMARY KEY (`moon_id`),
  ADD KEY `FK_Moon_Planet_id` (`planet_id`);

--
-- Indexes for table `planets`
--
ALTER TABLE `planets`
  ADD PRIMARY KEY (`planet_id`);

--
-- Indexes for table `rocket`
--
ALTER TABLE `rocket`
  ADD PRIMARY KEY (`rocket_id`);

--
-- Indexes for table `rover`
--
ALTER TABLE `rover`
  ADD PRIMARY KEY (`rover_id`),
  ADD KEY `FK_Rover_Planet_Id` (`planet_id`),
  ADD KEY `FK_Rover_Moon_Id` (`moon_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mission`
--
ALTER TABLE `mission`
  MODIFY `mission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moon`
--
ALTER TABLE `moon`
  MODIFY `moon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planets`
--
ALTER TABLE `planets`
  MODIFY `planet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rocket`
--
ALTER TABLE `rocket`
  MODIFY `rocket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rover`
--
ALTER TABLE `rover`
  MODIFY `rover_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mission`
--
ALTER TABLE `mission`
  ADD CONSTRAINT `MISSION_ROCKET_ID_FK` FOREIGN KEY (`rocket_id`) REFERENCES `rocket` (`rocket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mission_rover`
--
ALTER TABLE `mission_rover`
  ADD CONSTRAINT `MISSION_ROVER_MISSION_ID_FK` FOREIGN KEY (`mission_id`) REFERENCES `mission` (`mission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MISSION_ROVER_ROVER_ID_FK` FOREIGN KEY (`rover_id`) REFERENCES `rover` (`rover_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `moon`
--
ALTER TABLE `moon`
  ADD CONSTRAINT `FK_Moon_Planet_id` FOREIGN KEY (`planet_id`) REFERENCES `planets` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rover`
--
ALTER TABLE `rover`
  ADD CONSTRAINT `FK_Rover_Moon_Id` FOREIGN KEY (`moon_id`) REFERENCES `moon` (`moon_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Rover_Planet_Id` FOREIGN KEY (`planet_id`) REFERENCES `planets` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
