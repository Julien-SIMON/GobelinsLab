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
	version varchar(10) NOT NULL,
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

'CREATE TABLE IF NOT EXISTS <prefix>core_jobs (
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
	start_time INT UNSIGNED NOT NULL ,
	end_time   INT UNSIGNED NOT NULL ,
	days varchar(20),
	hours varchar(20),
	minutes varchar(20),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_job_polling (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_job BIGINT UNSIGNED NOT NULL ,
	start_time INT UNSIGNED NOT NULL ,
	end_time   INT UNSIGNED NOT NULL ,
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
	id_user BIGINT UNSIGNED NOT NULL ,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_translation (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_plugin BIGINT UNSIGNED NOT NULL,
	id_locale INT UNSIGNED NOT NULL,
	index_translation BIGINT UNSIGNED NOT NULL,
	translation TEXT,
	comment TEXT,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_user_caches (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_plugin BIGINT UNSIGNED NOT NULL,
	key varchar(30),
	user_id BIGINT UNSIGNED NOT NULL,
	expire_date INT UNSIGNED DEFAULT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_statistics (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	hash_ip varchar(25),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>core_stat_summary (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	key varchar(25),
	value varchar(25),
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

$databaseArray['ORACLE']['create_schema'] = array(
'CREATE TABLESPACE gldev DATAFILE \'E:\data\oracle\MYDB\TBS_GLDEV.DBF\' SIZE 2G AUTOEXTEND ON NEXT 1G',
'CREATE USER gldev IDENTIFIED BY "gldev" DEFAULT TABLESPACE gldev ACCOUNT UNLOCK',
'GRANT CONNECT TO gldev',
'GRANT RESOURCE TO gldev',
'GRANT UNLIMITED TABLESPACE TO gldev'
);

$databaseArray['ORACLE']['create_frame'] = array('CREATE TABLE <prefix>core_groups (
id        			INTEGER NOT NULL ,
name	 			VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_groups ADD CONSTRAINT <prefix>core_groupsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_users (
id        			INTEGER NOT NULL ,
name	 			VARCHAR2 (250) ,
avatar	 			VARCHAR2 (250) ,
mail	 			VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_users ADD CONSTRAINT <prefix>core_usersPK PRIMARY KEY (id)',

'CREATE TABLE <prefix>core_groups_users_map (
id        			INTEGER NOT NULL ,
group_id   			INTEGER NOT NULL ,
user_id   			INTEGER NOT NULL ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_groups_users_map ADD CONSTRAINT <prefix>core_groups_users_mapPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_user_auth_methods (
id        			INTEGER NOT NULL ,
name	 			VARCHAR2 (100) ,
icon	 			VARCHAR2 (250) ,
created_date		INTEGER NOT NULL ,
edited_date 		INTEGER NOT NULL ,
deleted_date		INTEGER NOT NULL ,
created_id  		INTEGER NOT NULL ,
edited_id   		INTEGER NOT NULL ,
deleted_id  		INTEGER NOT NULL
)', 
'ALTER TABLE <prefix>core_user_auth_methods ADD CONSTRAINT <prefix>core_user_auth_methodsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_user_auths (
id        			INTEGER NOT NULL ,
auth_id        		INTEGER NOT NULL ,
user_id        		INTEGER NOT NULL ,
password 			VARCHAR2 (250) ,
avatar	 			VARCHAR2 (250) ,
lastname 			VARCHAR2 (250) ,
firstname 			VARCHAR2 (250) ,
mail	 			VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_user_auths ADD CONSTRAINT <prefix>core_user_authsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_plugins
(
id        			INTEGER NOT NULL ,
name        		VARCHAR2 (250) ,
version        		VARCHAR2 (10) ,
activated        	INTEGER NOT NULL ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_plugins ADD CONSTRAINT <prefix>core_pluginsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_pages (
id        			INTEGER NOT NULL ,
plugin_id 			INTEGER NOT NULL ,
name        		VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_pages ADD CONSTRAINT <prefix>core_pagesPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_tables (
id        			INTEGER NOT NULL ,
name        		VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' , 
'ALTER TABLE <prefix>core_tables ADD CONSTRAINT <prefix>core_tablesPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_objects (
id        			INTEGER NOT NULL ,
id_table   			INTEGER NOT NULL ,
id_ext     			INTEGER NOT NULL ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_objects ADD CONSTRAINT <prefix>core_objectsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_access (
id        			INTEGER NOT NULL ,
id_source  			INTEGER NOT NULL ,
id_target  			INTEGER NOT NULL ,
secure_level 		INTEGER NOT NULL ,
created_date  		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_access ADD CONSTRAINT <prefix>core_accessPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_parameters (
id        			INTEGER NOT NULL ,
id_plugin  			INTEGER NOT NULL ,
name  				VARCHAR2 (250) ,
parameter_value		VARCHAR2 (250) ,
default_value		VARCHAR2 (250) ,
created_date  		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_parameters ADD CONSTRAINT <prefix>core_parametersPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_processus (
id        			INTEGER NOT NULL ,
id_parent 			INTEGER ,
cmd					VARCHAR2 (250) ,
args				VARCHAR2 (250) ,
status	 			VARCHAR2 (12) ,
percent 			INTEGER NOT NULL ,
comments			VARCHAR2 (250) ,
timeout 			INTEGER NOT NULL,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_processus ADD CONSTRAINT <prefix>core_processusPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_jobs (
id        			INTEGER NOT NULL ,
id_plugin 			INTEGER NOT NULL ,
page				VARCHAR2 (250) ,
last_run_pid		INTEGER NOT NULL ,
last_run 			INTEGER NOT NULL ,
created_date		INTEGER NOT NULL ,
edited_date 		INTEGER NOT NULL ,
deleted_date		INTEGER NOT NULL ,
created_id  		INTEGER NOT NULL ,
edited_id   		INTEGER NOT NULL ,
deleted_id  		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_job ADD CONSTRAINT <prefix>core_jobPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_job_scheduled (
id        			INTEGER NOT NULL ,
id_job	 			INTEGER NOT NULL ,
start_time			INTEGER NOT NULL ,
end_time  			INTEGER NOT NULL ,
days 				VARCHAR2 (20),
hours 				VARCHAR2 (20),
minutes				VARCHAR2 (20),
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_job_scheduled ADD CONSTRAINT <prefix>core_job_scheduledPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_job_polling (
id        			INTEGER NOT NULL ,
id_job	 			INTEGER NOT NULL ,
start_time 			INTEGER NOT NULL ,
end_time 			INTEGER NOT NULL ,
polling_time		INTEGER NOT NULL ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_job_polling ADD CONSTRAINT <prefix>core_job_pollingPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_event_logs (
id        			INTEGER NOT NULL ,
id_plugin 			INTEGER NOT NULL ,
id_user 			INTEGER NOT NULL ,
log_statement		VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_event_logs ADD CONSTRAINT <prefix>core_event_logsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_locale (
id        			INTEGER NOT NULL ,
short_name			VARCHAR2 (10) ,
long_name			VARCHAR2 (10) ,
flag_path			VARCHAR2 (250) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_locale ADD CONSTRAINT <prefix>core_localePK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_translation (
id        			INTEGER NOT NULL ,
id_plugin  			INTEGER NOT NULL ,
id_locale  			INTEGER NOT NULL ,
index_translation	INTEGER NOT NULL ,
translation 		CLOB ,
comment 			CLOB ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_translation ADD CONSTRAINT <prefix>core_translationPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_user_caches (
id        			INTEGER NOT NULL ,
id_plugin  			INTEGER NOT NULL ,
key					VARCHAR2 (30) ,
user_id  			INTEGER NOT NULL ,
expire_date			INTEGER NOT NULL ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_user_caches ADD CONSTRAINT <prefix>core_user_cachesPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_statistics (
id        			INTEGER NOT NULL ,
hash_ip				VARCHAR2 (25) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_statistics ADD CONSTRAINT <prefix>core_statisticsPK PRIMARY KEY (id)' ,

'CREATE TABLE <prefix>core_stat_summary (
id        			INTEGER NOT NULL ,
jey					VARCHAR2 (25) ,
value 				VARCHAR2 (25) ,
created_date 		INTEGER NOT NULL ,
edited_date  		INTEGER NOT NULL ,
deleted_date 		INTEGER NOT NULL ,
created_id   		INTEGER NOT NULL ,
edited_id    		INTEGER NOT NULL ,
deleted_id   		INTEGER NOT NULL
)' ,
'ALTER TABLE <prefix>core_stat_summary ADD CONSTRAINT <prefix>core_stat_summaryPK PRIMARY KEY (id)'
);
?>