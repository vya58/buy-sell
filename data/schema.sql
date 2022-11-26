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
