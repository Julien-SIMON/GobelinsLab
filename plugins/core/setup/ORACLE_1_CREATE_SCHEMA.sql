CREATE TABLESPACE GLDEV DATAFILE 'E:\data\oracle\P1Y\TBS_GLDEV.DBF' SIZE 2G AUTOEXTEND ON NEXT 1G; 

CREATE USER GLDEV IDENTIFIED BY "GLDEV" DEFAULT TABLESPACE GLDEV ACCOUNT UNLOCK;

GRANT CONNECT TO GLDEV;
GRANT RESOURCE TO GLDEV;
GRANT UNLIMITED TABLESPACE TO GLDEV;