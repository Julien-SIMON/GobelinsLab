--
-- CREATE TABLE "GROUPS"
--
CREATE TABLE IF NOT EXISTS `gl_core_groups` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
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
-- CREATE TABLE "USERS"
--
CREATE TABLE IF NOT EXISTS `gl_core_users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `mail` varchar(250) DEFAULT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101;

--
-- CREATE TABLE "GROUPS_USERS_MAP"
--
CREATE TABLE IF NOT EXISTS `gl_core_groups_users_map` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `group_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101;

--
-- CREATE TABLE "USER_AUTH_METHODS"
--
CREATE TABLE IF NOT EXISTS `gl_core_user_auth_methods` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon` varchar(250) DEFAULT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101;

--
-- CREATE TABLE "USER_AUTHS"
--
CREATE TABLE IF NOT EXISTS `gl_core_user_auths` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `auth_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `lastname` varchar(250) DEFAULT NULL,
  `firstname` varchar(250) DEFAULT NULL,
  `mail` varchar(250) DEFAULT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101;

--
-- CREATE TABLE "PLUGINS"
--
CREATE TABLE IF NOT EXISTS `gl_core_plugins` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `activated` int(1) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101;

--
-- CREATE TABLE "PAGES"
--
CREATE TABLE IF NOT EXISTS `gl_core_pages` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(255) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101;

--
-- CREATE TABLE "TABLES"
--
CREATE TABLE IF NOT EXISTS `gl_core_tables` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1001;

--
-- CREATE TABLE "OBJECTS"
--
CREATE TABLE IF NOT EXISTS `gl_core_objects` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_table` int(255) NOT NULL,
  `id_ext` int(255) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10001;

--
-- CREATE TABLE "SECURITY"
--
CREATE TABLE IF NOT EXISTS `gl_core_access` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_source` int(255) NOT NULL,
  `id_target` int(255) NOT NULL,
  `secure_level` int(3) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10001;

--
-- CREATE TABLE "PARAMETERS"
--
CREATE TABLE IF NOT EXISTS `gl_core_parameters` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_plugin` int(255) NOT NULL,
  `name` varchar(250) NOT NULL,
  `parameter_value` varchar(250) NOT NULL,
  `default_value` varchar(250) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10001;

--
-- Création de la table PROCESSUS
--
CREATE TABLE IF NOT EXISTS `gl_core_processus` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_parent` int(255),
  `cmd` varchar(250),
  `args` varchar(250),
  `status` varchar(12),
  `percent` int(3),
  `comments` varchar(250),
  `timeout` int(250) NOT NULL,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101;

--
-- Création de la table JOB
--
CREATE TABLE IF NOT EXISTS `gl_core_job` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_plugin` int(255) NOT NULL ,
  `page` varchar(250),
  `last_run_pid` int(255) NOT NULL ,
  `last_run` int(255) NOT NULL ,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101;

--
-- Création de la table JOB_SCHEDULED
--
CREATE TABLE IF NOT EXISTS `gl_core_job_scheduled` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_job` int(255) NOT NULL ,
  `start_day` int(255) NOT NULL ,
  `start_time` int(255) NOT NULL ,
  `polling_trigger` MEDIUMTEXT ,
  `polling_time` int(255) NOT NULL ,
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101;

--
-- Création de la table EVENT_LOGS
--
CREATE TABLE IF NOT EXISTS `gl_core_event_logs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_plugin` int(255) NOT NULL ,
  `log_statement` varchar(250),
  `created_date` int(255) NOT NULL,
  `edited_date` int(255) NOT NULL,
  `deleted_date` int(255) NOT NULL,
  `created_id` int(255) NOT NULL,
  `edited_id` int(255) DEFAULT NULL,
  `deleted_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101;
