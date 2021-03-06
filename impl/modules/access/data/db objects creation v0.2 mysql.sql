-- Generated by Oracle SQL Developer Data Modeler 3.0.0.665
--   at:        2011-07-24 10:52:09 CEST
--   site:      Oracle Database 11g
--   type:      Oracle Database 11g

DROP TABLE IF EXISTS IFCMACC_EVENT_LOG;
DROP TABLE IF EXISTS IFCMACC_USERS;

CREATE TABLE IFCMACC_EVENT_LOG 
    ( 
     ID INTEGER  NOT NULL AUTO_INCREMENT , 
     EVT_TYPE SMALLINT  NOT NULL , 
     EVT_CODE SMALLINT  NOT NULL , 
     EVT_NAME CHAR (65)  NOT NULL , 
     EVT_INFO VARCHAR (125) , 
     IP_ADDR VARCHAR (39) , 
     TMSTD DATETIME  NOT NULL , 
     TMSTD_MCSEC INTEGER  NOT NULL , 
     USERS_ID INTEGER , 
     EVENT_LOG_ID INTEGER,

    PRIMARY KEY(ID)
    ) ENGINE = INNODB
;


CREATE UNIQUE INDEX EVENT_LOG_PKX ON IFCMACC_EVENT_LOG 
    ( 
     ID ASC 
    ) 
;
CREATE INDEX EVENT_LOG_USERS_FK_1X ON IFCMACC_EVENT_LOG 
    ( 
     USERS_ID ASC 
    ) 
;
CREATE INDEX EVENT_LOG_EVENT_LOG_FK_2X ON IFCMACC_EVENT_LOG 
    ( 
     EVENT_LOG_ID ASC 
    ) 
;


CREATE TABLE IFCMACC_USERS 
    ( 
     ID INTEGER  NOT NULL AUTO_INCREMENT , 
     USR_NAME CHAR (30)  NOT NULL , 
     ACC_PWD CHAR (255)  NOT NULL , 
     ACC_PWD_ALGRTHM VARCHAR (20)  NOT NULL , 
     ACC_PWD_SALT VARCHAR (25)  NOT NULL , 
     EMAIL_ADDR VARCHAR (254)  NOT NULL , 
     STATE SMALLINT  NOT NULL ,

    PRIMARY KEY (ID)
    ) ENGINE = INNODB
;


CREATE UNIQUE INDEX USERS_PKX ON IFCMACC_USERS 
    ( 
     ID ASC 
    ) 
;
CREATE UNIQUE INDEX USERS_USR_NAME_UNX ON IFCMACC_USERS 
    ( 
     USR_NAME ASC 
    ) 
;

CREATE UNIQUE INDEX USERS_EMAIL_ADDRESS_UNX ON IFCMACC_USERS 
    ( 
     EMAIL_ADDR ASC 
    ) 
;


ALTER TABLE IFCMACC_USERS 
    ADD CONSTRAINT USERS_USR_NAME_UN UNIQUE ( USR_NAME ) ;



ALTER TABLE IFCMACC_EVENT_LOG 
    ADD CONSTRAINT EVENT_LOG_EVENT_LOG_FK_2 FOREIGN KEY 
    ( 
     EVENT_LOG_ID
    ) 
    REFERENCES IFCMACC_EVENT_LOG 
    ( 
     ID
    ) 
    ON DELETE SET NULL 
;


ALTER TABLE IFCMACC_EVENT_LOG 
    ADD CONSTRAINT EVENT_LOG_USERS_FK_1 FOREIGN KEY 
    ( 
     USERS_ID
    ) 
    REFERENCES IFCMACC_USERS 
    ( 
     ID
    ) 
    ON DELETE SET NULL 
;





-- Oracle SQL Developer Data Modeler Summary Report: 
-- 
-- CREATE TABLE                             2
-- CREATE INDEX                             5
-- ALTER TABLE                              5
-- CREATE VIEW                              0
-- CREATE PACKAGE                           0
-- CREATE PACKAGE BODY                      0
-- CREATE PROCEDURE                         0
-- CREATE FUNCTION                          0
-- CREATE TRIGGER                           0
-- CREATE STRUCTURED TYPE                   0
-- CREATE COLLECTION TYPE                   0
-- CREATE CLUSTER                           0
-- CREATE CONTEXT                           0
-- CREATE DATABASE                          0
-- CREATE DIMENSION                         0
-- CREATE DIRECTORY                         0
-- CREATE DISK GROUP                        0
-- CREATE ROLE                              0
-- CREATE ROLLBACK SEGMENT                  0
-- CREATE SEQUENCE                          0
-- CREATE MATERIALIZED VIEW                 0
-- CREATE SYNONYM                           0
-- CREATE TABLESPACE                        0
-- CREATE USER                              0
-- 
-- DROP TABLESPACE                          0
-- DROP DATABASE                            0
-- 
-- ERRORS                                   0
-- WARNINGS                                 0
