-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 01:29 PM
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
-- Database: `ecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `role`, `email`, `mobile`, `status`) VALUES
(1, 'lizBethAgri', 'liza123', 0, '', '', 1),
(3, 'liz', 'bella', 1, 'lizabell@gmail.com', '53393003-3', 1),
(6, 'leticia', 'leticia123', 1, 'holi40027@gmail.com', '0755675337', 1),
(7, 'Alele bura', 'bura123', 1, 'bura@gmail.com', '077346782', 1),
(8, 'ben', 'ben 123', 1, 'ben@gmail.com', '0755675337', 1),
(9, 'lizard', 'liza123', 1, 'liz@gmail.com', '0755675337', 1),
(10, 'keny', 'keny123', 1, 'keny@gmail.com', '0788956784', 1),
(11, 'lizaBethAgri', 'erty', 1, 'lizaBethAgri@gmail.com', '07731344344', 1);

-- --------------------------------------------------------

--
-- Table structure for table `agri_tips`
--

CREATE TABLE `agri_tips` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `about` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date_t` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agri_tips`
--

INSERT INTO `agri_tips` (`id`, `title`, `about`, `description`, `added_by`, `date_t`) VALUES
(1, 'etryuiop', 'marketprice', 'sdfjkl', 1, '2024-10-25'),
(2, 'werryui', 'marketprice', 'tryuio', 1, '2024-10-25'),
(6, 'ertyu', 'marketprice', 'jkl', 1, '2024-10-25'),
(7, 'ertyu', 'marketprice', 'jkl', 1, '2024-10-25'),
(10, 'ertyu', 'marketprice', 'jkl', 1, '2024-10-25'),
(16, 'haverst of tomatos', 'myfarm', 'pending harvest of tomatoes pay for workers', 3, '2024-10-26'),
(17, 'new fungus', 'myfarm', 'maize fungus affecting maize need.....', 3, '2024-10-28');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `categories`, `status`) VALUES
(2, 'Vegetable', 1),
(19, 'fruits', 1),
(23, 'legumes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(75) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `comment` text NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `mobile`, `comment`, `added_on`) VALUES
(9, 'ghh', 'lizaBethAgri@gmail.com', '0788620385', 'blah blah blah', '2024-09-14 05:49:05'),
(10, 'elizabeth ayinga', 'Ayingaelizabeth@gmail.com', '0788620385', 'i really like the products that come from these platform very fresh', '2024-10-19 10:37:03'),
(11, 'beth ayinga', 'lizaBethAgri@gmail.com', '0788620389', 'final testing blah blah blah blah', '2024-10-21 12:28:17'),
(12, 'beth ayinga', 'lizaBethAgri@gmail.com', '0788620389', 'final testing blah blah blah blah', '2024-10-21 12:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_master`
--

