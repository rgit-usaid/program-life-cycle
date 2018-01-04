-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 04, 2016 at 10:50 AM
-- Server version: 5.0.96-community
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rgdemode_glaas`
--

-- --------------------------------------------------------

--
-- Table structure for table `usaid_requisition`
--

CREATE TABLE IF NOT EXISTS `usaid_requisition` (
  `id` int(8) NOT NULL auto_increment,
  `requisition_number` varchar(20) NOT NULL,
  `create_date` date NOT NULL,
  `type` enum('Acquisition','Assistance') default 'Acquisition',
  `status` enum('Incomplete','Pre-Approved','Rejected','Returned','In-Process','Approved','Canceled','Requires Re-approval') default 'Incomplete',
  `period_of_performance_start_date` date default NULL,
  `period_of_performance_end_date` date default NULL,
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `requisition_number` (`requisition_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `usaid_requisition`
--

INSERT INTO `usaid_requisition` (`id`, `requisition_number`, `create_date`, `type`, `status`, `period_of_performance_start_date`, `period_of_performance_end_date`, `added_on`) VALUES
(1, '1001-999-99-999990', '2016-09-29', 'Acquisition', 'Pre-Approved', '2016-09-30', '2016-10-10', '2016-09-29 11:21:45'),
(2, '1001-999-99-999991', '2016-09-20', 'Assistance', 'Incomplete', '2016-10-05', '2016-10-20', '2016-09-29 11:22:05'),
(3, 'RECQ-111-16-000002', '2016-08-15', 'Assistance', 'Approved', '2016-09-15', '2017-05-01', '2016-09-29 11:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `usaid_requisition_clin`
--

CREATE TABLE IF NOT EXISTS `usaid_requisition_clin` (
  `id` int(8) NOT NULL auto_increment,
  `requisition_number` varchar(20) NOT NULL,
  `clin_number` varchar(40) NOT NULL,
  `clin_name` varchar(100) NOT NULL,
  `clin_description` text NOT NULL,
  `clin_amount` bigint(12) NOT NULL,
  `start_performance_period` date NOT NULL,
  `end_performance_period` date NOT NULL,
  `share` enum('N','Y') default 'N',
  `parent_clin_number` varchar(40) default NULL,
  `level` enum('1','2','3','4','5') default '1',
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `clin_number` (`clin_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `usaid_requisition_clin`
--

INSERT INTO `usaid_requisition_clin` (`id`, `requisition_number`, `clin_number`, `clin_name`, `clin_description`, `clin_amount`, `start_performance_period`, `end_performance_period`, `share`, `parent_clin_number`, `level`, `added_on`) VALUES
(1, 'RECQ-111-16-000002', 'RECQ-111-16-000002-001', 'Test CLIN Name', 'Test Description', 500, '2016-09-30', '2016-10-15', 'N', NULL, '1', '2016-09-30 11:14:16'),
(2, 'RECQ-111-16-000002', 'RECQ-111-16-000002-002', 'Medical Supplies1', 'Purchase of medical supplies.  These will be distributed to clinics.2', 190003, '2016-10-03', '2018-03-30', 'N', NULL, '1', '2016-10-03 09:31:51'),
(3, 'RECQ-111-16-000002', 'RECQ-111-16-000002-003', 'Medical Equipment', 'Medical Equipment  These will be distributed to clinics.', 23456, '0000-00-00', '0000-00-00', '', NULL, '1', '2016-09-30 12:13:53'),
(4, 'RECQ-111-16-000002', 'RECQ-111-16-000002-001-001', 'Test CLIN Name2', 'dsfgdfgdf', 344, '2016-10-10', '2016-10-18', 'N', 'RECQ-111-16-000002-001', '2', '2016-10-03 12:06:00'),
(5, 'RECQ-111-16-000002', 'RECQ-111-16-000002-001-002', 'CLIN L-2 Test recrod', '', 200, '2016-10-03', '2016-10-28', 'Y', 'RECQ-111-16-000002-001', '2', '2016-10-03 12:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `usaid_requisition_clin_budget`
--

CREATE TABLE IF NOT EXISTS `usaid_requisition_clin_budget` (
  `id` int(8) NOT NULL auto_increment,
  `budget_number` varchar(40) NOT NULL,
  `cost_code` varchar(40) NOT NULL,
  `code_description` text NOT NULL,
  `budget_amount` bigint(12) NOT NULL,
  `budget_type` enum('Requisition','Clin','Award') default 'Clin',
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `usaid_requisition_clin_budget`
--

INSERT INTO `usaid_requisition_clin_budget` (`id`, `budget_number`, `cost_code`, `code_description`, `budget_amount`, `budget_type`, `added_on`) VALUES
(1, 'RECQ-111-16-000002-001', '543', 'Test Code desc', 400, 'Clin', '2016-09-30 11:14:16'),
(2, 'RECQ-111-16-000002-001', '544', 'Test2', 100, 'Clin', '2016-09-30 11:14:16'),
(3, 'RECQ-111-16-000002-003', '150', 'Direct Costs', 20000, 'Clin', '2016-09-30 12:13:53'),
(4, 'RECQ-111-16-000002-003', '160', 'Indirect Costts', 3456, 'Clin', '2016-09-30 12:13:53'),
(5, 'RECQ-111-16-000002-002', '120', 'Direct Costs', 20, 'Clin', '2016-10-03 09:29:36'),
(6, 'RECQ-111-16-000002-002', '130', 'Indirect Cost', 30, 'Clin', '2016-10-03 09:29:36'),
(7, 'RECQ-111-16-000002-001-001', '12', 'sdfdsg', 20, 'Clin', '2016-10-03 11:50:24'),
(8, 'RECQ-111-16-000002-001-001', '22', 'sdfdsg', 46, 'Clin', '2016-10-03 12:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `usaid_vendor`
--

CREATE TABLE IF NOT EXISTS `usaid_vendor` (
  `id` int(8) NOT NULL auto_increment,
  `DUNS_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address_street` varchar(100) default NULL,
  `address_city` varchar(50) default NULL,
  `address_state_province` varchar(50) default NULL,
  `address_country` varchar(50) default NULL,
  `address_location_code` varchar(20) default NULL,
  `contact_name` varchar(100) default NULL,
  `email_address` varchar(100) default NULL,
  `phone_number` varchar(15) default NULL,
  `direct_deposit_number` varchar(40) default NULL,
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `DUNS_number` (`DUNS_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `usaid_vendor`
--

INSERT INTO `usaid_vendor` (`id`, `DUNS_number`, `name`, `address_street`, `address_city`, `address_state_province`, `address_country`, `address_location_code`, `contact_name`, `email_address`, `phone_number`, `direct_deposit_number`, `added_on`) VALUES
(19, '201600001', 'World Bank Group', '1855 Pennsylvania Ave, NW', 'Washington', 'DC', 'USA', '20035', 'Ban Ki Moon', 'BKMoon@worldbankgroup.org', '202 665-4476', '009-22876-wbg', '2016-09-29 20:24:58'),
(20, '201600002', 'World Food Program USA', '1725 I St, NW', 'Washington', 'DC', 'USA', '20006', 'General inquiries', 'inquiries@worldfood.org', '202 555-7746', '990-009-22533', '2016-09-29 20:27:46'),
(21, '201600003', 'Chemonics', '1717 H St NW #1', 'Washington', 'DC', 'USA', '20006', 'Millard Fillmore', 'mfillmore@chemonics.com', '202 555-0091', '09-8874-987', '2016-09-29 20:29:45'),
(22, '201600004', 'The Partnership for Supply Chain Management', '1616 Fort Myer Drive 12th Floor', 'Arlington', 'Virginia', 'USA', '22209', 'Angel Rodriquez', 'angel.rogriguez@PSCM.org', '+1-571-227-8600', '009-98-w34', '2016-09-29 20:33:25'),
(23, '201600005', 'FHI 360', '359 Blackwell Street, Suite 200', 'Durham', 'NC', 'USA', '27701', 'Bill Durham', 'Bill.Durham@FHI360.org', '1.919.544.7040', '5544-6t5-664', '2016-09-29 20:35:39'),
(24, '201600006', 'United Nations Children Fund / UNICEF', 'UNICEF House 3 United Nations Plaza', 'New York', 'NY', 'USA', '10017', 'Ming Tze Shing', 'Mshing@unicef.org', '212 555-8873', '009-wed-00987', '2016-09-29 20:42:24'),
(25, '201600007', 'JSI / John Snow Inc', '44 Farnsworth Street', 'Boston', 'MA', 'USA', '02210', 'Jane Snow', 'jane.snow@jsi.com', '617.482.9485', '12-2211-2212', '2016-09-29 20:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `usaid_vendor_local_address`
--

CREATE TABLE IF NOT EXISTS `usaid_vendor_local_address` (
  `id` int(8) NOT NULL auto_increment,
  `vendor_id` int(8) NOT NULL,
  `local_contact_name` varchar(100) default NULL,
  `local_contact_email` varchar(100) default NULL,
  `local_phone_number` varchar(15) default NULL,
  `local_address_street` varchar(100) default NULL,
  `local_address_city` varchar(50) default NULL,
  `local_address_state_province` varchar(50) default NULL,
  `local_address_country` varchar(50) NOT NULL,
  `local_address_location_code` varchar(20) default NULL,
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `usaid_vendor_local_address`
--

INSERT INTO `usaid_vendor_local_address` (`id`, `vendor_id`, `local_contact_name`, `local_contact_email`, `local_phone_number`, `local_address_street`, `local_address_city`, `local_address_state_province`, `local_address_country`, `local_address_location_code`, `added_on`) VALUES
(23, 23, 'Mary Washington', 'mwashington@fhi360.org', '202 555-2213', '2400 Connecticut Ave, NW #1100', 'Washington', 'DC', 'USA', '20008', '2016-09-29 20:36:54'),
(24, 25, 'Blanca Snow', 'blanca.snow', '+34 98-87460 -7', '120 Cielo Rd', 'Lima', '', 'Pery', '', '2016-09-29 20:46:08');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         