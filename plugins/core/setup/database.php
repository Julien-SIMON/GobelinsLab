<?php

// 
$databaseArray['MYSQL']['create_frame'] = array();
$databaseArray['MYSQL']['insert_default_rows'] = array();

$databaseArray['ORACLE']['create_schema'] = array();
$databaseArray['ORACLE']['create_frame'] = array();
$databaseArray['ORACLE']['create_sequences'] = array();
$databaseArray['ORACLE']['create_foreign_key'] = array();
$databaseArray['ORACLE']['insert_default_rows'] = array();
$databaseArray['ORACLE']['create_index'] = array();

// ---------------------------------------------------------------------------------------- //
// MySQL
// ---------------------------------------------------------------------------------------- //

$databaseArray['MYSQL']['create_frame'] = array(
'CREATE TABLE IF NOT EXISTS <prefix>core_groups (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name varchar(250) NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_users (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name varchar(250) NOT NULL,
	avatar varchar(250) DEFAULT NULL,
	mail varchar(250) DEFAULT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_groups_users_map (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	group_id BIGINT UNSIGNED NOT NULL,
	user_id BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_user_auth_methods (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name varchar(100) NOT NULL,
	icon varchar(250) DEFAULT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_user_auths (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	auth_id BIGINT UNSIGNED NOT NULL,
	user_id BIGINT UNSIGNED NOT NULL,
	password varchar(250) DEFAULT NULL,
	avatar varchar(250) DEFAULT NULL,
	lastname varchar(250) DEFAULT NULL,
	firstname varchar(250) DEFAULT NULL,
	mail varchar(250) DEFAULT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_plugins (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name varchar(250) NOT NULL,
	activated INT NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_pages (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	plugin_id BIGINT UNSIGNED NOT NULL,
	name varchar(250) NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_tables (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name varchar(250) NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1001' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_objects (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_table BIGINT UNSIGNED NOT NULL,
	id_ext BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10001' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_access (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_source BIGINT UNSIGNED NOT NULL,
	id_target BIGINT UNSIGNED NOT NULL,
	secure_level INT NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10001' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_parameters (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_plugin BIGINT UNSIGNED NOT NULL,
	name varchar(250) NOT NULL,
	parameter_value varchar(250) NOT NULL,
	default_value varchar(250) NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10001' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_processus (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_parent BIGINT UNSIGNED,
	cmd varchar(250),
	args varchar(250),
	status varchar(12),
	percent INT,
	comments varchar(250),
	timeout INT NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_job (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_plugin BIGINT UNSIGNED NOT NULL ,
	page varchar(250),
	last_run_pid INT NOT NULL ,
	last_run INT NOT NULL ,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_job_scheduled (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_job BIGINT UNSIGNED NOT NULL ,
	start_day INT UNSIGNED NOT NULL ,
	start_time INT UNSIGNED NOT NULL ,
	polling_trigger MEDIUMTEXT ,
	polling_time INT NOT NULL ,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_event_logs (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_plugin BIGINT UNSIGNED NOT NULL ,
	log_statement varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_locale (
	id int UNSIGNED NOT NULL AUTO_INCREMENT,
	short_name varchar(10),
	long_name varchar(10),
	flag_path varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101'
);

// ---------------------------------------------------------------------------------------- //
// Oracle
// ---------------------------------------------------------------------------------------- //



?>