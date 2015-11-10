--
-- Plug in CMDB
--

--
-- CREATE TABLE "CMDB_ENVIRONMENTS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_environments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_parent` int(255),
  `name` varchar(250) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_PROJECTS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_projects` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_parent` int(255),
  `name` varchar(250) NOT NULL,
  `comments` text,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEVICES"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_devices` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_parent` int(255),
  `name` varchar(250),
  `typename` varchar(250),
  `comments` text,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_PRO_ENV_MAP"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_pro_env_map` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_device` int(255) NOT NULL,
  `id_project` int(255) NOT NULL,
  `id_environment` int(255) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1001 ;


--
-- CREATE TABLE "CMDB_DEV_STATUS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_status` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_device` int(255),
  `status` varchar(20),
  `code` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS_CPU"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os_cpu` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `id_os` int(250),
  `logical_id` varchar(20),
  `max_clock_speed` int(250),
  `name` varchar(150),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `id_device` int(250),
  `os_type` varchar(20),
  `os_port` int(10),
  `os_name` varchar(150),
  `os_version` varchar(150),
  `os_architecture` varchar(10),
  `os_serial_number` varchar(100),
  `os_install_date` int(250) NOT NULL,
  `os_last_boot_time` int(250) NOT NULL,
  `os_memory_size` int(250) NOT NULL,
  `audited` int(1),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS_DISKS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os_disks` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `id_os` int(250),
  `name` varchar(150),
  `interfacetype` varchar(25),
  `disk_size` bigint unsigned,
  `caption` varchar(250),
  `firmwarerevision` varchar(50),
  `manufacturer` varchar(50),
  `model` varchar(150),
  `serialnumber` varchar(150),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS_FS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os_fs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_os` int(250),
  `name` varchar(150),
  `filesystem` varchar(25),
  `alias` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS_FS_UP"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os_fs_up` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_fs` bigint unsigned,
  `partition_size` bigint unsigned,
  `partition_freespace` bigint unsigned,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS_SERVICES"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os_services` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `id_os` int(250),
  `name` varchar(150),
  `path_name` varchar(150),
  `start_mode` varchar(20),
  `service_state` varchar(15),
  `owner` varchar(50),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DEV_OS_CREDS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_dev_os_creds` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `id_os` int(250),
  `user_name` varchar(250),
  `pass_word` varchar(250),
  `substitute_user_name` varchar(250),
  `substitute_pass_word` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DB_INSTANCES"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_db_instances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_os` bigint unsigned,
  `db_name` varchar(250),
  `db_type` varchar(30),
  `db_version` varchar(25),
  `db_port` varchar(25),
  `db_bin_path` varchar(250),
  `audited` int,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DB_INSTANCE_UP"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_db_instance_up` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_instance` bigint unsigned,
  `status` varchar(20),
  `code` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DB_INSTANCE_CREDS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_db_instance_creds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_instance` bigint unsigned,
  `user_name` varchar(250),
  `pass_word` varchar(250),
  `substitute_user_name` varchar(250),
  `substitute_pass_word` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DATABASES"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_databases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_instance` bigint unsigned,
  `name` varchar(150),
  `startup_time` int,
  `status` varchar(10),
  `archiver` varchar(10),
  `health_checked` int NOT NULL,
  `space_checked` int NOT NULL,
  `backup_checked` int NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DB_BACKUPS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_db_backups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_database` bigint unsigned,
  `backup_format` varchar(20),
  `backup_type` varchar(20),
  `backup_completion` int,
  `backup_status` varchar(50),
  `backup_size` int,
  `backup_file_path` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DB_FILEGROUPS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_db_filegroups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_database` bigint unsigned,
  `name` varchar(150),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- CREATE TABLE "CMDB_DB_FILEGROUP_STATUS"
--
CREATE TABLE IF NOT EXISTS `gl_cmdb_db_filegroup_status` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_filegroup` bigint unsigned,
  `space_size` int NOT NULL,
  `free_space` int NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;
