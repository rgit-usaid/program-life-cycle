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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
-- Table structure for table `usaid_requisition`
--
CREATE TABLE IF NOT EXISTS `usaid_requisition` (
  `id` int(8) NOT NULL auto_increment,
  `requisition_number` varchar(20) NOT NULL,
  `create_date` date NOT NULL,
  `type` enum('Acquisition','Assistance') default 'Acquisition',
  `status` enum('Incomplete','Pre-Approved','Rejected','Returned','In-Process','Approved','Canceled','Requires Re-approval','Remove') default 'Incomplete',
  `period_of_performance_start_date` date default NULL,
  `period_of_performance_end_date` date default NULL,
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `requisition_number` (`requisition_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `status` enum('Active','Remove') default 'Active',
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `clin_number` (`clin_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `status` enum('Active','Remove') default 'Active',
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Table structure for table `usaid_requisition_award`
--
CREATE TABLE IF NOT EXISTS `usaid_requisition_award` (
  `id` int(8) NOT NULL auto_increment,
  `requisition_number` varchar(20) NOT NULL,
  `award_number` varchar(40) NOT NULL,
  `award_name` varchar(100) NOT NULL,
  `award_description` text NOT NULL,
  `award_date` date NOT NULL,
  `type` enum('CPFF','CPAF','CA','D2D','PIL','FP') default 'CPFF',
  `start_performance_period` date NOT NULL,
  `end_performance_period` date NOT NULL,
  `do_not_share` enum('N','Y') default 'N',
  `implementing_mechanism_type` varchar(100) default NULL,
  `operating_unit_id` varchar(20) default NULL,
  `vendor_id` int(8) default NULL,
  `employee_id` varchar(10) default NULL,
  `status` enum('Active','Remove') default 'Active',
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `award_number` (`award_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `usaid_requisition_award_clin`
--
CREATE TABLE IF NOT EXISTS `usaid_requisition_award_clin` (
  `id` int(8) NOT NULL auto_increment,
  `award_number` varchar(40) NOT NULL,
  `clin_number` varchar(40) NOT NULL,
  `clin_name` varchar(100) NOT NULL,
  `clin_description` text NOT NULL,
  `clin_amount` bigint(12) NOT NULL,
  `start_performance_period` date NOT NULL,
  `end_performance_period` date NOT NULL,
  `share` enum('N','Y') default 'N',
  `parent_clin_number` varchar(40) default NULL,
  `level` enum('1','2','3','4','5') default '1',
  `status` enum('Active','Remove') default 'Active',
  `added_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `clin_number` (`clin_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;