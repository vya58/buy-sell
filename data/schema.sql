/*
CREATE DATABASE `buysell_bd`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE `buysell_bd`;

CREATE TABLE `user` (
    `user_id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` CHAR(255) NOT NULL,
    `avatar` VARCHAR(255) UNIQUE,
    `date_add` DATETIME NOT NULL DEFAULT NOW()
);

CREATE TABLE `category` (
    `category_id` INT PRIMARY KEY AUTO_INCREMENT,
    `category_name` VARCHAR(30) NOT NULL,
    `category_icon` VARCHAR(255) UNIQUE
);

CREATE TABLE `comment` (
    `comment_id` INT PRIMARY KEY AUTO_INCREMENT,
    `comment_text` TEXT NOT NULL
);

CREATE TABLE `offer` (
    `offer_id` INT PRIMARY KEY AUTO_INCREMENT,
    `owner_id` INT NOT NULL,
    `offer_title` VARCHAR(50) NOT NULL,
    `offer_image` VARCHAR(255) UNIQUE,
    `offer_price` INT UNSIGNED NOT NULL,
    `offer_type` VARCHAR(10) NOT NULL,
    `offer_text` TEXT NOT NULL,
    `offer_date_create` DATETIME NOT NULL DEFAULT NOW()
);

CREATE TABLE `offer_category` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `offer_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    FOREIGN KEY (`offer_id`) REFERENCES `offer` (`offer_id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE,
    UNIQUE KEY `relation_row_unique` (`category_id`, `offer_id`)
);

CREATE TABLE `offer_comment` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `offer_id` INT NOT NULL,
    `comment_id` INT NOT NULL,
    FOREIGN KEY (`offer_id`) REFERENCES `offer` (`offer_id`) ON DELETE CASCADE,
    FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE,
    UNIQUE KEY `relation_row_unique` (`offer_id`, `comment_id`)
);
*/

-- Дамп структуры базы данных buysell_bd
CREATE DATABASE IF NOT EXISTS `buysell_bd` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `buysell_bd`;



-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица buysell_bd.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `avatar` (`avatar`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb3;


-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.category
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `category_icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_icon` (`category_icon`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.comment
CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `comment_text` text NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `fk_comment_owner_id` (`owner_id`),
  CONSTRAINT `fk_comment_owner_id` FOREIGN KEY (`owner_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица buysell_bd.migration
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.offer
CREATE TABLE IF NOT EXISTS `offer` (
  `offer_id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `offer_title` varchar(50) NOT NULL,
  `offer_image` varchar(255) DEFAULT NULL,
  `offer_price` int unsigned NOT NULL,
  `offer_type` varchar(10) NOT NULL,
  `offer_text` text NOT NULL,
  `offer_date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`offer_id`),
  UNIQUE KEY `offer_image` (`offer_image`),
  KEY `fk_offers_owner_id` (`owner_id`),
  CONSTRAINT `fk_offers_owner_id` FOREIGN KEY (`owner_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.offer_category
CREATE TABLE IF NOT EXISTS `offer_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `offer_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation_row_unique` (`category_id`,`offer_id`),
  KEY `offer_id` (`offer_id`),
  CONSTRAINT `offer_category_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`offer_id`) ON DELETE CASCADE,
  CONSTRAINT `offer_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.offer_comment
CREATE TABLE IF NOT EXISTS `offer_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `offer_id` int NOT NULL,
  `comment_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation_row_unique` (`offer_id`,`comment_id`),
  KEY `comment_id` (`comment_id`),
  CONSTRAINT `offer_comment_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`offer_id`) ON DELETE CASCADE,
  CONSTRAINT `offer_comment_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.auth
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `source` varchar(255) NOT NULL,
  `source_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-auth-user_id-user-id` (`user_id`),
  CONSTRAINT `fk-auth-user_id-user-id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Дамп структуры для таблица buysell_bd.auth_rule
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Дамп структуры для таблица buysell_bd.auth_item
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Дамп структуры для таблица buysell_bd.auth_assignment
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица buysell_bd.auth_item_child
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Экспортируемые данные не выделены.

