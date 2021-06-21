CREATE DATABASE vaphot;
USE vaphot;

CREATE TABLE contact (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  subject VARCHAR(100) NOT NULL,
  message VARCHAR(250) NOT NULL 
);

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE article (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  title VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  price INT NOT NULL,
  image VARCHAR(255) NOT NULL,
  qty INT NOT NULL,
  category_id INT NOT NULL
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  email VARCHAR(45) NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_admin BOOLEAN NOT NULL,
  firstname VARCHAR(50),
  lastname VARCHAR(50)
);

CREATE TABLE wishlist (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  article_id INT NOT NULL,
  user_id INT NOT NULL
);

CREATE TABLE command (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  created_at DATE NOT NULL,
  total INT NOT NULL,
  user_id INT NOT NULL,
  address TEXT NOT NULL
);

CREATE TABLE command_article (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  command_id INT NOT NULL,
  article_id INT NOT NULL,
  qty INT NOT NULL
);

ALTER TABLE article ADD CONSTRAINT `FK_article_category` FOREIGN KEY (`category_id`) REFERENCES `category`(`id`);
ALTER TABLE wishlist ADD CONSTRAINT `FK_wishlist_article` FOREIGN KEY (`article_id`) REFERENCES `article`(`id`);
ALTER TABLE wishlist ADD CONSTRAINT `FK_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`);
ALTER TABLE command ADD CONSTRAINT `FK_command_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`);
ALTER TABLE command_article ADD CONSTRAINT `FK_command_article_command` FOREIGN KEY (`command_id`) REFERENCES `command`(`id`);
ALTER TABLE command_article ADD CONSTRAINT `FK_command_article_article` FOREIGN KEY (`article_id`) REFERENCES `article`(`id`);