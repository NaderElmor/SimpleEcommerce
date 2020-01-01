-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2020 at 05:12 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `description` text CHARACTER SET utf8mb4 NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT 0,
  `allowComment` tinyint(4) NOT NULL DEFAULT 0,
  `allowAds` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryID`, `categoryName`, `parent`, `description`, `ordering`, `visibility`, `allowComment`, `allowAds`) VALUES
(9, 'Hand Made', 0, 'Hand made items', 1, 0, 0, 0),
(10, 'Computers', 0, 'Computer items', 2, 0, 0, 0),
(11, 'Cell phones', 0, '', NULL, 0, 0, 0),
(12, 'Clothes', 0, 'Fashion', 4, 1, 1, 1),
(13, 'Tools', 0, 'Very good tools', 5, 0, 0, 0),
(16, 'Boxes', 9, 'jjjjjjjjjjj', 3, 0, 0, 0),
(17, 'Nokia', 11, '', 0, 0, 0, 0),
(18, 'OPPO', 11, '', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentID` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `userID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentID`, `content`, `status`, `date`, `userID`, `itemID`) VALUES
(24, 'wow', 0, '2019-06-19', 2, 17),
(36, 'nice', 0, '2019-06-19', 9, 17),
(37, 'Nice Wow', 0, '2019-06-19', 9, 17),
(38, 'Very goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery goodVery good', 0, '2019-06-19', 9, 17),
(40, 'wow 1', 0, '2019-06-20', 9, 13),
(41, 'wow 2\r\n', 0, '2019-06-20', 9, 13),
(42, 'Wow ', 0, '2019-10-12', 1, 41);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `rating` int(11) NOT NULL,
  `approve` int(11) NOT NULL DEFAULT 0,
  `userID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itemName`, `description`, `price`, `country`, `image`, `status`, `date`, `rating`, `approve`, `userID`, `categoryID`, `tags`) VALUES
