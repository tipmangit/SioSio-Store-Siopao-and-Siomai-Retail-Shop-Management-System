-- phpMyAdmin SQL Dump - Updated Cart Table with User Names
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 01:00 PM
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
-- Database: `siosio_store`
--

-- --------------------------------------------------------

--
-- Drop existing cart table if it exists
--

DROP TABLE IF EXISTS `cart`;

-- --------------------------------------------------------

--
-- Table structure for table `cart` (Updated with User Names)
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(150) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price_at_time` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price_at_time`) STORED,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `user_name_idx` (`user_name`),
  ADD KEY `session_id_idx` (`session_id`),
  ADD KEY `product_id_idx` (`product_id`),
  ADD KEY `product_name_idx` (`product_name`),
  ADD KEY `status_idx` (`status`),
  ADD KEY `created_at_idx` (`created_at`);

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_user_fk` FOREIGN KEY (`user_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_user_or_session_check` CHECK ((`user_id` IS NOT NULL) OR (`session_id` IS NOT NULL));

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------
-- SAMPLE DATA VIEW
-- --------------------------------------------------------

-- After adding items, your cart table will look like this:
-- +----+---------+---------------------------+------------+------------+---------------+----------+---------------+-------------+--------+---------------------+---------------------+
-- | id | user_id | user_name                 | session_id | product_id | product_name  | quantity | price_at_time | total_price | status | created_at          | updated_at          |
-- +----+---------+---------------------------+------------+------------+---------------+----------+---------------+-------------+--------+---------------------+---------------------+
-- |  1 |       1 | clyde emerson estrella salvador | NULL       |          1 | Pork Siomai   |        2 |         25.00 |       50.00 | active | 2025-09-18 05:00:00 | 2025-09-18 05:00:00 |
-- |  2 |    NULL | NULL                      | abc123def  |          7 | Asado Siopao  |        1 |         45.00 |       45.00 | active | 2025-09-18 05:15:00 | 2025-09-18 05:15:00 |
-- +----+---------+---------------------------+------------+------------+---------------+----------+---------------+-------------+--------+---------------------+---------------------+

-- --------------------------------------------------------
-- USEFUL QUERIES
-- --------------------------------------------------------

-- View all items for a logged-in user (shows user name instead of ID)
-- SELECT c.id, c.user_name, c.product_name, c.quantity, c.price_at_time, c.total_price, c.created_at
-- FROM cart c
-- WHERE c.user_id = ? AND c.status = 'active'
-- ORDER BY c.created_at DESC;

-- View all items for a guest user
-- SELECT c.id, c.session_id, c.product_name, c.quantity, c.price_at_time, c.total_price, c.created_at
-- FROM cart c
-- WHERE c.session_id = ? AND c.status = 'active'
-- ORDER BY c.created_at DESC;