CREATE TABLE `coupon_master` (
  `id` int(11) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `coupon_value` int(11) NOT NULL,
  `coupon_type` varchar(10) NOT NULL,
  `cart_min_value` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `coupon_master`
--

INSERT INTO `coupon_master` (`id`, `coupon_code`, `coupon_value`, `coupon_type`, `cart_min_value`, `status`) VALUES
(3, 'AgrI123', 500, 'UGX', 1000, 1),
(5, 'XYZ40', 2000, 'UGX', 4000, 1),
(6, '345645', 10000, 'UGX', 20000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` int(11) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `total_price` float NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `order_status` int(11) NOT NULL,
  `length` float NOT NULL,
  `breadth` float NOT NULL,
  `height` float NOT NULL,
  `weight` float NOT NULL,
  `txnid` varchar(20) NOT NULL,
  `mihpayid` varchar(20) NOT NULL,
  `ship_order_id` int(11) NOT NULL,
  `ship_shipment_id` int(11) NOT NULL,
  `payu_status` varchar(10) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `coupon_value` varchar(50) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `address`, `city`, `pincode`, `payment_type`, `total_price`, `payment_status`, `order_status`, `length`, `breadth`, `height`, `weight`, `txnid`, `mihpayid`, `ship_order_id`, `ship_shipment_id`, `payu_status`, `coupon_id`, `coupon_value`, `coupon_code`, `added_on`) VALUES
(12, 1, 'kulambiro', 'kampala', 456, 'COD', 1500, 'pending', 1, 0, 0, 0, 0, '93f270f844343d05fab1', '', 0, 0, '', 0, '', '', '2024-10-11 11:22:27'),
(25, 0, 'kiwatule', 'kampala', 78, 'COD', 7630, 'Success', 5, 0, 0, 0, 0, 'b4bed2a74c9401d7e74c', '', 0, 0, '', 0, '', '', '2024-10-16 06:16:25'),
(26, 4, 'kulambiro', 'kampala', 12, 'COD', 1500, 'Success', 5, 0, 0, 0, 0, '5cfaa1a5725312befbbd', '', 0, 0, '', 0, '', '', '2024-10-19 11:18:11'),
(27, 4, 'katwe', 'kampala', 23, 'mtn_momo', 5500, 'Success', 5, 20, 20, 20, 20, '40af54a5f47a10b4926b', '', 0, 0, '', 0, '', '', '2024-10-19 07:12:27'),
(29, 4, 'kulambiro', 'kampala', 45, 'mtn_momo', 130, 'pending', 1, 0, 0, 0, 0, '7105ecd2c1d27b3ca31e', '', 0, 0, '', 0, '', '', '2024-10-21 08:25:12'),
(35, 4, 'kulambiro', 'kampala', 23, 'COD', 20000, 'Success', 5, 0, 0, 0, 0, 'fb95c0695bd1b3264f49', '', 0, 0, '', 0, '', '', '2024-10-21 11:47:01'),
(37, 4, 'kulambiro', 'kampala', 23, 'COD', 4500, 'pending', 1, 0, 0, 0, 0, 'fa062d4239fe6369f787', '', 0, 0, '', 0, '', '', '2024-10-21 12:13:42'),
(38, 4, 'kulambiro', 'kampala', 23, 'mtn_momo', 2000, 'Success', 5, 0, 0, 0, 0, '8ea170ca0231a90ed34e', '', 0, 0, '', 0, '', '', '2024-10-21 02:44:20'),
(39, 4, 'kulambiro', 'kampala', 34, 'COD', 3000, 'Success', 5, 0, 0, 0, 0, '46afacb462d0afca013b', '', 0, 0, '', 0, '', '', '2024-10-21 02:55:33'),
(40, 4, 'kulambiro', 'kampala', 34, 'COD', 63000, 'Success', 5, 0, 0, 0, 0, '548b454abeba4f4dd02f', '', 0, 0, '', 0, '', '', '2024-10-21 03:04:47'),
(41, 4, 'kulambiro', 'kampala', 123, 'COD', 13500, 'Success', 5, 0, 0, 0, 0, 'd0c0ce20538d57ab7921', '', 0, 0, '', 0, '', '', '2024-10-28 05:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(1, 12, 12, 1, 100),
(3, 13, 13, 1, 150),
(4, 12, 12, 1, 1000),
(86, 17, 17, 1, 3000),
(88, 19, 17, 1, 3000),
(89, 1, 17, 1, 3000),
(90, 1, 20, 3, 1500),
(91, 1, 18, 4, 4500),
(92, 0, 21, 5, 5500),
(93, 25, 13, 1, 130),
(94, 25, 17, 1, 3000),
(95, 25, 18, 1, 4500),
(96, 26, 20, 1, 1500),
(97, 27, 21, 1, 5500),
(98, 29, 13, 1, 130),
(99, 30, 19, 1, 1000),
(100, 32, 20, 1, 1500),
(101, 34, 18, 1, 4500),
(102, 35, 22, 10, 2000),
(103, 37, 23, 3, 1500),
(104, 38, 22, 1, 2000),
(105, 39, 23, 2, 1500),
(106, 40, 24, 7, 9000),
(107, 41, 18, 3, 4500);

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Processing'),
(3, 'Shipped'),
(4, 'Canceled'),
(5, 'Complete');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `sub_categories_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `oldprice` float NOT NULL,
  `price` float NOT NULL,
  `qty` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `short_desc` varchar(2000) NOT NULL,
  `description` text NOT NULL,
  `best_seller` int(11) NOT NULL,
  `meta_title` varchar(2000) NOT NULL,
  `meta_desc` varchar(2000) NOT NULL,
  `meta_keyword` varchar(2000) NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `Rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `categories_id`, `sub_categories_id`, `name`, `oldprice`, `price`, `qty`, `image`, `short_desc`, `description`, `best_seller`, `meta_title`, `meta_desc`, `meta_keyword`, `added_by`, `status`, `Rating`) VALUES
(1, 1, 1, 'Fresh Organic Simla Apple Fruit 4 Pieces', 0, 44, 10, 'apples.jpg', '100% Wax Free, Pack contains 4 pieces\nGood source of Vitamin C, Dietary Fiber, Flavonoids and antioxidants', 'Shimla apples are commonly grown apples in India. They are one of the healthiest fruit. They are good source of vitamin c, dietary fiber, flavonoids and antioxidants. Store them in a cool dry place away from direct sunlight. They can eaten as a fresh or used in fruit chat, salads, smoothies, pies etc.', 1, 'apple', '', 'apple', 0, 1, 5),
(2, 1, 1, 'Pears', 0, 35, 4, 'pears.jpg', 'Aenean tempus ut leo nec laoreet. Vestibulum ut est neque.', 'Curabitur eget augue dolor. Curabitur id dapibus massa. Vestibulum at enim quis metus ultrices posuere vitae sit amet eros. Morbi et libero pellentesque, efficitur odio nec, congue lorem. Vestibulum faucibus, risus eget pretium efficitur, neque nulla eleifend purus, non venenatis lorem ligula vel nulla. Fusce finibus efficitur sapien vitae laoreet. Integer imperdiet justo sed tellus dictum, at egestas arcu finibus. Fusce et augue elit. Praesent tincidunt purus in purus dictum volutpat. Aenean tempus ut leo nec laoreet. Vestibulum ut est neque.', 0, 'Pears', '', 'Pears', 0, 1, 4),
(3, 1, 1, 'Cherries', 0, 40, 5, 'cherries.jpg', 'Nullam purus lorem, tincidunt vitae tristique non, imperdiet ut urna.', 'Nullam a nunc et lorem ornare faucibus. Etiam tortor lacus, auctor eget enim at, tincidunt dignissim magna. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin tincidunt eros eget felis tempor, id volutpat ipsum lacinia. Donec scelerisque risus non purus scelerisque tristique. Mauris enim ligula, condimentum sed iaculis nec, porttitor eu nunc. Sed hendrerit vel arcu vitae iaculis. Phasellus vehicula molestie leo. Nullam purus lorem, tincidunt vitae tristique non, imperdiet ut urna.', 0, 'Pears', 'Pears', 'Pears', 0, 1, 4),
(4, 1, 2, 'Bananas', 0, 250, 3, 'bananas.jpg', 'per inceptos himenaeos. Ut commodo ullamcorper quam non pulvinar.', 'Duis a felis congue, feugiat est non, suscipit quam. In elit lacus, auctor sed lacus eget, egestas consectetur leo. Duis pellentesque pharetra ante, ac ornare nibh faucibus id. Integer pulvinar malesuada nisl. Nulla vel orci nunc. Nullam a tellus eu ex ullamcorper mollis. Donec commodo ligula a accumsan fermentum. Mauris sed orci lacinia, posuere leo molestie, pretium mi. Cras sodales, neque id cursus fermentum, mi purus vehicula sem, vel laoreet lorem justo id tortor. Sed ut urna ut ipsum vestibulum commodo. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut commodo ullamcorper quam non pulvinar.', 1, 'bananas', 'bananas', 'bananas', 0, 1, 4),
(5, 1, 2, 'Mangoes', 0, 2399, 8, 'mangoes.jpg', 'a nisl pharetra orci, at condimentum nisl lorem elementum ipsum.', 'Nunc auctor turpis ante, eget bibendum mi mollis in. Aliquam quis neque ut libero malesuada auctor. Aliquam interdum enim at commodo gravida. Donec nisl sem, molestie ut quam quis, vulputate venenatis ipsum. Aenean quis ex ut magna accumsan fringilla. Quisque id ex massa. Sed libero ante, fringilla ac condimentum in, porttitor ac risus. Integer mattis odio nec nunc semper imperdiet. In porttitor tellus eget sapien vulputate, eu euismod lacus aliquet. Maecenas molestie elit augue, sit amet fringilla dolor congue et. Nunc eu libero auctor, sollicitudin lectus quis, porta ligula. In vel ullamcorper risus. Nullam viverra, mi sit amet laoreet luctus, urna nisl pharetra orci, at condimentum nisl lorem elementum ipsum.', 1, 'mangoes', 'mangoes', 'Fresh Indian mangoes', 0, 1, 4),
(13, 2, 6, 'Tomatoes', 200, 130, 10, 'tomatoes.jpg', 'Test desc', 'Test desc', 1, 'tomatoes', 'tomatoes', 'tomatoes', 2, 1, 4),
(14, 2, 6, 'Peas', 201, 30, 10, 'peas.jpg', 'Test desc', 'Test desc', 0, 'peas', 'peas', 'peas', 2, 1, 5),
(15, 2, 6, 'Brinjal', 202, 50, 10, 'brinjal.jpg', 'Test desc', 'Test desc', 0, 'brinjal', 'brinjal', 'brinjal', 2, 1, 2),
(16, 2, 6, 'Okra', 2000, 1500, 10, 'ladyfingers.jpg', 'Okra', 'Okra', 0, 'Okra', 'Okra', 'Okra', 1, 1, 3),
(17, 2, 6, 'Carrots', 4000, 3000, 2, 'carrots.jpg', 'Test desc', 'Test desc', 1, 'carrots', 'carrots', 'carrots', 1, 1, 5),
(18, 2, 6, 'Onions', 5000, 4500, 10, '540156353_onions.jpg', 'Test desc', 'Test desc', 1, 'onions', 'onions', 'onions', 1, 1, 4),
(19, 2, 6, 'spinach', 3000, 1000, 7, '488079373_896870071_lettuce.jpg', 'spinach', 'spinach', 0, 'spinach', 'spinach', 'spinach', 3, 1, 0),
(20, 19, 25, 'watermelon', 3000, 1500, 20, '323542165_512168633_watermelon.jpg', 'melon', 'melon', 0, 'melon', 'melon', 'melon', 3, 1, 0),
(21, 2, 6, 'cauliflower', 6000, 5500, 5, '549330348_cauliflower.jpg', 'cauliflower', 'cauliflower', 0, 'cauliflower', 'cauliflower', 'cauliflower', 9, 1, 0),
(22, 23, 26, 'Nambale beans', 2200, 2000, 100, '563036638_beans.jpeg', 'namabale', 'nambale', 1, 'nambale', 'nambale', 'nambale', 3, 1, 0),
(23, 2, 6, 'cabbage', 2000, 1500, 19, '690720775_cabbage.jpg', 'cabbage', 'cabbage', 0, 'cabbage', 'cabbage', 'cabbage', 1, 1, 0),
(24, 19, 25, 'watermelon', 10000, 9000, 45, '127338937_323542165_512168633_watermelon.jpg', 'melon', 'melon', 0, 'melon', 'melon', 'melon', 9, 1, 0),
(25, 19, 25, 'Bananas', 3000, 2000, 20, '965174808_bananas.jpg', 'sweet yellow bananas', 'sweet yellow bananas', 0, 'banana', 'banana', 'banana', 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shiprocket_token`
--

CREATE TABLE `shiprocket_token` (
  `id` int(11) NOT NULL,
  `token` varchar(500) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shiprocket_token`
--

INSERT INTO `shiprocket_token` (`id`, `token`, `added_on`) VALUES
(1, '', '2024-10-20 09:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `sub_categories` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `categories_id`, `sub_categories`, `status`) VALUES
(0, 4, 'General', 1),
(1, 1, 'General', 1),
(2, 1, 'Tropic and Exotic', 1),
(3, 1, 'Citrus', 1),
(4, 1, 'Berries', 1),
(5, 1, 'Melons', 1),
(6, 2, 'General', 1),
(7, 2, 'Leafy Green', 1),
(8, 2, 'Cruciferous', 1),
(9, 2, 'Marrow', 1),
(10, 3, 'Cereals', 1),
(11, 3, 'Pseudo-Cereals', 1),
(12, 5, 'Bread', 1),
(13, 5, 'Chocolate', 1),
(15, 2, 'local', 1),
(16, 5, 'local', 1),
(17, 1, 'exotic', 1),
(18, 5, 'animal', 1),
(19, 1, 'hj', 1),
(20, 1, 'hkl', 1),
(21, 1, 'hklhjk', 1),
(22, 1, 'hklhjkjhkj', 1),
(23, 1, 'hklhjkjhkjjkl', 1),
(24, 2, 'kj', 1),
(25, 19, 'local', 1),
(26, 23, 'local', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `mobile`, `added_on`) VALUES
(1, 'sundus', '$2y$10$VxaTHNrmM1A8XZf.p5Snnut1RMJ55gaP2474IKGquL2', 'sundus@gmail.com', '0788863453', '2024-10-16 04:10:55'),
(2, 'elizabeth ayinga', '$2y$10$ZsbPj29iI7J21JAh0G9nPutXPkqrs/IMpHahW2Wfvqb', 'elizabeth@gmail.com', '0788620387', '2024-10-16 04:23:21'),
(4, 'liza', 'liza123', 'ayingaelizabeth@gmail.com', '0788620385', '2024-10-16 04:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_on`) VALUES
(1, 1, 6, '2024-09-24 10:03:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agri_tips`
--
ALTER TABLE `agri_tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_master`
--
ALTER TABLE `coupon_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shiprocket_token`
--
ALTER TABLE `shiprocket_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `agri_tips`
--
ALTER TABLE `agri_tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `coupon_master`
--
ALTER TABLE `coupon_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `shiprocket_token`
--
ALTER TABLE `shiprocket_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
