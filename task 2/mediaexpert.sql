-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Mar 2024, 07:44
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `mediaexpert`
--

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_status_history` (IN `object_id` INT, IN `old_status` VARCHAR(255))   BEGIN
    INSERT INTO status_history(object_id, status, created_at)
    VALUES (object_id, old_status, NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `objects`
--

CREATE TABLE `objects` (
  `id` int(11) NOT NULL,
  `number` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `status` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `objects`
--

INSERT INTO `objects` (`id`, `number`, `status`, `created_at`) VALUES
(2, '12345', 'inactive', '2024-03-20 07:01:43'),
(3, '12345', 'inactive', '2024-03-20 07:01:44');

--
-- Wyzwalacze `objects`
--
DELIMITER $$
CREATE TRIGGER `before_objects_update` BEFORE UPDATE ON `objects` FOR EACH ROW BEGIN
    CALL insert_status_history(OLD.id, OLD.status);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `status_history`
--

CREATE TABLE `status_history` (
  `id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `status` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `status_history`
--

INSERT INTO `status_history` (`id`, `object_id`, `status`, `created_at`) VALUES
(4, 2, 'active', '2024-03-20 07:01:43'),
(5, 3, 'active', '2024-03-20 07:01:44');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `objects`
--
ALTER TABLE `objects`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `status_history`
--
ALTER TABLE `status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_object_id` (`object_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `objects`
--
ALTER TABLE `objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `status_history`
--
ALTER TABLE `status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `status_history`
--
ALTER TABLE `status_history`
  ADD CONSTRAINT `fk_object_id` FOREIGN KEY (`object_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
