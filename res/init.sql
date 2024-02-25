DROP DATABASE IF EXISTS phpughh;

DROP USER IF EXISTS 'roadrunner_ro'@'%';
DROP USER IF EXISTS 'roadrunner_rw'@'%';

CREATE DATABASE phpughh;

USE phpughh;

CREATE USER 'roadrunner_ro'@'%' IDENTIFIED BY 'phpughh';
CREATE USER 'roadrunner_rw'@'%' IDENTIFIED BY 'phpughh';

GRANT SELECT ON phpughh.* TO 'roadrunner_ro'@'%';
GRANT SELECT, CREATE, UPDATE, DELETE ON phpughh.* TO 'roadrunner_rw'@'%';
GRANT SELECT, CREATE, UPDATE, DELETE ON phpughh.* TO 'roadrunner_rw'@'%';

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user__uq` (`username`)
) ENGINE=InnoDB;

INSERT INTO `user` (`name`, `username`, `password`)
    VALUES ('Admin', 'admin', '$2a$10$4HsGhuUqKM6n1iKsdC0WNO8JgRrBh07XDRN0wz8BKHMZ1v58XMF7a');
