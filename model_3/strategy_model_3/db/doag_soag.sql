

## usaid_objective_agreement table ===============
 CREATE TABLE `usaid_objective_agreement` (
  `id` int(8) NOT NULL AUTO_INCREMENT, 
  `operating_unit_id` varchar(40) NOT NULL,
  `objective_agreement_type` enum('DOAG','SOAG') NOT NULL,
  `name` varchar(40) NOT NULL, 
  `description` varchar(255) NOT NULL,
  `approved_date` date,
  `added_on` date,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

## usaid_objective_agreement_relation table ===============
 CREATE TABLE `usaid_objective_agreement_relation` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `objective_agreement_id` int(8) NOT NULL,
  `relation_id` int(8) NOT NULL, 
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

## usaid_objective_agreement_document table ===============
 CREATE TABLE `usaid_objective_agreement_document` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `objective_agreement_id` int(8) NOT NULL,
  `document_name` varchar(50) NOT NULL, 
  `document_path` varchar(255) NOT NULL,
  `document_tags` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
