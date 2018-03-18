-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2018 at 04:42 AM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wheel_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `fa_icon` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `title`, `locked`, `fa_icon`) VALUES
(1, 'Off-Topic', 0, 'hand-peace'),
(2, 'Programming', 0, 'terminal'),
(3, 'Science and Tech', 0, 'flask'),
(5, 'Fitness and Health', 0, 'medkit'),
(6, 'Sports', 0, 'volleyball-ball'),
(7, 'Education and Career', 0, 'book'),
(10, 'Music', 0, 'music'),
(11, 'Films and TV', 0, 'film'),
(12, 'Video Games', 0, 'gamepad'),
(13, 'Wallpapers', 0, 'images');

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `archived` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(256) NOT NULL,
  `full_text` varchar(32768) NOT NULL,
  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`id`, `archived`, `title`, `full_text`, `creation_timestamp`, `author_id`, `board_id`, `image_id`) VALUES
(5, 0, 'Great spot to download books - Mobilism', 'I imagine most people know about it but if not, the Mobilism forums are a great place for downloading ebooks.. you can make requests as well and often will end up finding stuff there that you can\'t wrangle up anywhere else: \n\nhttp://forum.mobilism.org/viewforum.php?f=120', '2018-03-08 13:39:39', 1, 1, 6),
(6, 0, 'Another test discussion', 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee eeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', '2018-03-10 15:22:09', 1, 1, 10),
(7, 0, 'what do u think of java?', 'What do u guys think of Java? Is it still worth learning?\r\nI want to get into software development and I\'m not sure what language to pickup.', '2018-03-10 18:01:49', 1, 2, 11),
(8, 0, 'Learning ruby', 'so, I am going to start learning ruby because I am a basement dwelling neck beard and I have no life. \r\n\r\nSo, I figure I might as well learn how to program and thought ruby would be a good place to start. \r\n\r\nWhat are some staples I should know about ruby and programming in general, really?', '2018-03-10 18:05:36', 1, 2, 12),
(9, 0, 'portable music player thread', 'what players you using and how they workin? what headphones you got paired with it?', '2018-03-10 18:08:03', 1, 3, 13),
(10, 0, 'need recommendations', ' recommend me bands and albums like The National like their soft, mellow rock style and the gentle vocals. Bands and albums similar to their relaxing and soft aspect.', '2018-03-10 18:17:53', 1, 10, 15),
(11, 0, 'Chelsea vs palace match thread', 'Chelsea\r\n\r\nStarting XI : Courtois; Azpilicueta, Christensen, Cahill (c); Zappacosta, Kante, Fabregas, Alonso; Willian, Giroud, Hazard\r\n\r\nSubs :Caballero, Ampadu, Bakayoko, Emerson, Moses, Pedro, Morata\r\n\r\nPalace \r\nStarting XI:Hennessey, Wan-Bissaka, Kelly, Tomkins, Van Aanholt, Townsend, McArthur, Milivojevic, Schlupp, Sorloth, Benteke\r\n\r\nSubs:Cavalieri, Sakho, Souare, Fosu-Mensah, Riedewald, Lee, Zaha', '2018-03-10 18:19:18', 1, 6, 16),
(12, 0, 'Obscure games', 'Tell us about that game that you love but nobody plays. Bonus points if it\'s abandonware or free', '2018-03-11 04:39:11', 2, 12, 17),
(13, 0, 'Classy nerd wallpapers.', 'I\'m looking for some simple/classy nerd wallpapers (anime/video games/whatever) that don\'t scream "I haven\'t seen the sun in months." Posting what I have.', '2018-03-11 04:41:25', 2, 13, 18);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(32) NOT NULL,
  `addition_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `filename`, `addition_timestamp`, `size`) VALUES
(6, '1-5aa13d1bc0b059.43454553.jpeg', '2018-03-08 07:39:39', 129804),
(9, '1-5aa3ee2d435657.34007652.png', '2018-03-10 08:39:41', 35158),
(10, '1-5aa3f8210b9e45.17417101.jpeg', '2018-03-10 09:22:09', 106097),
(11, '1-5aa41d8d0feef1.53590133.jpeg', '2018-03-10 12:01:49', 109236),
(12, '1-5aa41e7003d125.47465055.png', '2018-03-10 12:05:36', 90925),
(13, '1-5aa41f03af7956.29707101.jpeg', '2018-03-10 12:08:03', 343211),
(14, '1-5aa41f23e6d9c9.81513431.jpeg', '2018-03-10 12:08:35', 1863377),
(15, '1-5aa42151cdcbb5.88359378.jpeg', '2018-03-10 12:17:53', 24254),
(16, '1-5aa421a6a4efb8.68956092.jpeg', '2018-03-10 12:19:18', 105953),
(17, '2-5aa4b2efb62815.49517551.jpeg', '2018-03-10 22:39:11', 95716),
(18, '2-5aa4b375cecb10.09708152.jpeg', '2018-03-10 22:41:25', 462588);

-- --------------------------------------------------------

--
-- Table structure for table `moderations`
--

CREATE TABLE `moderations` (
  `user_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `moderations`
--

INSERT INTO `moderations` (`user_id`, `board_id`) VALUES
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `full_text` varchar(32768) NOT NULL,
  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `full_text`, `creation_timestamp`, `author_id`, `discussion_id`, `image_id`) VALUES
