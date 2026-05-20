SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Tabel Admins
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin Default (Password: admin123 yang di-hash menggunakan PASSWORD_DEFAULT)
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$gR09W9Zg8hR8Mv36f5O6P.G7R3iYn/4C2FmX866q39/X03K2O7pDG');

-- 2. Tabel Settings
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(100) DEFAULT 'Garfield Store',
  `logo` varchar(255) DEFAULT NULL,
  `hero_title` varchar(255) DEFAULT 'Premium Digital World',
  `hero_subtitle` varchar(255) DEFAULT 'Temukan akun premium dan layanan digital terbaik dengan garansi penuh.',
  `wa_number` varchar(20) DEFAULT '628123456789',
  `telegram` varchar(100) DEFAULT '@garfieldstore',
  `email` varchar(100) DEFAULT 'support@garfieldstore.com',
  `instagram` varchar(255) DEFAULT 'https://instagram.com/',
  `bg_music` varchar(255) DEFAULT NULL,
  `theme_color` varchar(20) DEFAULT '#00d2ff',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`id`) VALUES (1);

-- 3. Tabel Categories
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`name`, `slug`) VALUES 
('Premium Account', 'premium-account'),
('Gaming', 'gaming');

-- 4. Tabel Products
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `discount_price` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Ready','Promo','Sold Out') DEFAULT 'Ready',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel Testimonials
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `review` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabel FAQs
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
