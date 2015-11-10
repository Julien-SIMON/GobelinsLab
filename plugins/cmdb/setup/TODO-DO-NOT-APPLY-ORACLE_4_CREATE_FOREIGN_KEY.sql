﻿--
-- Création de la table DEVICES
--

--
-- Création de la table DEVICE_STATUS
--
ALTER TABLE TOOLZ.CMDB_DEVICE_STATUS ADD CONSTRAINT CMDB_DEVICE_STATUS_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DEVICES_CPUS
--
ALTER TABLE TOOLZ.CMDB_DEVICES_CPUS ADD CONSTRAINT CMDB_DEVICES_CPUS_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DEVICES_OS
--
ALTER TABLE TOOLZ.CMDB_DEVICES_OS ADD CONSTRAINT CMDB_DEVICES_OS_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DEVICE_DISKS
--
ALTER TABLE TOOLZ.CMDB_DEVICE_DISKS ADD CONSTRAINT CMDB_DEVICE_DISKS_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

-- Création de la table DEVICE_PARTITIONS
--
ALTER TABLE TOOLZ.CMDB_DEVICE_PARTITIONS ADD CONSTRAINT CMDB_DEVICE_PARTITIONS_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DEVICES_SERVICES
--
ALTER TABLE TOOLZ.CMDB_DEVICES_SERVICES ADD CONSTRAINT CMDB_DEVICES_SERVICES_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DEVICE_CREDENTIALS
--
ALTER TABLE TOOLZ.CMDB_DEVICE_CREDENTIALS ADD CONSTRAINT CMDB_DEVICE_CREDENTIALS_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DEVICES_PROJECTS_ENVIRONM_MAP
--
ALTER TABLE TOOLZ.CMDB_DEVICES_PRO_ENV_MAP ADD CONSTRAINT CMDB_DEV_PRO_ENV_MAP_DEV_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;
ALTER TABLE TOOLZ.CMDB_DEVICES_PRO_ENV_MAP ADD CONSTRAINT CMDB_DEV_PRO_ENV_MAP_PRO_FK FOREIGN
KEY ( ID_PROJECT ) REFERENCES TOOLZ.CMDB_PROJECTS ( ID ) ON
DELETE CASCADE ;
ALTER TABLE TOOLZ.CMDB_DEVICES_PRO_ENV_MAP ADD CONSTRAINT CMDB_DEV_PRO_ENV_MAP_ENV_FK FOREIGN
KEY ( ID_ENVIRONMENT ) REFERENCES TOOLZ.CMDB_ENVIRONMENTS ( ID ) ON
DELETE CASCADE ;



--
-- Création de la table DATABASE_INSTANCES
--
ALTER TABLE TOOLZ.CMDB_DATABASE_INSTANCES ADD CONSTRAINT CMDB_DB_INSTANCE_DEVICES_FK FOREIGN
KEY ( ID_DEVICE ) REFERENCES TOOLZ.CMDB_DEVICES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DATABASE_INSTANCE_STATUS
--
ALTER TABLE TOOLZ.CMDB_DATABASE_INSTANCE_STATUS ADD CONSTRAINT CMDB_STATUS_DB_INSTANCE_FK FOREIGN
KEY ( ID_INSTANCE ) REFERENCES TOOLZ.CMDB_DATABASE_INSTANCES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DATABASE_INSTANCE_CREDENTIALS
--
ALTER TABLE TOOLZ.CMDB_DATABASE_INSTANCE_CREDS ADD CONSTRAINT CMDB_DB_INSTANCE_CREDS_FK FOREIGN
KEY ( ID_INSTANCE ) REFERENCES TOOLZ.CMDB_DATABASE_INSTANCES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DATABASES
--
ALTER TABLE TOOLZ.CMDB_DATABASES ADD CONSTRAINT CMDB_DB_DB_INSTANCES_FK FOREIGN
KEY ( ID_INSTANCE ) REFERENCES TOOLZ.CMDB_DATABASE_INSTANCES ( ID ) ON
DELETE CASCADE ;


--
-- Création de la table DATABASE_BACKUPS
--
ALTER TABLE TOOLZ.CMDB_DATABASE_BACKUPS ADD CONSTRAINT CMDB_DB_BCK_DATABASES_FK FOREIGN
KEY ( ID_DATABASE ) REFERENCES TOOLZ.CMDB_DATABASES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table CMDB_DATABASE_FILEGROUPS
--
ALTER TABLE TOOLZ.CMDB_DATABASE_FILEGROUPS ADD CONSTRAINT CMDB_DB_FGS_DATABASES_FK FOREIGN
KEY ( ID_DATABASE ) REFERENCES TOOLZ.CMDB_DATABASES ( ID ) ON
DELETE CASCADE ;

--
-- Création de la table DATABASE_FILEGROUP_STATUS
--
ALTER TABLE TOOLZ.CMDB_DATABASE_FILEGROUP_STATUS ADD CONSTRAINT CMDB_FG_STS_DB_FGS_FK FOREIGN
KEY ( ID_FILEGROUP ) REFERENCES TOOLZ.CMDB_DATABASE_FILEGROUPS ( ID ) ON
DELETE CASCADE ;