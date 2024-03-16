-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2024 at 06:36 PM
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
-- Table structure for table `astronaut`
--

DROP TABLE IF EXISTS `astronaut`;
CREATE TABLE `astronaut` (
  `astronaut_id` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `gender` varchar(8) NOT NULL,
  `country` varchar(32) NOT NULL,
  `flight_count` int(11) NOT NULL,
  `total_flight_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meteorite`
--

DROP TABLE IF EXISTS `meteorite`;
CREATE TABLE `meteorite` (
  `meteorite_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `recclass` varchar(16) NOT NULL,
  `mass` int(11) NOT NULL,
  `fall` enum('Fell','Found') NOT NULL,
  `year` year(4) DEFAULT NULL,
  `reclat` float NOT NULL,
  `reclong` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `moon`
--

INSERT INTO `moon` (`moon_id`, `name`, `planet_id`, `radius`, `density`, `magnitude`, `albedo`) VALUES
(1, 'Moon', 3, 1737.5, 3.344, -12.74, 0.12),
(2, 'Phobos', 4, 11.1, 1.872, 11.4, 0.071),
(3, 'Titan', 6, 2574.73, 1.882, 8.4, 0.2),
(4, 'Io', 5, 1821.6, 3.528, 5.02, 0.63),
(5, 'Ariel', 7, 578.9, 1.592, 13.7, 0.39),
(6, 'Triton', 8, 1353.4, 2.059, 13.54, 0.719),
(7, 'Deimos', 4, 6.2, 1.471, 12.45, 0.068),
(8, 'Europa', 5, 1560.8, 3.013, 5.29, 0.67),
(9, 'Mimas', 6, 198.2, 1.15, 12.8, 0.962),
(10, 'Umbriel', 7, 584.7, 1.459, 14.47, 0.21);

-- --------------------------------------------------------

--
-- Table structure for table `planet`
--

DROP TABLE IF EXISTS `planet`;
CREATE TABLE `planet` (
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

--
-- Dumping data for table `planet`
--

INSERT INTO `planet` (`planet_id`, `name`, `mass`, `diameter`, `density`, `gravity`, `escape_velocity`, `rotation_period`, `mission_count`, `moon_count`) VALUES
(1, 'Mercury', 0.33, 4879, 5427, 3.7, 4.3, 1407.6, 0, 0),
(2, 'Venus', 4.87, 12104, 5243, 8.9, 10.4, -5832.5, 0, 0),
(3, 'Earth', 5.97, 12756, 5514, 9.8, 11.2, 23.9, 0, 0),
(4, 'Mars', 0.642, 6792, 3933, 3.7, 5, 24.6, 0, 0),
(5, 'Jupiter', 1898, 142984, 1326, 23.1, 59.5, 9.9, 0, 0),
(6, 'Saturn', 568, 120536, 687, 9, 35.5, 10.7, 0, 0),
(7, 'Uranus', 86.8, 51118, 1271, 8.7, 21.3, -17.2, 0, 0),
(8, 'Neptune', 102, 49528, 1638, 11, 23.5, 16.1, 0, 0);

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
  `landing_date` date NOT NULL,
  `landing_longitude` varchar(16) NOT NULL,
  `landing_latitude` varchar(16) NOT NULL,
  `operational_days` int(11) DEFAULT NULL,
  `distance_travelled` float DEFAULT NULL,
  `moon_id` int(11) DEFAULT NULL,
  `planet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rover`
--

INSERT INTO `rover` (`rover_id`, `name`, `country`, `agency`, `landing_date`, `landing_longitude`, `landing_latitude`, `operational_days`, `distance_travelled`, `moon_id`, `planet_id`) VALUES
(2, 'Lunokhod 1', 'USSR', NULL, '1970-11-17', '-35.0017', '38.2378', 322, 10.5, 1, NULL),
(3, 'Lunokhod 2', 'USSR', NULL, '1971-01-15', '30.45', '25.85', 236, 39, 1, NULL),
(4, 'PrOP-M', 'USSR', NULL, '1971-11-27', '47', '-45', NULL, NULL, NULL, 4),
(5, 'Sojourner', 'United States', 'NASA', '1997-07-04', '-35.0017', '38.2378', 85, 0.1, NULL, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `astronaut`
--
ALTER TABLE `astronaut`
  ADD PRIMARY KEY (`astronaut_id`);

--
-- Indexes for table `meteorite`
--
ALTER TABLE `meteorite`
  ADD PRIMARY KEY (`meteorite_id`);

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
-- Indexes for table `planet`
--
ALTER TABLE `planet`
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
-- AUTO_INCREMENT for table `astronaut`
--
ALTER TABLE `astronaut`
  MODIFY `astronaut_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meteorite`
--
ALTER TABLE `meteorite`
  MODIFY `meteorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mission`
--
ALTER TABLE `mission`
  MODIFY `mission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moon`
--
ALTER TABLE `moon`
  MODIFY `moon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `planet`
--
ALTER TABLE `planet`
  MODIFY `planet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rocket`
--
ALTER TABLE `rocket`
  MODIFY `rocket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rover`
--
ALTER TABLE `rover`
  MODIFY `rover_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `FK_Moon_Planet_id` FOREIGN KEY (`planet_id`) REFERENCES `planet` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rover`
--
ALTER TABLE `rover`
  ADD CONSTRAINT `FK_Rover_Moon_Id` FOREIGN KEY (`moon_id`) REFERENCES `moon` (`moon_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Rover_Planet_Id` FOREIGN KEY (`planet_id`) REFERENCES `planet` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
