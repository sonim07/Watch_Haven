-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2024 at 06:29 AM
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
-- Database: `watch_haven`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'sonim', '$2y$10$Bx6MIjIhPqKcx8UCVpaanuJbjT9QBPFGu1OtE02XrzTrgDAjbzzG2', '2024-07-10 10:28:48'),
(2, 'new', '$2y$10$Dc9Z1koiSMHAePpldKFfs.kncpINsi8yaA8fpXMJIpuvyAvrU8Ehi', '2024-07-10 10:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`) VALUES
(7, 6, 2, 1, 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Sonim Stha', 'sonimstha@gmail.com', '', 'Hello', '2024-07-10 15:11:23'),
(2, 'Sonim Stha', 'sonimstha@gmail.com', '', 'Hello', '2024-07-10 15:12:25'),
(3, 'Sonim Stha', 'sonimstha@gmail.com', '', 'Hello', '2024-07-10 17:31:34'),
(4, 'Hang', 'paruhangrai8900@gmail.com', '9863667993', 'Such a Wonderful Website', '2024-07-12 09:56:32'),
(5, 'Ezon Waiba', 'paruhangrai8900@gmail.com', '9863667993', 'Nice', '2024-07-12 09:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(15) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `order_date`, `status`, `customer_address`, `customer_email`, `customer_phone`, `total_amount`, `payment_method`) VALUES
(1, 'Sonim Stha', '0000-00-00', 'Confirmed', 'hhhh', 'sonimstha@gmail.com', NULL, 0.00, ''),
(2, 'Ronald Rai', '0000-00-00', 'Delivered', 'kkk', 'acharyabasanta36@gmail.com', NULL, 0.00, ''),
(3, 'Ronald Rai', '0000-00-00', '', 'kkk', 'acharyabasanta36@gmail.com', NULL, 0.00, ''),
(4, 'Sonim Stha', '0000-00-00', '', 'fadfa', 'sonimstha@gmail.com', NULL, 0.00, ''),
(5, 'Ezon Waiba', '0000-00-00', '', 'dadas', 'waibaezon@gmail.com', NULL, 0.00, ''),
(6, 'Ezon Waiba', '0000-00-00', '', 'lkmk', 'waibaezon@gmail.com', NULL, 500.00, ''),
(7, 'Ezon Waiba', '0000-00-00', '', 'lkmk', 'waibaezon@gmail.com', NULL, 0.00, ''),
(8, 'Sonim Stha', '0000-00-00', '', 'dadsda', 'sonimstha@gmail.com', NULL, 500.00, ''),
(9, 'Sonim Stha', '0000-00-00', '', 'sdasdad', 'sonimstha@gmail.com', NULL, 500.00, ''),
(10, 'Ezon Waiba', '0000-00-00', '', 'juuj', 'waibaezon@gmail.com', '9863667993', 500.00, ''),
(11, 'Ezon Waiba', '0000-00-00', '', 'dasdasdaw', 'waibaezon@gmail.com', '9863667993', 500.00, 'COD'),
(12, 'Selena Galden', '0000-00-00', '', 'dasndas', 'selegalden@gmail.com', '9863667993', 1000.00, 'COD'),
(13, 'Ezon Waiba', '0000-00-00', '', 'gfufuf', 'sonimstha@gmail.com', '9863667993', 400.00, 'COD'),
(14, 'Nelson Rai', '0000-00-00', '', 'Kathmandu', 'nelsonrai@gmail.com', '9863667993', 400.00, 'COD'),
(15, 'Ronald Rai', '0000-00-00', '', 'Dharan', 'waibaezon@gmail.com', '9863667993', 8000.00, 'COD'),
(16, 'Nelson Rai', '0000-00-00', '', 'Kathmandu', 'sonimstha@gmail.com', '9863667993', 1500.00, 'COD'),
(17, 'Selena Galden', '0000-00-00', '', 'Kathmandu', 'selegalden@gmail.com', '9863667993', 500.00, 'COD'),
(18, 'Ozil', '0000-00-00', '', 'Dharan', 'sonimstha@gmail.com', '9863667993', 500.00, 'COD'),
(19, 'Nelson Rai', '2024-07-11', '', 'Kathmandu', 'sonimstha@gmail.com', '9863667993', 500.00, 'COD'),
(20, 'Sonim Stha', '2024-07-11', '', 'Kathamndu', 'sonimstha@gmail.com', '9863667993', 99999999.99, 'COD'),
(21, 'Nelson Rai', '2024-07-12', 'Delivered', 'Kathmandu, Baneshwor', 'rainelson250@gmail.com', '9823304046', 500.00, 'COD'),
(22, 'Hang', '2024-07-12', '', 'Dharan', 'paruhangrai8900@gmail.com', '9863667993', 1000000.00, 'COD'),
(23, 'Hang', '2024-07-12', '', 'Dharan', 'paruhangrai8900@gmail.com', '9863667993', 2500000.00, 'COD'),
(24, 'Ezon Waiba', '2024-07-12', '', 'Dharan', 'paruhangrai8900@gmail.com', '9863667993', 500.00, 'COD'),
(25, 'Ronald Rai', '2024-07-12', '', 'Dharan', 'paruhangrai8900@gmail.com', '9863667993', 200.00, 'COD'),
(26, 'Sonim Stha', '2024-07-12', '', 'Kathmandu', 'paruhangrai8900@gmail.com', '9863667993', 500.00, 'COD'),
(27, 'Hang', '2024-07-12', '', 'Dharan', 'paruhangrai8900@gmail.com', '9863667993', 500.00, 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `password_history`
--

CREATE TABLE `password_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_history`
--

INSERT INTO `password_history` (`id`, `user_id`, `password`, `created_at`) VALUES
(1, 5, '$2y$10$6NDrbcGSuiOBrjb4Ye43G.DjYLNfFetPzX4K1ZQdGx3yEHgyzNwaC', '2024-08-16 18:22:19'),
(2, 6, '$2y$10$Gvm9JzThl/BaEspj.cHzt.Y4niuPWYkrSWqkJJwCa8TqLi7kvc8PK', '2024-08-17 04:21:55'),
(3, 1, '$2y$10$XLvugfWDBFl/E81OTP8.jezENsLufTAUoO6IU71MTWzo2w9Mo/.WO', '2024-07-10 08:40:30'),
(4, 1, '$2y$10$6NDrbcGSuiOBrjb4Ye43G.DjYLNfFetPzX4K1ZQdGx3yEHgyzNwaC', '2024-08-16 12:37:19'),
(5, 2, '$2y$10$gWu9vWSvJBndxWDzEtRt9.Sv7SAJRo1EfBSSlidwH3p2B2pspY4MW', '2024-07-11 01:18:06'),
(6, 3, '$2y$10$UgO07hLro0KxiuJ4VBGqreJ2UiDEIxGjzd9EZGOT76SyScbSXUt1u', '2024-07-12 03:15:05'),
(7, 5, '$2y$10$6NDrbcGSuiOBrjb4Ye43G.DjYLNfFetPzX4K1ZQdGx3yEHgyzNwaC', '2024-08-16 12:37:19'),
(8, 6, '$2y$10$Bx6MIjIhPqKcx8UCVpaanuJbjT9QBPFGu1OtE02XrzTrgDAjbzzG2', '2024-08-16 22:36:55');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` text NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_description`, `product_price`, `product_image`) VALUES
(2, 'Guniess watch', 'Watch', 400.00, 'https://img.freepik.com/free-photo/gray-scale-shot-black-watch_181624-422.jpg'),
(3, 'Luxury Watch', 'Watch', 500.00, 'https://c4.wallpaperflare.com/wallpaper/437/433/208/watch-luxury-watches-rolex-wallpaper-preview.jpg'),
(4, 'Unique Watch', 'Watch', 1000.00, 'https://c4.wallpaperflare.com/wallpaper/729/338/570/watch-luxury-watches-ulysse-nardin-wallpaper-preview.jpg'),
(5, 'Classic Watch', 'Watch', 6000.00, 'https://static01.nyt.com/images/2023/09/25/multimedia/25sp-watches-photos-inyt-01-mgjb/25sp-watches-photos-inyt-01-mgjb-articleLarge.jpg?quality=75&auto=webp&disable=upscale'),
(6, 'Women Watch', 'Watch', 200.00, 'https://t3.ftcdn.net/jpg/04/40/10/78/360_F_440107854_2ys84w9PiDP4Tc8wOoAty94OCtz7ZiTR.jpg'),
(7, 'Couple Watch', 'Watch', 8000.00, 'https://guesswatches.com/cdn/shop/files/GW_Home_Carousel_Squares_1200x1200_SP24_NR2.png?v=1720182046&width=768'),
(8, 'Patek Phillippe Nautils 7010R-013', 'Watch', 1000000.00, 'https://cdn.thewatchpages.com/app/uploads/2023/11/14102120/patek-philippe-nautilus-ladies-7010r-013-7-scaled.jpeg'),
(9, 'Patek Phillippe Nautils 7118/1A-001', 'Watch', 2500000.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcEF1qIx_4E2qXhuiKJKbHpK9rzs9CwKHUDA&s'),
(10, 'Patek Phillippe Nautils 7118/1A-010', 'Watch', 4500000.00, 'https://static.patek.com/images/articles/face_white/350/7118_1A_010.jpg'),
(11, 'Patek Phillippe Nautils 7118/1A-011', 'Watch', 8520000.00, 'https://static.patek.com/images/articles/face_white/350/7118_1A_011.jpg'),
(12, 'Patek Phillippe Nautils 7118/1R-010', 'Watch', 2500000.00, 'https://www.cortinawatch.com/my/wp-content/uploads/sites/2/2023/05/Patek-Philippe_Nautilus_7118_1R_010_at-Cortina-Watch_frontal.jpg'),
(13, 'Patek Phillippe Nautils 7118/1450G-001', 'Watch', 9000000.00, 'https://static.patek.com/images/articles/face_white/350/7118_1450G_001_001.jpg'),
(14, 'Patek Phillippe Nautils 7118/1451G-001', 'Watch', 11000000.00, 'https://static.patek.com/images/articles/face_white/350/7118_1451G_001_1.jpg'),
(15, 'Patek Phillippe Nautils 7118/1452G-001', 'Watch', 12000000.00, 'https://static.patek.com/images/articles/face_white/350/7118_1452G_001_1.jpg'),
(16, 'Patek Phillippe Nautils 7118/1453G-001', 'Watch', 99999999.99, 'https://static.patek.com/images/articles/face_white/350/7118_1453G_001_1.jpg'),
(17, 'Patek Phillippe Nautils 5811/1G-001', 'Watch', 15000000.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoARC3FplJb5zGvWxeZ3fDes4uLeU-_mgdYQ&s'),
(18, 'Patek Phillippe Nautils 5712/1A-001', 'Watch', 1400000.00, 'https://static.patek.com/images/articles/face_white/350/5712_1A_001_1.jpg'),
(19, 'Patek Phillippe Nautils 5726A-001', 'Watch', 4000000.00, 'https://static.patek.com/images/articles/face_white/350/5726A_001.jpg'),
(20, '5980/60G-001', 'Watch', 5000000.00, 'https://static.patek.com/images/articles/face_white/350/5980_60G_001_1.jpg'),
(24, '5930P-001', 'Watch', 9000000.00, 'https://static.patek.com/images/articles/face_white/350/5930P_001_1.jpg'),
(26, '7130R-014', 'Watch', 9000000.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT21A-ufuJNMs2EtlJ0cynyJSFQwTwA0ASsLA&s');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `review` text NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `customer_name`, `review`, `rating`, `created_at`) VALUES
(1, 2, 'Sonim Shrestha', 'Such a Wonderful Watch', 5, '2024-07-11 05:12:53'),
(5, 2, 'Ezon Waiba', 'Wonderful', 5, '2024-07-11 05:59:30'),
(8, 2, 'Ronald Rai', 'Awesome', 5, '2024-07-11 06:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `account_locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `created_at`, `profile_image`, `name`, `last_login`, `otp`, `failed_login_attempts`, `last_failed_login`, `account_locked_until`) VALUES
(1, 'sonim', 'sonimstha@gmail.com', '$2y$10$XLvugfWDBFl/E81OTP8.jezENsLufTAUoO6IU71MTWzo2w9Mo/.WO', '9863667993', '2024-07-10 14:25:30', NULL, '', '2024-07-13 12:10:46', NULL, 1, '2024-08-17 00:25:23', NULL),
(2, 'hang', 'paruhangrai8900@gmail.com', '$2y$10$gWu9vWSvJBndxWDzEtRt9.Sv7SAJRo1EfBSSlidwH3p2B2pspY4MW', '', '2024-07-11 07:03:06', NULL, '', '2024-07-12 15:28:40', NULL, 0, NULL, NULL),
(3, 'nelson', 'rainelson250@gmail.com', '$2y$10$UgO07hLro0KxiuJ4VBGqreJ2UiDEIxGjzd9EZGOT76SyScbSXUt1u', NULL, '2024-07-12 09:00:05', NULL, NULL, '2024-07-12 14:45:16', NULL, 0, NULL, NULL),
(5, 'hello', 'hello@gmail.com', '$2y$10$6NDrbcGSuiOBrjb4Ye43G.DjYLNfFetPzX4K1ZQdGx3yEHgyzNwaC', NULL, '2024-08-16 18:22:19', NULL, NULL, '2024-08-17 00:23:33', NULL, 5, '2024-08-17 00:24:05', '2024-08-16 20:54:05'),
(6, 'test', 'test@gmail.com', '$2y$10$Bx6MIjIhPqKcx8UCVpaanuJbjT9QBPFGu1OtE02XrzTrgDAjbzzG2', NULL, '2024-08-17 04:21:55', NULL, NULL, '2024-08-17 10:07:53', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `watches`
--

CREATE TABLE `watches` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watches`
--

INSERT INTO `watches` (`id`, `name`, `price`, `image`) VALUES
(1, 'Elegant Watch', 250.00, 'watch1.jpg'),
(2, 'Classic Watch', 300.00, 'watch2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_history`
--
ALTER TABLE `password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watches`
--
ALTER TABLE `watches`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `password_history`
--
ALTER TABLE `password_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `watches`
--
ALTER TABLE `watches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `password_history`
--
ALTER TABLE `password_history`
  ADD CONSTRAINT `password_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
