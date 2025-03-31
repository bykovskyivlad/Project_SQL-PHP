-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Січ 26 2025 р., 22:43
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `poczta`
--

-- --------------------------------------------------------

--
-- Структура таблиці `courier`
--

CREATE TABLE `courier` (
  `courier_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Дамп даних таблиці `courier`
--

INSERT INTO `courier` (`courier_id`, `first_name`, `last_name`, `contact_number`, `department_id`) VALUES
(1, 'Kamil', 'Wiśniewski', '777-888-999', 1),
(2, 'Ramil', 'Piwowarczyk', '777-845-999', 2),
(3, 'Makar', 'Piwowarczyk', '707-845-699', 2);

-- --------------------------------------------------------

--
-- Структура таблиці `postal_department`
--

CREATE TABLE `postal_department` (
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Дамп даних таблиці `postal_department`
--

INSERT INTO `postal_department` (`department_id`, `name`, `location`, `contact_number`) VALUES
(1, 'Centrum Warszawa', 'Warszawa', '123-456-789'),
(2, 'Centrum Poznania', 'Poznan', '166-499-799');

-- --------------------------------------------------------

--
-- Структура таблиці `recipient`
--

CREATE TABLE `recipient` (
  `recipient_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Дамп даних таблиці `recipient`
--

INSERT INTO `recipient` (`recipient_id`, `first_name`, `last_name`, `street`, `city`, `postal_code`, `contact_number`) VALUES
(1, 'Anna', 'Nowak', 'Odbiorcza ', 'Kraków', '30-002', '444-555-666'),
(4, 'Marcin', 'Nowak', 'Mickiewicza', 'Poznań', '60-600', '393-939-393');

-- --------------------------------------------------------

--
-- Структура таблиці `sender`
--

CREATE TABLE `sender` (
  `sender_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Дамп даних таблиці `sender`
--

INSERT INTO `sender` (`sender_id`, `first_name`, `last_name`, `street`, `city`, `postal_code`, `contact_number`) VALUES
(1, 'Jan', 'Kowalski', 'Nadawcza ', 'Warszawa', '23-322', '111-222-333'),
(2, 'Janusz', 'Torbek', 'Wojskowa', 'Poznan', '12-122', '121-221-212'),
(3, 'Jan', 'Torbek', 'Wojska Polskiego', 'Poznan', '12-999', '121-221-606');

-- --------------------------------------------------------

--
-- Структура таблиці `shipment`
--

CREATE TABLE `shipment` (
  `shipment_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `courier_id` int(11) DEFAULT NULL,
  `current_status` varchar(50) DEFAULT 'Nadana',
  `current_location` varchar(255) DEFAULT NULL,
  `status_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `shipment_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `tracking_number` varchar(50) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Дамп даних таблиці `shipment`
--

INSERT INTO `shipment` (`shipment_id`, `sender_id`, `recipient_id`, `department_id`, `courier_id`, `current_status`, `current_location`, `status_updated_at`, `shipment_date`, `expected_delivery_date`, `delivery_date`, `tracking_number`, `comment`) VALUES
(1, 1, 1, 1, 1, 'Dostarczona', 'Centrum Logistyczne', '2025-01-26 20:07:18', '2025-01-08', '2025-01-11', '2025-01-11', '12345', 'Dostarczona'),
(3, 1, 1, 2, 2, 'Dostarczona', 'Centrum Logistyczne', '2025-01-26 20:07:08', '2025-01-14', '2025-01-18', '2025-01-26', '12646', 'Dostarczona'),
(5, 1, 4, 2, 2, 'Nadana', 'Punkt nadawczy', '2025-01-25 23:00:00', '2025-01-26', '2025-01-30', '2025-01-31', '12222', 'Nadana');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `courier`
--
ALTER TABLE `courier`
  ADD PRIMARY KEY (`courier_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Індекси таблиці `postal_department`
--
ALTER TABLE `postal_department`
  ADD PRIMARY KEY (`department_id`);

--
-- Індекси таблиці `recipient`
--
ALTER TABLE `recipient`
  ADD PRIMARY KEY (`recipient_id`);

--
-- Індекси таблиці `sender`
--
ALTER TABLE `sender`
  ADD PRIMARY KEY (`sender_id`);

--
-- Індекси таблиці `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipment_id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `courier_id` (`courier_id`),
  ADD KEY `idx_tracking_number` (`tracking_number`),
  ADD KEY `idx_status_updated_at` (`status_updated_at`),
  ADD KEY `idx_current_status` (`current_status`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `courier`
--
ALTER TABLE `courier`
  MODIFY `courier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблиці `postal_department`
--
ALTER TABLE `postal_department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблиці `recipient`
--
ALTER TABLE `recipient`
  MODIFY `recipient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблиці `sender`
--
ALTER TABLE `sender`
  MODIFY `sender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблиці `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `courier`
--
ALTER TABLE `courier`
  ADD CONSTRAINT `courier_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `postal_department` (`department_id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `sender` (`sender_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `recipient` (`recipient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipment_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `postal_department` (`department_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipment_ibfk_4` FOREIGN KEY (`courier_id`) REFERENCES `courier` (`courier_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
