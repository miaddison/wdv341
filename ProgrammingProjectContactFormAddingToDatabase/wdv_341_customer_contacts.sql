-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 27, 2017 at 12:47 AM
-- Server version: 5.6.35
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
using `miaddison_wdv`
--

-- --------------------------------------------------------

--
-- Table structure for table `wdv_341_customer_contacts`
--

CREATE TABLE `wdv_341_customer_contacts` (
  `contact_id` int(4) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_reason` varchar(100) NOT NULL,
  `contact_comments` varchar(500) NOT NULL,
  `contact_newsletter` tinyint(1) NOT NULL,
  `contact_more_products` tinyint(1) NOT NULL,
  `contact_date` date NOT NULL,
  `contact_time` time NOT NULL,
  `contact_assigned_rep` varchar(100) NOT NULL,
  `contact_followup_date` date NOT NULL,
  `contact_followup_result` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wdv_341_customer_contacts`
--

INSERT INTO `wdv_341_customer_contacts` (`contact_id`, `contact_name`, `contact_email`, `contact_reason`, `contact_comments`, `contact_newsletter`, `contact_more_products`, `contact_date`, `contact_time`, `contact_assigned_rep`, `contact_followup_date`, `contact_followup_result`) VALUES
(1, 'merna Addison', 'merna.addison@yahoo.com', 'billing', 'comments', 1, 1, '2017-10-26', '17:43:28', '', '0000-00-00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wdv_341_customer_contacts`
--
ALTER TABLE `wdv_341_customer_contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wdv_341_customer_contacts`
--
ALTER TABLE `wdv_341_customer_contacts`
  MODIFY `contact_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;