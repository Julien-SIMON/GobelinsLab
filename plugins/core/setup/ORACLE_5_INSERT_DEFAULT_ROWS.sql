--
-- INSERT DEFAULT ROWS FOR "GROUPS" TABLE
--
INSERT INTO GLDEV.gl_core_groups ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', 'admins', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_groups ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '2', 'members', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_groups ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '3', 'guests', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "USERS" TABLE
--
INSERT INTO GLDEV.gl_core_users ( id, name, avatar, mail, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', 'admin', 'lib/avatars/brain.jpg', '', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "GROUPS_USERS_MAP" TABLE
--
INSERT INTO GLDEV.gl_core_groups_users_map ( id, group_id, user_id, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', '1', '1', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "USER_AUTH_METHODS" TABLE
--
INSERT INTO GLDEV.gl_core_user_auth_methods ( id, name, icon, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', 'LOCAL', 'icon-log-in', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_user_auth_methods ( id, name, icon, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '2', 'LDAP', 'icon-sitemap', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_user_auth_methods ( id, name, icon, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '5', 'FACEBOOK', 'icon-facebook-square', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_user_auth_methods ( id, name, icon, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '6', 'GOOGLE', 'icon-google-plus-1', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "USER_AUTHS" TABLE
--
INSERT INTO GLDEV.gl_core_user_auths ( id, auth_id, user_id, password, avatar, lastname, firstname, mail, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', '1', '1', 'admin', '', '', '', '', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "PLUGINS" TABLE
--
INSERT INTO GLDEV.gl_core_plugins ( id, name, activated, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', 'core', '1', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "TABLES" TABLE
--
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', 'core_plugins', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '2', 'core_tables', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '3', 'core_objects', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '4', 'core_user_auths', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '5', 'core_user_auth_methods', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '6', 'core_users', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '7', 'core_groups', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '8', 'core_pages', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_tables ( id, name, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '10', 'core_processus', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "OBJECTS" TABLE
--
-- plugins
INSERT INTO GLDEV.gl_core_objects ( id, id_table, id_ext, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', '1', '1', 0, 0, 0, 0, 0, 0);
-- groups
INSERT INTO GLDEV.gl_core_objects ( id, id_table, id_ext, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '21', '7', '1', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_objects ( id, id_table, id_ext, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '22', '7', '2', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_objects ( id, id_table, id_ext, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '23', '7', '3', 0, 0, 0, 0, 0, 0);
-- users
INSERT INTO GLDEV.gl_core_objects ( id, id_table, id_ext, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '51', '6', '1', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "ACCESS" TABLE
--
INSERT INTO GLDEV.gl_core_access ( id, id_source, id_target, secure_level, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', '21', '1', '100', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_access ( id, id_source, id_target, secure_level, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '2', '22', '1', '10', 0, 0, 0, 0, 0, 0);

--
-- INSERT DEFAULT ROWS FOR "PARAMETERS" TABLE
--
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '1', '1', 'DEFAULT_GROUP', 'members', 'members', 0, 0, 0, 0, 0, 0);

INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '20', '1', 'LOCAL_REGISTER', 'true', 'true', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '21', '1', 'LOCAL_CONNEXION', 'true', 'true', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '22', '1', 'LDAP_REGISTER', 'false', 'false', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '23', '1', 'LDAP_CONNEXION', 'false', 'false', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '24', '1', 'FACEBOOK_REGISTER', 'false', 'false', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '25', '1', 'FACEBOOK_CONNEXION', 'false', 'false', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '26', '1', 'GOOGLE_REGISTER', 'false', 'false', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '27', '1', 'GOOGLE_CONNEXION', 'false', 'false', 0, 0, 0, 0, 0, 0);

INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '50', '1', 'LDAP_DEFAULT_DOMAIN', 'FR', 'FR', 0, 0, 0, 0, 0, 0);
INSERT INTO GLDEV.gl_core_parameters ( id, id_plugin, name, parameter_value, default_value, created_date, edited_date, deleted_date, created_id, edited_id, deleted_id) VALUES ( '51', '1', 'LDAP_SERVERS', 'ldap://', 'ldap://', 0, 0, 0, 0, 0, 0);