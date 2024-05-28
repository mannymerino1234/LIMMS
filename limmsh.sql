-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 28, 2024 at 12:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `limmsh`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int NOT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `id_number`, `name`, `password`, `type`) VALUES
(54, '2210132-1', 'manny merino', '$2y$10$/uo8QY21q0wXvW//R7122u/X1fmSRkZEvZJ1QGPRsvipt0r9VJAsm', 'admin'),
(55, '2210134-2', 'josephine', '$2y$10$ymJfwyK5jIuXgoBDY/h0f.xyYT/1qThjHJkRA8r56T6LyHhRJXonO', 'student'),
(56, '2', 'were', '$2y$10$p3X4SmutRH0X1lTE1PL8hel6weDmCDmSE7GVmBqvPJbpAy5p3qGy.', 'staff'),
(57, '123', 'merino', '$2y$10$jKnJqeC2Au1RSoK9r4u0WOJ7wNM0CWPSB7VLIfIlFXI2hUyvnxOnW', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `quantity` int DEFAULT NULL,
  `category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `image`, `name`, `description`, `quantity`, `category_id`) VALUES
(25, 'test_tube.png', 'Test Tubes', 'Test tubes are small, cylindrical containers used in labs for holding, mixing, and heating chemicals. They have a rounded bottom and an open top for easy pouring.', 9, 14),
(28, 'microscope.png', 'Microscopes', 'A microscope is a laboratory instrument used to view tiny objects that are too small to be seen with the naked eye. It magnifies the image of the object, making details visible', 10, 17),
(29, 'Spectro-UV1_G.jpg', 'Spectrophotometers', 'A spectrophotometer is a scientific instrument used to measure the amount of light that a sample absorbs or transmits at different wavelengths.', 10, 17),
(30, 'beaker.jpg', 'Beakers', 'a beaker (also becker or beker) is generally a cylindrical container with a flat bottom.', 8, 14),
(31, 'Erlenmeyer Flasks.jpg', 'Erlenmeyer Flasks', 'contain liquids and for mixing, heating, cooling, incubation, filtration, storage, and other liquid-handling processes', 9, 14),
(32, 'Volumetric Flasks.jpg', 'Volumetric Flasks', 'A volumetric flask is a flat bottomed bulb with a elongated neck calibrated to hold a set volume at a mark on the neck.', 9, 14),
(33, 'Petri Dishes.jpg', 'Petri Dishes', 'A petri dish is a flat, shallow dish made of glass or plastic with a suitable lid. A petri dish is used to culture different types of cells, including bacteria and molds.', 10, 14),
(34, 'Burettes.jpg', 'Burettes', 'a graduated glass tube with a tap at one end, for delivering known volumes of a liquid, especially in titrations.', 10, 14),
(35, 'Balances.jpg', 'Balances', 'Laboratory balances are used to accurately determine the mass or weight of an item or substance within a specific weight range and to a particular readability.', 10, 15),
(36, 'pH Meters.jpg', 'pH Meters', 'A pH meter is an instrument used to measure hydrogen ion activity in solutions - in other words, this instrument measures acidity/alkalinity of a solution.', 10, 15),
(37, 'Thermometers.jpg', 'Thermometers', 'A laboratory thermometer is an instrument used to measure temperature.', 10, 15),
(38, 'Calipers and Micrometers.jpg', 'Calipers and Micrometers', 'Both calipers and micrometers are precision measuring tools, but they\'re ultimately very different.', 10, 15),
(39, 'Hot Plates.jpg', 'Hot Plates', 'Hot plates are frequently used in the laboratory to perform chemical reactions, to heat samples, and for numerous other activities. Hot plates are conceptually simple – a flat surface with heating elements.', 10, 16),
(40, 'Bunsen Burners.jpg', 'Bunsen Burners', 'A Bunsen burner is a type of gas burner that is used in many chemistry procedures in a laboratory setting.', 10, 16),
(41, 'Telescopes.jpg', 'Telescopes', 'telescope, device used to form magnified images of distant objects. The telescope is undoubtedly the most important investigative tool in astronomy.', 10, 17),
(42, 'Refractometers.jpg', 'Refractometers', 'A refractometer is a piece of laboratory apparatus used for measuring the refractive index of gases, liquids, and translucent solids.', 10, 17),
(43, 'Muffle Furnaces.jpg', 'Muffle Furnaces', 'A muffle furnace is a laboratory instrument used to heat materials to extremely high temperatures whilst isolating them from fuel and the byproducts of combustion from the heat source.', 10, 16),
(44, 'Incubators.jpg', 'Incubators', 'A laboratory incubator is a heated, insulated box used to grow and maintain microbiological or cell cultures. ', 10, 16);

-- --------------------------------------------------------

--
-- Table structure for table `items_category`
--

CREATE TABLE `items_category` (
  `category_id` int NOT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items_category`
--

INSERT INTO `items_category` (`category_id`, `category_name`) VALUES
(14, 'Glassware'),
(15, 'Measuring Instruments'),
(16, 'Heating Equipment'),
(17, 'Optical Equipment');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int NOT NULL,
  `item_id` int DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `borrow_time` time NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `item_id`, `account_id`, `quantity`, `borrow_date`, `borrow_time`, `return_date`, `status`) VALUES
(55, 25, 55, 1, '2024-05-27', '03:49:32', '2024-05-27', 'Returned'),
(56, 32, 55, 1, '2024-05-27', '04:57:34', NULL, 'Cancelled Request'),
(57, 40, 55, 1, '2024-05-27', '20:31:56', NULL, 'Cancelled Request'),
(58, 31, 55, 1, '2024-05-27', '20:33:25', NULL, 'pending request'),
(59, 25, 55, 8, '2024-05-27', '20:50:14', NULL, 'Cancelled Request'),
(60, 30, 55, 1, '2024-05-27', '22:26:01', NULL, 'pending request'),
(61, 30, 57, 1, '2024-05-27', '22:29:42', '2024-05-27', 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `history_id` int NOT NULL,
  `description` text,
  `date` date DEFAULT NULL,
  `account_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`history_id`, `description`, `date`, `account_id`) VALUES
(60, 'Borrow item: Test Tubes', '2024-05-27', 55),
(61, 'Item Test Tubes returned successfully by account ID number 2210134-2', '2024-05-27', 54),
(62, 'Borrow item: Volumetric Flasks', '2024-05-27', 55),
(63, 'Borrow item: Bunsen Burners', '2024-05-27', 55),
(64, 'Borrow item: Erlenmeyer Flasks', '2024-05-27', 55),
(65, 'Borrow item: Test Tubes', '2024-05-27', 55),
(66, 'Borrow item: Beakers', '2024-05-27', 55),
(67, 'Borrow item: Beakers', '2024-05-27', 57),
(68, 'Item Beakers returned successfully by account ID number 123', '2024-05-27', 54);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `items_category`
--
ALTER TABLE `items_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `account_id` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `items_category`
--
ALTER TABLE `items_category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `items_category` (`category_id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
