<?php

// 
$databaseArray['MYSQL']['create_schema'] = array();
$databaseArray['MYSQL']['create_frame'] = array();
$databaseArray['MYSQL']['create_sequences'] = array();
$databaseArray['MYSQL']['create_foreign_key'] = array();
$databaseArray['MYSQL']['insert_default_rows'] = array();
$databaseArray['MYSQL']['create_index'] = array();

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
'CREATE TABLE IF NOT EXISTS <prefix>kb_class (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_parent BIGINT UNSIGNED NOT NULL,
	name varchar(25),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_class_doc_map (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_class BIGINT UNSIGNED NOT NULL,
	id_doc BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_index (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_locale INT UNSIGNED DEFAULT NULL,
	published_date INT UNSIGNED DEFAULT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_sources (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_doc BIGINT UNSIGNED NOT NULL,
	name varchar(250),
	link varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_files (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_doc_version BIGINT UNSIGNED NOT NULL,
	path varchar(250),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1001' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_commit (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_doc_index BIGINT UNSIGNED NOT NULL,
	id_locale int UNSIGNED NOT NULL,
	version varchar(10),
	translate_status varchar(20),
	title varchar(250),
	subtitle varchar(250),
	content longtext,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_authors (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_doc_index BIGINT UNSIGNED NOT NULL,
	id_user BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_translators (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_doc_index BIGINT UNSIGNED NOT NULL,
	id_user BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_ref_compatible (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name varchar(30),
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_compatible_map (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_compatible BIGINT UNSIGNED NOT NULL,
	id_doc BIGINT UNSIGNED NOT NULL,
	created_date INT UNSIGNED DEFAULT NULL,
	edited_date INT UNSIGNED DEFAULT NULL,
	deleted_date INT UNSIGNED DEFAULT NULL,
	created_id BIGINT UNSIGNED DEFAULT NULL,
	edited_id BIGINT UNSIGNED DEFAULT NULL,
	deleted_id BIGINT UNSIGNED DEFAULT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101' ,

'CREATE TABLE IF NOT EXISTS <prefix>kb_doc_nocompatible_map (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_compatible BIGINT UNSIGNED NOT NULL,
	id_doc BIGINT UNSIGNED NOT NULL,
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