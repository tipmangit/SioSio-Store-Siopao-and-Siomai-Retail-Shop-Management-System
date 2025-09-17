-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 12:00 PM
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

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;