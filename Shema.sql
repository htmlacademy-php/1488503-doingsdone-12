-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.19 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;


-- Дамп структуры базы данных things_are_in_order
CREATE DATABASE IF NOT EXISTS `things_are_in_order` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `things_are_in_order`;

-- Дамп структуры для таблица things_are_in_order.projects
CREATE TABLE IF NOT EXISTS `projects`
(
    `id`      int unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int unsigned NOT NULL,
    `name`    varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Дамп данных таблицы things_are_in_order.projects: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `projects`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `projects`
    ENABLE KEYS */;

-- Дамп структуры для таблица things_are_in_order.tasks
CREATE TABLE IF NOT EXISTS `tasks`
(
    `id`         int unsigned NOT NULL AUTO_INCREMENT,
    `user_id`    int unsigned NOT NULL,
    `project_id` int unsigned NOT NULL,
    `file`       varchar(255)      DEFAULT NULL,
    `data_add`   timestamp    NOT NULL,
    `data_term`  timestamp    NULL DEFAULT NULL,
    `status`     int          NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Дамп данных таблицы things_are_in_order.tasks: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `tasks`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks`
    ENABLE KEYS */;

-- Дамп структуры для таблица things_are_in_order.users
CREATE TABLE IF NOT EXISTS `users`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `email`       varchar(255) NOT NULL,
    `password`    varchar(64)     NOT NULL,
    `name`        varchar(255)    NOT NULL,
    `date_create` timestamp    NULL DEFAULT NULL,
    `date_update` timestamp    NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Дамп данных таблицы things_are_in_order.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `users`
    ENABLE KEYS */;

/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES = IFNULL(@OLD_SQL_NOTES, 1) */;
