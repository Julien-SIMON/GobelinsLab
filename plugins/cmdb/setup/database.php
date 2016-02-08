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
'CREATE TABLE IF NOT EXISTS <prefix>cmdb_environments (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_parent BIGINT UNSIGNED NOT NULL,
	name varchar(250) NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_projects (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_parent BIGINT UNSIGNED NOT NULL,
	name varchar(250) NOT NULL,
	comments text,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_devices (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_parent BIGINT UNSIGNED NOT NULL,
	name varchar(250),
	typename varchar(250),
	comments text,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_pro_env_map (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_device BIGINT UNSIGNED NOT NULL,
	id_project BIGINT UNSIGNED NOT NULL,
	id_environment BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1001' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_status (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_device BIGINT UNSIGNED NOT NULL,
	status varchar(20),
	code varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os_cpu (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_os BIGINT UNSIGNED NOT NULL,
	logical_id varchar(20),
	max_clock_speed int,
	name varchar(150),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_device BIGINT UNSIGNED NOT NULL,
	os_type varchar(20),
	os_port int,
	os_name varchar(150),
	os_version varchar(150),
	os_architecture varchar(10),
	os_serial_number varchar(100),
	os_install_date int NOT NULL,
	os_last_boot_time int NOT NULL,
	os_memory_size int NOT NULL,
	audited int,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os_disks (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_os BIGINT UNSIGNED NOT NULL,
	name varchar(150),
	interfacetype varchar(25),
	disk_size BIGINT UNSIGNED,
	caption varchar(250),
	firmwarerevision varchar(50),
	manufacturer varchar(50),
	model varchar(150),
	serialnumber varchar(150),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os_fs (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_os BIGINT UNSIGNED NOT NULL,
	name varchar(150),
	filesystem varchar(25),
	alias varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os_fs_up (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_fs BIGINT UNSIGNED NOT NULL,
	partition_size BIGINT UNSIGNED,
	partition_freespace BIGINT UNSIGNED,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os_services (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_os BIGINT UNSIGNED NOT NULL,
	name varchar(150),
	path_name varchar(150),
	start_mode varchar(20),
	service_state varchar(15),
	owner varchar(50),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_dev_os_creds (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_os BIGINT UNSIGNED NOT NULL,
	user_name varchar(250),
	pass_word varchar(250),
	substitute_user_name varchar(250),
	substitute_pass_word varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_db_instances (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_os BIGINT UNSIGNED NOT NULL,
	db_name varchar(250),
	db_type varchar(30),
	db_version varchar(25),
	db_port varchar(25),
	db_bin_path varchar(250),
	audited int,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_db_instance_up (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_instance BIGINT UNSIGNED,
	status varchar(20),
	code varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_db_instance_creds (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_instance BIGINT UNSIGNED,
	user_name varchar(250),
	pass_word varchar(250),
	substitute_user_name varchar(250),
	substitute_pass_word varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_databases (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_instance BIGINT UNSIGNED,
	name varchar(150),
	startup_time int,
	status varchar(10),
	archiver varchar(10),
	health_checked int NOT NULL,
	space_checked int NOT NULL,
	backup_checked int NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_db_backups (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_instance BIGINT UNSIGNED,
	backup_format varchar(20),
	backup_type varchar(20),
	backup_completion int,
	backup_status varchar(50),
	backup_size int,
	backup_file_path varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_db_filegroups (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_database BIGINT UNSIGNED,
	name varchar(150),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>cmdb_db_filegroup_status (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_filegroup BIGINT UNSIGNED,
	space_size int NOT NULL,
	free_space int NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101'
);

// ---------------------------------------------------------------------------------------- //
// Oracle
// ---------------------------------------------------------------------------------------- //



?>