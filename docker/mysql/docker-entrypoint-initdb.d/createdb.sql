CREATE DATABASE IF NOT EXISTS `larvel-docker-compose` COLLATE 'utf8_general_ci' ;
GRANT ALL ON `larvel-docker-compose`.* TO 'larvel-docker-compose-user'@'%' ;

FLUSH PRIVILEGES ;
