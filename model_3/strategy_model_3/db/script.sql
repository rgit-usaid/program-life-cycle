  

## usid_frame table=========
  CREATE TABLE `usaid_frame` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `frame_name` varchar(50) NOT NULL,
  `operating_unit_id` varchar(40) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frame_name`(`frame_name`),
  UNIQUE KEY `operating_unit_id`(`operating_unit_id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
  
## usaid_development_goal table ===============
 CREATE TABLE `usaid_development_goal` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `goal_description` varchar(50) NOT NULL,
  `goal_approval_date` date NOT NULL,
  `operating_unit_id` varchar(40) NOT NULL, 
  `frame_id` int(8) NOT NULL,
  `location` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


 ## usaid_do_ir_program_element table ===============
CREATE TABLE `usaid_do_ir_program_element` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `relation_id` int(8) NOT NULL,
  `program_element_id` int(8) NOT NULL, 
  `type` enum('Goal','Objective','IR','Sub_IR'),
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

## usaid_do_ir_indicator table ===============
  CREATE TABLE `usaid_do_ir_indicator` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `relation_id` int(8) NOT NULL, 
  `indicator_id` int(8) NOT NULL, 
  `type` enum('Goal','Objective','IR','Sub_IR'),
  `indicator_type` enum('Standard','Custom'),
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

  ## usaid_development_objective table ===============
 CREATE TABLE `usaid_development_objective` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `objective_description` varchar(50) NOT NULL,
  `objective_approval_date` date NOT NULL,
  `operating_unit_id` varchar(40) NOT NULL, 
  `frame_id` int(8) NOT NULL,
  `location` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
 
 ## usaid_intermediate_result table ===============
 CREATE TABLE `usaid_intermediate_result` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `ir_description` varchar(50) NOT NULL,
  `ir_approval_date` date NOT NULL,
  `operating_unit_id` varchar(40) NOT NULL, 
  `frame_id` int(8) NOT NULL,
  `location` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
 
 ## usaid_sub_intermediate_result table ===============
 CREATE TABLE `usaid_sub_intermediate_result` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `sub_ir_description` varchar(50) NOT NULL,
  `sub_ir_approval_date` date NOT NULL,
  `operating_unit_id` varchar(40) NOT NULL, 
  `frame_id` int(8) NOT NULL,
  `location` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

 ## usaid_data_relation table ===============
 CREATE TABLE `usaid_data_relation` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `frame_id` int(8) NOT NULL,
  `from_id` int(8) NOT NULL,
  `from_type` enum('GOAL','DO','IR','SUBIR','Project'),
  `to_id` int(8) NOT NULL,
  `to_type` enum('GOAL','DO','IR','SUBIR','Project'),
  `from_port` varchar(40) NOT NULL,
  `to_port` varchar(40) NOT NULL,
  `location` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

## usaid_frame_project_activity table ===============
 CREATE TABLE `usaid_frame_project_activity` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `project_activity_id` varchar(50) NOT NULL,
  `operating_unit_id` varchar(40) NOT NULL, 
  `frame_id` int(8) NOT NULL,
  `type` enum('Project','Activity'),
  `location` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

## usaid_association table ===============
 CREATE TABLE `usaid_association` (
  `id` int(10) NOT NULL AUTO_INCREMENT, 
  `gohashid` varchar(12) NOT NULL,
  `association_type` enum('Program Element','Standard Indicator','Custom Indicator','Project','Activity','Budget') NOT NULL,
  `association_id` varchar(20),
  `association_value` varchar(250),   
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

