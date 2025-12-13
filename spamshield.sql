-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 02:41 PM
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
-- Database: `spamshield`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_spam` tinyint(1) NOT NULL DEFAULT 0,
  `spam_probability` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `confidence` varchar(20) NOT NULL DEFAULT 'low',
  `indicators` text DEFAULT NULL,
  `analyzed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_data`
--

CREATE TABLE `training_data` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_spam` tinyint(1) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_data`
--

INSERT INTO `training_data` (`id`, `content`, `is_spam`, `added_at`) VALUES
(1, 'Congratulations! You have won a free iPhone! Click here to claim your prize now!', 1, '2025-12-09 07:52:04'),
(2, 'URGENT: Your account has been compromised. Verify your password immediately!', 1, '2025-12-09 07:52:04'),
(3, 'Make money fast! Work from home and earn $5000 per week guaranteed!', 1, '2025-12-09 07:52:04'),
(4, 'You have been selected for a special offer. Act now and get 90% off!', 1, '2025-12-09 07:52:04'),
(5, 'FREE VIAGRA! Best prices online. Order now and save big!', 1, '2025-12-09 07:52:04'),
(6, 'Your lottery ticket has won! Send us your bank details to receive $1,000,000', 1, '2025-12-09 07:52:04'),
(7, 'Hot singles in your area want to meet you tonight! Click here!', 1, '2025-12-09 07:52:04'),
(8, 'Limited time offer! Buy now and get free shipping worldwide!', 1, '2025-12-09 07:52:04'),
(9, 'You are our lucky winner! Claim your prize before it expires!', 1, '2025-12-09 07:52:04'),
(10, 'Increase your credit score instantly! Guaranteed approval!', 1, '2025-12-09 07:52:04'),
(11, 'Nigerian prince needs your help transferring $10 million dollars', 1, '2025-12-09 07:52:04'),
(12, 'Act now! This offer expires in 24 hours! Dont miss out!', 1, '2025-12-09 07:52:04'),
(13, 'Cheap medications online! No prescription needed!', 1, '2025-12-09 07:52:04'),
(14, 'Double your bitcoin investment in just 24 hours guaranteed!', 1, '2025-12-09 07:52:04'),
(15, 'Weight loss miracle! Lose 30 pounds in 30 days!', 1, '2025-12-09 07:52:04'),
(16, 'Hi John, can we schedule a meeting for tomorrow at 3pm to discuss the project?', 0, '2025-12-09 07:52:04'),
(17, 'Thank you for your order. Your package will arrive within 3-5 business days.', 0, '2025-12-09 07:52:04'),
(18, 'Reminder: Your dentist appointment is scheduled for Monday at 10am.', 0, '2025-12-09 07:52:04'),
(19, 'The quarterly report is ready for review. Please find it attached.', 0, '2025-12-09 07:52:04'),
(20, 'Happy birthday! Hope you have a wonderful day with family and friends.', 0, '2025-12-09 07:52:04'),
(21, 'Can you please send me the updated spreadsheet when you have a chance?', 0, '2025-12-09 07:52:04'),
(22, 'The team meeting has been moved to Thursday at 2pm in conference room B.', 0, '2025-12-09 07:52:04'),
(23, 'Your subscription will renew automatically on the 15th of next month.', 0, '2025-12-09 07:52:04'),
(24, 'I wanted to follow up on our conversation from last week about the proposal.', 0, '2025-12-09 07:52:04'),
(25, 'Please review the attached document and let me know your thoughts.', 0, '2025-12-09 07:52:04'),
(26, 'The weather forecast shows rain tomorrow, so bring an umbrella.', 0, '2025-12-09 07:52:04'),
(27, 'Your flight confirmation for next Tuesday has been processed.', 0, '2025-12-09 07:52:04'),
(28, 'Looking forward to seeing you at the conference next week.', 0, '2025-12-09 07:52:04'),
(29, 'The invoice for your recent purchase is attached to this email.', 0, '2025-12-09 07:52:04'),
(30, 'Could you help me with the database migration this afternoon?', 0, '2025-12-09 07:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Lenard7', 'lenard123@gmail.com', '$2y$12$/DDEfynwQUqJ5QcYhEhjpu8zy/9CCaxc0T9XI4gM0YTbzcSV1O3rS', '2025-12-09 07:52:28', '2025-12-09 07:52:28'),
(2, 'lenard17', 'lenard156@gmail.com', '$2y$12$.OfhYFp2wx5IyO57uLBi1.wXkaWwR90hDBXacLAaiKnSaWO3gU/Eq', '2025-12-09 07:57:37', '2025-12-09 07:57:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_messages_user_id` (`user_id`),
  ADD KEY `idx_messages_analyzed_at` (`analyzed_at`);

--
-- Indexes for table `training_data`
--
ALTER TABLE `training_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_training_is_spam` (`is_spam`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `training_data`
--
ALTER TABLE `training_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
