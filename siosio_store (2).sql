-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 06:43 PM
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
-- Table structure for table `cart`
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
) ;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `wishlist_id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `otp_type` enum('registration','password_reset') NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verifications`
--

INSERT INTO `otp_verifications` (`id`, `email`, `otp_code`, `otp_type`, `is_verified`, `expires_at`, `created_at`, `verified_at`) VALUES
(1, 'kokoy2milanez@gmail.com', '521314', 'registration', 0, '2025-09-24 00:01:45', '2025-09-24 05:59:45', NULL),
(2, 'kokoy2milanez@gmail.com', '542349', 'registration', 0, '2025-09-24 00:05:25', '2025-09-24 06:03:25', NULL),
(3, 'kokoy2milanez@gmail.com', '599554', 'registration', 0, '2025-09-24 00:09:18', '2025-09-24 06:07:18', NULL),
(4, 'kokoy2milanez@gmail.com', '489124', 'registration', 0, '2025-09-24 00:09:53', '2025-09-24 06:07:53', NULL),
(5, 'kokoy2milanez@gmail.com', '670652', 'registration', 1, '2025-09-24 06:10:38', '2025-09-24 06:10:15', '2025-09-24 06:10:38'),
(6, 'addtocartburat199@gmail.com', '752316', 'registration', 0, '2025-09-24 00:17:43', '2025-09-24 06:15:43', NULL),
(7, 'marcomilanez08@gmail.com', '447643', 'registration', 0, '2025-09-24 00:51:49', '2025-09-24 06:49:49', NULL),
(8, 'marcomilanez08@gmail.com', '257482', 'registration', 0, '2025-09-24 00:53:50', '2025-09-24 06:51:50', NULL),
(9, 'marcomilanez08@gmail.com', '484587', 'registration', 0, '2025-09-24 00:56:08', '2025-09-24 06:54:08', NULL),
(10, 'marcomilanez08@gmail.com', '123224', 'registration', 1, '2025-09-24 06:57:05', '2025-09-24 06:56:42', '2025-09-24 06:57:05'),
(11, '', '899168', 'registration', 0, '2025-09-24 08:23:22', '2025-09-24 08:17:22', NULL),
(12, 'makoymilanez@gmail.com', '350146', 'registration', 1, '2025-09-24 08:20:55', '2025-09-24 08:20:35', '2025-09-24 08:20:55'),
(13, 'makoymilanez@gmail.com', '169052', 'registration', 1, '2025-09-24 08:23:16', '2025-09-24 08:22:51', '2025-09-24 08:23:16'),
(14, 'makoymilanez@gmail.com', '997720', 'registration', 0, '2025-09-24 09:57:55', '2025-09-24 15:55:55', NULL),
(15, 'makoymilanez@gmail.com', '507287', 'registration', 1, '2025-09-24 15:56:48', '2025-09-24 15:56:32', '2025-09-24 15:56:48'),
(16, 'makoymilanez@gmail.com', '153559', 'registration', 1, '2025-09-24 16:01:14', '2025-09-24 15:59:46', '2025-09-24 16:01:14'),
(17, 'makoymilanez@gmail.com', '531322', 'registration', 0, '2025-09-24 10:31:15', '2025-09-24 16:29:15', NULL),
(18, 'makoymilanez@gmail.com', '176553', 'registration', 1, '2025-09-24 16:29:59', '2025-09-24 16:29:45', '2025-09-24 16:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` enum('siomai','siopao') NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `quantity`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pork Siomai', 'siomai', 'Juicy pork siomai with authentic Filipino flavors', 25.00, 150, 'https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(2, 'Chicken Siomai', 'siomai', 'Tender chicken siomai with fresh ingredients', 22.00, 200, 'https://media.istockphoto.com/id/1336438874/photo/delicious-dim-sum-home-made-chinese-dumplings-served-on-plate.jpg?s=612x612&w=0&k=20&c=11KB0bXoZeMrlzaHN2q9aZq8kqtdvp-d4Oggc2TF8M4=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(3, 'Beef Siomai', 'siomai', 'Premium beef siomai with rich savory taste', 28.00, 120, 'https://media.istockphoto.com/id/2189370578/photo/delicious-shumai-shumay-siomay-chicken-in-bowl-snack-menu.jpg?s=612x612&w=0&k=20&c=hD4kuZsiGIjgyUPq-seqv229pFE43CnS0Do3EH_2E_Y=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(4, 'Tuna Siomai', 'siomai', 'Fresh tuna siomai with ocean-fresh flavor', 30.00, 100, 'https://media.istockphoto.com/id/1084916088/photo/close-up-cooking-homemade-shumai.jpg?s=612x612&w=0&k=20&c=M1RyWV62MACQffBC40UzZ_h-BsXOj4bkaMBrxnbMTzc=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(5, 'Shark\'s Fin Siomai', 'siomai', 'Premium shark\'s fin siomai with delicate texture', 35.00, 80, 'https://media.istockphoto.com/id/1330456626/photo/steamed-shark-fin-dumplings-served-with-chili-garlic-oil-and-calamansi.jpg?s=612x612&w=0&k=20&c=9Zi1JmbwvYtIlZJqZb6tHOVC21rS-IbwZXS-IeflE30=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(6, 'Japanese Siomai', 'siomai', 'Japanese-style siomai with nori wrapping', 32.00, 90, 'https://media.istockphoto.com/id/1221287744/photo/ground-pork-with-crab-stick-wrapped-in-nori.jpg?s=612x612&w=0&k=20&c=Rniq7tdyCqVZHpwngsbzOk1dG1u8pTEeUDE8arsfOUY=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(7, 'Asado Siopao', 'siopao', 'Classic asado siopao with sweet-savory pork filling', 45.00, 75, 'https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(8, 'Bola-Bola Siopao', 'siopao', 'Hearty bola-bola siopao with meatball filling', 42.00, 85, 'https://media.istockphoto.com/id/1184080523/photo/wanton-noodle-soup-and-siopao.jpg?s=612x612&w=0&k=20&c=oRJanjrTxICQfuzm9bXVPYkw9nKh74tcwjH1cVzXzN8=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(9, 'Choco Siopao', 'siopao', 'Sweet chocolate-filled siopao for dessert lovers', 38.00, 110, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxSCl2zlIK85vMZ6nRYuWpqde6JnIxBUTe-w&s', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00'),
(10, 'Ube Siopao', 'siopao', 'Filipino ube-flavored siopao with purple yam', 40.00, 95, 'https://media.istockphoto.com/id/2161276374/photo/vivid-steamed-purple-ube-sweet-potato-dumplings.jpg?s=612x612&w=0&k=20&c=Mb2rl1JZPvG0d5v-_gSC7Mx50DNggFJiTEcoTayqB1Q=', 'active', '2025-09-18 04:00:00', '2025-09-18 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `userss`
--

CREATE TABLE `userss` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `fname` varchar(150) NOT NULL,
  `mname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userss`
--

INSERT INTO `userss` (`id`, `name`, `fname`, `mname`, `lname`, `username`, `email`, `password`, `created_at`, `shipping_address`, `billing_address`, `profile_photo`, `phone`, `email_verified`) VALUES
(13, 'Marco Delos Santos Milanez', '', '', '', 'marcomilanez', 'makoymilanez@gmail.com', '$2y$10$z/VD3SXfUPIK.v/jiGa0m.FZUEi7ppIeuwBqQsXfPpknU5xUmws0.', '2025-09-24 16:29:59', NULL, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `fk_favorites_user` (`user_id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_idx` (`email`),
  ADD KEY `expires_at_idx` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_idx` (`category`),
  ADD KEY `status_idx` (`status`),
  ADD KEY `price_idx` (`price`),
  ADD KEY `quantity_idx` (`quantity`);

--
-- Indexes for table `userss`
--
ALTER TABLE `userss`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `wishlist_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `userss`
--
ALTER TABLE `userss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_user_fk` FOREIGN KEY (`user_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
