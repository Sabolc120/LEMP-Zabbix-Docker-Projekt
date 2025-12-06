-- create_db.sql (Újra módosítva)

-- A LEMP rész marad
CREATE DATABASE IF NOT EXISTS lemp_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lemp_app;
CREATE TABLE hosts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL,
    last_check TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO hosts (name, status) VALUES ('Webszerver A', 'online');
INSERT INTO hosts (name, status) VALUES ('Webszerver B', 'online');

CREATE DATABASE IF NOT EXISTS zabbix CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

CREATE USER IF NOT EXISTS 'zabbix'@'%' IDENTIFIED BY 'zabbix' REQUIRE SSL;

GRANT ALL PRIVILEGES ON zabbix.* TO 'zabbix'@'%';
GRANT ALL PRIVILEGES ON zabbix.* TO 'lemp_user'@'%';

FLUSH PRIVILEGES;