(1, 'heleoroteeeeeeeeeeee heleoroteeeeeeeeeeee heleoroteeeeeeeeeeee heleoroteeeeeeeeeeee heleoroteeeeeeeeeeee heleoroteeeeeeeeeeee', '2018-03-10 13:06:08', 1, 5, NULL),
(2, 'eiiiiiiiiiirrrrrrrrrr eiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrreiiiiiiiiiirrrrrrrrrr', '2018-03-10 14:39:41', 1, 5, 9),
(3, 'uyrturytrtn trhtjrjtrttrrrrrrrrrr', '2018-03-10 15:24:39', 1, 6, NULL),
(4, 'erer erergfbgfgbfgerererererer', '2018-03-10 17:58:03', 1, 6, NULL),
(5, 'Personally I love Java. It\'s very comfy to write in and you always get stuff done. Those long names and the reason they don\'t offer many ways to save typing time that someone mentions is because you\'re supposed to write clean code people can understand just by looking at it (without reading comments). Java is meant to be shared and it is, there\'s a ton of stuff out there ready to be used in your program. \r\n\r\nI\'d recommend Java to anyone because you\'ll basically learn C# and ActionScript 3 at the same time as you\'re learning Java.', '2018-03-10 18:02:44', 1, 7, NULL),
(6, 'Agreed. Java forces you to write clean code. Also after learning Java I could develop in C# without a problem', '2018-03-10 18:03:10', 1, 7, NULL),
(7, 'AFAIK "Learn Ruby The Hard Way" is still free, and not only is it the one good thing that joker wrote, it\'s also the best thing he did. Which is why he wrote it first, and then moved on to things he didn\'t understand as well... which is almost poetic, if you like bad poetry. \r\n\r\nLRTHW works, though, and I\'d suggest starting there, if you\'re dead-set on ruby. It\'s ~slightly~ easier than Python, but I\'d really recommend py at this point. It\'s more applicable; frameworks built around Ruby are worth learning if you know jack shit about MVE or whatever, Python will inevitably lead to you checking out the differenced between 3 and 2.7 and goes right into pentesting. So idk man, it\'s blub, but it\'s a good blub to help you get into learning how languages work.', '2018-03-10 18:06:00', 1, 8, NULL),
(8, 'python is better than ruby', '2018-03-10 18:06:14', 1, 8, NULL),
(9, 'here\'s a decent modification price:performance wise \r\n\r\n3000mAh aftermarket battery \r\nnew front and back panel \r\n256gb msata ssd addition \r\nRockbox \r\nvide 5G base with Wolfson internals', '2018-03-10 18:08:35', 1, 9, 14),
(10, 'I think I\'d have to pick Maya \r\n\r\nOr Ginko, but that\'s more IS. All the characters are awesome \r\n\r\nAlso you should check out Blue Reflection \r\n\r\nYou ever notice the dude in the antique shop is the devil?', '2018-03-11 04:40:07', 2, 12, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `board_id` int(11) NOT NULL,
  `full_text` varchar(32768) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`board_id`, `full_text`) VALUES
(1, 'some rules related to off topic board'),
(2, 'some rules'),
(3, 'some rules'),
(5, 'some rules'),
(6, 'some rules'),
(7, 'some rules'),
(10, 'some rules'),
(11, 'some rules'),
(12, 'some rules'),
(13, 'some rules');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email_address` varchar(254) NOT NULL,
  `password_hash` varchar(32) NOT NULL,
  `registration_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('normal','moderator','admin') NOT NULL DEFAULT 'normal',
  `account_status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `password_hash`, `registration_timestamp`, `role`, `account_status`) VALUES
(1, 'anonymous', 'anonymous', '2018-03-08 13:33:36', 'normal', 'active'),
(2, 'def@xyz.com', '457dcd4cc804078a5dd2299685d7367d', '2018-03-11 04:08:22', 'normal', 'active'),
(3, 'xyz@xyz.io', '457dcd4cc804078a5dd2299685d7367d', '2018-03-11 04:10:35', 'normal', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discussions_fk0` (`author_id`),
  ADD KEY `discussions_fk1` (`board_id`),
  ADD KEY `discussions_fk2` (`image_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filename` (`filename`);

--
-- Indexes for table `moderations`
--
ALTER TABLE `moderations`
  ADD KEY `moderations_fk0` (`user_id`),
  ADD KEY `moderations_fk1` (`board_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `replies_fk0` (`author_id`),
  ADD KEY `replies_fk1` (`discussion_id`),
  ADD KEY `replies_fk2` (`image_id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD UNIQUE KEY `board_id` (`board_id`),
  ADD KEY `rules_fk0` (`board_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_fk0` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `discussions_fk1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`),
  ADD CONSTRAINT `discussions_fk2` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `moderations`
--
ALTER TABLE `moderations`
  ADD CONSTRAINT `moderations_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `moderations_fk1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`);

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_fk0` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `replies_fk1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`),
  ADD CONSTRAINT `replies_fk2` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`);

--
-- Constraints for table `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rules_fk0` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
