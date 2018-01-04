
## alter table date 09nov2016


ALTER TABLE `usaid_development_goal` ADD `location` VARCHAR(255) NOT NULL AFTER `frame_id`; 
ALTER TABLE `usaid_development_objective` ADD `location` VARCHAR(255) NOT NULL AFTER `frame_id`;

ALTER TABLE `usaid_intermediate_result` ADD `location` VARCHAR(255) NOT NULL AFTER `frame_id`;

ALTER TABLE `usaid_sub_intermediate_result` ADD `location` VARCHAR(255) NOT NULL AFTER `frame_id`;

ALTER TABLE `usaid_data_relation` ADD `location` TEXT NOT NULL AFTER `to_port`;

ALTER TABLE `usaid_data_relation` CHANGE `from_type` `from_type` ENUM('GOAL','DO','IR','SUBIR','Project') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `usaid_data_relation` CHANGE `to_type` `to_type` ENUM('GOAL','DO','IR','SUBIR','Project') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `usaid_frame` ADD `status` ENUM('Draft','Active','Archive') NOT NULL DEFAULT 'Draft' AFTER `frame_name`;

ALTER TABLE `usaid_frame` ADD `operating_unit_id` VARCHAR(40) NOT NULL AFTER `id`;

ALTER TABLE `usaid_development_goal` ADD `gohashid` VARCHAR(12) NULL AFTER `id`;
ALTER TABLE `usaid_development_goal` ADD UNIQUE(`gohashid`);
ALTER TABLE `usaid_development_objective` ADD `gohashid` VARCHAR(12) NULL AFTER `id`;
ALTER TABLE `usaid_development_objective` ADD UNIQUE(gohashid);
ALTER TABLE `usaid_do_ir_indicator` ADD `gohashid` VARCHAR(12) NULL AFTER `id`;
ALTER TABLE `usaid_do_ir_indicator` ADD UNIQUE(`gohashid`);
ALTER TABLE `usaid_do_ir_program_element` ADD `gohashid` VARCHAR(12) NULL AFTER `id`;
ALTER TABLE `usaid_do_ir_program_element` ADD UNIQUE(`gohashid`);
ALTER TABLE `usaid_intermediate_result` ADD `gohashid` VARCHAR(12) NULL AFTER `id`;
ALTER TABLE `usaid_intermediate_result` ADD UNIQUE(`gohashid`);
ALTER TABLE `usaid_sub_intermediate_result` ADD `gohashid` VARCHAR(12) NULL AFTER `id`;
ALTER TABLE `usaid_sub_intermediate_result` ADD UNIQUE(`gohashid`);