(13, 'Speaker', 'Wow Speaker', 20, 'China', '', '1', '2019-05-15', 0, 1, 9, 9, 'computer     , lAptOp   ,     speaker'),
(14, 'Microphone', 'Very good mic', 0, 'Japan', '', '2', '2019-05-15', 0, 0, 9, 9, ''),
(16, 'Mouse', 'Magic mouse', 0, 'Egypt', '', '2', '2019-05-15', 0, 0, 9, 9, ''),
(17, 'Keyboard', 'Very good Keyboard', 0, 'USA', '', '3', '2019-05-15', 0, 0, 2, 13, ''),
(18, 'Lap Top Dell', 'Core I5 Ram 8 GB Hard 1Tera.', 200, 'USA', 'download.jpg', '1', '2019-06-17', 0, 1, 9, 10, 'laptops, computer'),
(36, 'Hammer', 'Very big hammer', 30, 'USA', '', '1', '2019-06-21', 0, 0, 14, 13, 'tools, carpenter'),
(37, 'toshipa', 'Very good', 5.5, 'USA', 'download (1).jpg', '1', '2019-06-21', 0, 1, 9, 10, 'laptops,computer'),
(41, 'OPPO F11', 'The durability and quality of the phone is made of plastic', 400, 'China', '2422790_OPPO-F11.jpg', '1', '2019-06-24', 0, 1, 27, 11, 'oppo, phones, smart'),
(42, 'Oppo 2019', 'The durability and quality of the phone is made of plastic', 200, 'USA', 'oppo1.jpg', '1', '2019-06-24', 0, 1, 9, 18, 'oppo, phones, smart'),
(43, 'Nokia', 'The durability and quality of the phone is made of plastic', 150, 'Fenland', '', '2', '2019-06-24', 0, 0, 27, 17, 'nokia'),
(44, 'OPPO A57', 'The Oppo A57 is a newly launched selfie-oriented smartphone targeted at value-conscious buyer', 327, 'China', 'imagesx.jpg', '1', '2019-04-05', 0, 1, 9, 11, 'oppo, smart, phones'),
(45, 'hkhhkhkj', 'kjhkjhkjhk', 0, 'Singapore', '3138339_', '1', '2019-12-22', 0, 0, 1, 18, 'phone'),
(46, 'test', 'lkhlhlh', 67, 'Singapore', '781240_img.jpg', '1', '2019-12-22', 0, 0, 1, 17, 'phone'),
(47, 'test', 'practical api', 18, 'Singapore', '8952606_logo2.png', '1', '2019-12-31', 0, 0, 1, 16, 'phone');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL COMMENT 'to identify the user',
  `userName` varchar(255) NOT NULL COMMENT 'user name to login',
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `groupID` int(11) NOT NULL DEFAULT 0 COMMENT 'if 0 then he is a user if 1 he is an admin',
  `trustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'seller rank if 0 he is a normal seller',
  `regStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'user approval(Don''t accept if 0)',
  `date` datetime NOT NULL,
  `userImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `password`, `email`, `fullName`, `groupID`, `trustStatus`, `regStatus`, `date`, `userImage`) VALUES
(1, 'nader', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'naderelmor17@gmail.com', 'Nader Hany Elmor', 1, 0, 1, '2019-04-01 00:00:00', ''),
(2, 'Adelz', '04890dd8e8d0c39b92bcbf8d998e5fce8de8d6b8', 'hh@gmail.com', 'ggggggggggg', 0, 0, 1, '2019-03-17 00:00:00', ''),
(6, 'Ebrahim', '2786f93d7b6fdbefa3b485bb2848cdb98665ab21', 'naderelmor38@gmail.com', 'ssssssssssss', 0, 0, 1, '2019-04-09 00:00:00', ''),
(7, 'Ali', '79211d5eb25b346f71c40a35473f3d2b0879673f', 'ahmed@gmail.comuuu', 'hkhkh', 0, 0, 1, '2019-04-14 17:34:16', ''),
(8, 'fffff', 'b009ad00622ce0daa528c1c1d2402885ac15ec34', 'naderelmoffffr3vv8@gmail.com', 'ffffffffffffffffff', 0, 0, 1, '2019-05-10 17:50:53', ''),
(9, 'Elmor', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ouser1@gmail.com', 'Ordinary User', 0, 0, 1, '2019-06-04 16:02:35', 'formal.jpg'),
(13, 'marwa', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'hh@gmail.com', '', 0, 0, 1, '2019-06-13 01:52:47', ''),
(14, 'ouser2', '', 'ouser2@gmail.com', 'Ouser2 full name', 0, 0, 1, '0000-00-00 00:00:00', ''),
(15, 'ffffff', '76429306d09b2c9e9b867ee25d43f2769a001474', 'hh@gmail.com', 'ffffffffffff', 0, 0, 1, '0000-00-00 00:00:00', ''),
(16, 'sdfsdfsdf', 'e4dbd52ed0486794b519a3a94407a83381e117dc', 'hhfffff@gmail.com', 'dfsdfsdf', 0, 0, 1, '0000-00-00 00:00:00', ''),
(17, 'zxczc', 'e28f2ebe7df6baf8bd89e470dd80b12601f03231', 'ahmxxed@gmail.com', 'cascaaca', 0, 0, 1, '0000-00-00 00:00:00', ''),
(18, 'asdad', 'c06edbd5cf71e4e6d21acbfdd36e88f486f43dd8', 'hdddh@gmail.com', 'asdads', 0, 0, 1, '0000-00-00 00:00:00', ''),
(19, 'adsdas', '85136c79cbf9fe36bb9d05d0639c70c265c18d37', 'ddhh@gmail.com', 'dasdadad', 0, 0, 1, '2019-06-21 20:55:47', '6228943_aya.png'),
(21, 'mytest', '8dff9be21d11c8714f905da71e08985cc975967b', 'exx@g.com', 'mytestmytestmytest', 0, 0, 1, '2019-06-24 14:31:24', '9982911_red.png'),
(27, 'Nader Hany', '676c9e70a4b09d8dd10f77971b8eb87953456f75', 'excx@g.com', 'Nader HAny', 0, 0, 1, '2019-06-24 15:33:19', '9620972_12309420_181761958836477_705225228_n.jpg'),
(28, 'ali ali', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'shop100@gmail.com', '', 0, 0, 0, '2019-07-04 17:11:23', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`),
  ADD UNIQUE KEY `name` (`categoryName`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `itemss_comments` (`itemID`),
  ADD KEY `users_comments` (`userID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`),
  ADD KEY `mk_userID_fk` (`userID`),
  ADD KEY `mk_categoryID_fk` (`categoryID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'to identify the user', AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `itemss_comments` FOREIGN KEY (`itemID`) REFERENCES `items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_comments` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `mk_categoryID_fk` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mk_userID_fk` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
