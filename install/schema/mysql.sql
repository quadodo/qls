DROP TABLE IF EXISTS `{database_prefix}users`;

CREATE TABLE `users`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `code` VARCHAR(32) DEFAULT '' NOT NULL,
  `active` TINYINT(1) DEFAULT '0' NOT NULL,
  `activation_time` INT(11) DEFAULT '0' NOT NULL,
  `last_login` INT(11) DEFAULT '0' NOT NULL,
  `last_session` VARCHAR(40) DEFAULT '' NOT NULL,
  `blocked` TINYINT(1) DEFAULT '0' NOT NULL,
  `tries` TINYINT(2) DEFAULT '0' NOT NULL,
  `last_try` INT(11) DEFAULT '0' NOT NULL,
  `last_action` INT(11) DEFAULT '0' NOT NULL,
  `group_id` INT(11) DEFAULT '0' NOT NULL,
  `mask_id` INT(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY(`id`),
  INDEX `users_idx` (`username`),
  INDEX `users_idx2` (`code`),
  INDEX `users_idx3` (`last_login`),
  INDEX `users_idx4` (`last_session`),
  INDEX `users_idx5` (`last_try`),
  INDEX `users_idx6` (`activation_time`),
  INDEX `users_idx7` (`last_action`)
);

DROP TABLE IF EXISTS `{database_prefix}sessions`;

CREATE TABLE `{database_prefix}sessions`(
	`user_id` INT(11) NOT NULL,
	`value` VARCHAR(40) DEFAULT '' NOT NULL,
	`time` INT(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY(`user_id`),
	INDEX `sessions_idx` (`value`,`time`)
);

DROP TABLE IF EXISTS `{database_prefix}config`;

CREATE TABLE `{database_prefix}config`(
  `id` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  PRIMARY KEY(`id`)
);

DROP TABLE IF EXISTS `{database_prefix}registration_fields`;

CREATE TABLE `{database_prefix}registration_fields`(
  `id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) DEFAULT '' NOT NULL,
  `maxvalue` VARCHAR(255) NOT NULL,
  `minvalue` VARCHAR(255) NOT NULL,
  `confirm` TINYINT(1) DEFAULT 0 NOT NULL,
  `regex` VARCHAR(255) DEFAULT '' NOT NULL
);