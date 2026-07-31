-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2026 at 04:37 PM
-- Server version: 8.0.44
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `user_id` int NOT NULL,
  `user_name` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking`
--

CREATE TABLE `tbl_booking` (
  `b_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `org_id` int NOT NULL,
  `eve_name` varchar(50) NOT NULL,
  `eve_type` varchar(50) NOT NULL,
  `eve_desc` varchar(500) NOT NULL DEFAULT '',
  `expect_guests` varchar(20) NOT NULL DEFAULT '',
  `theme` varchar(50) NOT NULL DEFAULT '',
  `venue_name` varchar(50) NOT NULL DEFAULT '',
  `venue_addr` varchar(250) NOT NULL DEFAULT '',
  `catering` varchar(30) NOT NULL DEFAULT '',
  `photography` varchar(30) NOT NULL DEFAULT '',
  `payment_method` varchar(30) NOT NULL DEFAULT '',
  `event_budget` varchar(30) NOT NULL DEFAULT '',
  `strt_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `booking_date` timestamp NULL DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `cust_id` int NOT NULL,
  `user_name` varchar(80) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `address2` varchar(250) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` date DEFAULT NULL,
  `verification_code` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `profile_pic` longblob,
  `insta_profile` varchar(250) NOT NULL DEFAULT '',
  `twitter_profile` varchar(250) NOT NULL DEFAULT '',
  `facebook_profile` varchar(250) NOT NULL DEFAULT '',
  `linkedin_profile` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cust_admin_msg`
--

CREATE TABLE `tbl_cust_admin_msg` (
  `cust_admin_msg_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `cust_msg` text,
  `admin_msg` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cust_rating`
--

CREATE TABLE `tbl_cust_rating` (
  `rating_id` int NOT NULL,
  `org_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `rating` varchar(10) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '',
  `rating_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

CREATE TABLE `tbl_event` (
  `evn_id` int NOT NULL,
  `org_id` int NOT NULL,
  `eve_name` varchar(50) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `hall_name` varchar(50) NOT NULL DEFAULT '',
  `hall_add` varchar(250) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `pincode` varchar(10) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL DEFAULT '',
  `county` varchar(50) NOT NULL DEFAULT '',
  `hall_capacity` varchar(20) NOT NULL DEFAULT '',
  `image1` longblob,
  `image2` longblob,
  `image3` longblob,
  `image4` longblob,
  `time` time DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `add_evn_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `feed_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `name` varchar(80) NOT NULL,
  `feed_desc` varchar(500) NOT NULL,
  `feed_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_organizer`
--

CREATE TABLE `tbl_organizer` (
  `org_id` int NOT NULL,
  `user_name` varchar(80) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `company_name` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `pincode` varchar(10) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(50) NOT NULL DEFAULT '',
  `since_establish` varchar(50) NOT NULL DEFAULT '',
  `experience` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(250) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `created_at` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT '',
  `approve` varchar(20) NOT NULL DEFAULT '',
  `block` varchar(20) NOT NULL DEFAULT '',
  `verification_code` varchar(255) DEFAULT NULL,
  `v_status` tinyint(1) NOT NULL DEFAULT '0',
  `profile_pic` longblob,
  `insta_profile` varchar(250) NOT NULL DEFAULT '',
  `twitter_profile` varchar(250) NOT NULL DEFAULT '',
  `facebook_profile` varchar(250) NOT NULL DEFAULT '',
  `linkedin_profile` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_org_admin_msg`
--

CREATE TABLE `tbl_org_admin_msg` (
  `admin_msg_id` int NOT NULL,
  `org_id` int NOT NULL,
  `admin_msg` text,
  `org_msg` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_org_cust_msg`
--

CREATE TABLE `tbl_org_cust_msg` (
  `cust_msg_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `org_id` int NOT NULL,
  `org_msg` text,
  `cust_msg` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_org_rating`
--

CREATE TABLE `tbl_org_rating` (
  `rating_id` int NOT NULL,
  `org_id` int NOT NULL,
  `rating` varchar(10) NOT NULL,
  `disc` varchar(500) NOT NULL DEFAULT '',
  `rating_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_report`
--

CREATE TABLE `tbl_report` (
  `repo_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `org_id` int NOT NULL,
  `b_id` int DEFAULT NULL,
  `org_name` varchar(80) NOT NULL DEFAULT '',
  `repo_name` varchar(100) NOT NULL DEFAULT '',
  `repo_desc` varchar(500) NOT NULL DEFAULT '',
  `admin_reply` varchar(500) NOT NULL DEFAULT '',
  `repo_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD PRIMARY KEY (`b_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`cust_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_cust_admin_msg`
--
ALTER TABLE `tbl_cust_admin_msg`
  ADD PRIMARY KEY (`cust_admin_msg_id`);

--
-- Indexes for table `tbl_cust_rating`
--
ALTER TABLE `tbl_cust_rating`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`evn_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`feed_id`);

--
-- Indexes for table `tbl_organizer`
--
ALTER TABLE `tbl_organizer`
  ADD PRIMARY KEY (`org_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_org_admin_msg`
--
ALTER TABLE `tbl_org_admin_msg`
  ADD PRIMARY KEY (`admin_msg_id`);

--
-- Indexes for table `tbl_org_cust_msg`
--
ALTER TABLE `tbl_org_cust_msg`
  ADD PRIMARY KEY (`cust_msg_id`);

--
-- Indexes for table `tbl_org_rating`
--
ALTER TABLE `tbl_org_rating`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `tbl_report`
--
ALTER TABLE `tbl_report`
  ADD PRIMARY KEY (`repo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  MODIFY `b_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `cust_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_cust_admin_msg`
--
ALTER TABLE `tbl_cust_admin_msg`
  MODIFY `cust_admin_msg_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_cust_rating`
--
ALTER TABLE `tbl_cust_rating`
  MODIFY `rating_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `evn_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `feed_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_organizer`
--
ALTER TABLE `tbl_organizer`
  MODIFY `org_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_org_admin_msg`
--
ALTER TABLE `tbl_org_admin_msg`
  MODIFY `admin_msg_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_org_cust_msg`
--
ALTER TABLE `tbl_org_cust_msg`
  MODIFY `cust_msg_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_org_rating`
--
ALTER TABLE `tbl_org_rating`
  MODIFY `rating_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_report`
--
ALTER TABLE `tbl_report`
  MODIFY `repo_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
