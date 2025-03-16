DROP DATABASE IF EXISTS twitter;
CREATE DATABASE twitter;
USE twitter;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS follow;
DROP TABLE IF EXISTS tweet;
DROP TABLE IF EXISTS retweet;
DROP TABLE IF EXISTS hashtag;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS block_user;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS bookmark;
DROP TABLE IF EXISTS impression;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS community;
DROP TABLE IF EXISTS user_community;

CREATE TABLE `user` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `role` varchar(255) DEFAULT 'user',
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) UNIQUE NOT NULL,
  `display_name` varchar(255),
  `email` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `phone` varchar(255) NULL,
  `url` varchar(255) NULL,
  `biography` varchar(255) NULL,
  `city` varchar(255) NULL,
  `country` varchar(255) NULL,
  `genre` varchar(255) NULL,
  `picture` varchar(255) NULL,
  `header` varchar(255) NULL,
  `NSFW` boolean DEFAULT false,
  `is_active` boolean DEFAULT true NOT NULL,
  `is_verified` boolean DEFAULT false NOT NULL,
  `ban` varchar(255) DEFAULT NULL,
  `creation_date` date NOT NULL,
  `verified_date` date DEFAULT NULL,
  `inactive_date` date DEFAULT NULL
);

CREATE TABLE `follow` (
  `id_user_follow` integer NOT NULL,
  `id_user_followed` integer NOT NULL,
  PRIMARY KEY (id_user_follow, id_user_followed), -- clé primaire composite
  FOREIGN KEY (id_user_follow) REFERENCES user(id),
  FOREIGN KEY (id_user_followed) REFERENCES user(id)
);

CREATE TABLE `block_user` (
  `id_user` integer NOT NULL,
  `id_blocked_user` integer NOT NULL,
  PRIMARY KEY (id_user, id_blocked_user), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_blocked_user) REFERENCES user(id)
);

CREATE TABLE `tweet` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `id_user` integer NOT NULL,
  `reply_to` integer NULL,
  `quote_to` integer NULL,
  `NSFW` boolean DEFAULT false NOT NULL,
  `content` varchar(140) NOT NULL,
  `creation_date` datetime NOT NULL,
  `is_pinned` boolean NOT NULL DEFAULT false,
  `is_community` boolean NOT NULL DEFAULT false,
  `media1` varchar(255) NULL,
  `media2` varchar(255) NULL,
  `media3` varchar(255) NULL,
  `media4` varchar(255) NULL,
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (reply_to) REFERENCES tweet(id),
  FOREIGN KEY (quote_to) REFERENCES tweet(id)
);

CREATE TABLE `bookmark` (
  `id_tweet` integer NOT NULL,
  `id_user` integer NOT NULL,
  PRIMARY KEY (id_tweet, id_user), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `impression` (
  `id_user` integer NOT NULL,
  `id_tweet` integer NOT NULL,
  PRIMARY KEY (id_user, id_tweet), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `retweet` (
  `id_user` integer NOT NULL,
  `id_tweet` integer NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (id_user, id_tweet), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `hashtag` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `report` (
  `id_tweet` integer NOT NULL,
  `id_user` integer NOT NULL,
  `description` varchar(255) DEFAULT null,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (id_tweet, id_user), -- clé primaire composite
  FOREIGN KEY (id_tweet) REFERENCES tweet(id),
  FOREIGN KEY (id_user) REFERENCES user(id)
);

CREATE TABLE `likes` (
  `id_user` integer NOT NULL,
  `id_tweet` integer NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (id_user, id_tweet), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `message` (
  `id` integer PRIMARY KEY,
  `content` varchar(255) NOT NULL,
  `media` varchar(255) NULL,
  `id_sender` integer NOT NULL,
  `id_receiver` integer NOT NULL,
  `date` datetime NOT NULL,
  `is_hidden` boolean NOT NULL DEFAULT false,
  `is_viewed` boolean NOT NULL DEFAULT false,
  FOREIGN KEY (id_sender) REFERENCES user(id),
  FOREIGN KEY (id_receiver) REFERENCES user(id)
);

CREATE TABLE `community` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `biography` varchar(255),
  `id_creator` integer NOT NULL,
  `cover` varchar(255),
  `date_creation` datetime NOT NULL,
  FOREIGN KEY (id_creator) REFERENCES user(id)
);

CREATE TABLE `user_community` (
  `id_community` integer NOT NULL,
  `id_user` integer NOT NULL,
  `role` varchar(255) DEFAULT 'user',
  PRIMARY KEY (id_community, id_user), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_community) REFERENCES community(id)
);

INSERT INTO user (firstname,lastname,username,email,password,birthdate,city,country,creation_date)
VALUES
('test','test','test','test@test.fr','citron','2000-05-15','Paris','France','2025-05-15'),
('test2','test2','test2','test2@test.fr','purple','2003-06-21','Lyon','France','2024-02-27'),
('test3','test3','test3','test3@test.fr','green','2002-12-25','Lille','France','2025-01-19');

INSERT INTO follow (id_user_follow, id_user_followed)
VALUES
(1,2), (1,3),
(2,1),
(3,1),(3,2);

INSERT INTO tweet (id_user, content, creation_date)
VALUES 
(1, 'Hey 👋🏽 je suis nouveau sur Twitter', '2025-02-15 12:00:00'),
(1, 'EH toi la !! 😂 non Mbappe MECHANTTTT!!', '2025-02-16 12:00:00'),
(2, 'Ca marche commment Twitter je capte pas', '2025-02-15 12:00:00'),
(2, 'mdrrr cest un ouf Kanye West , le bipolaire il a séré', '2025-02-16 12:00:00'),
(3, 'Hello guys', '2025-02-15 12:03:00'),
(3, 'Macron Démission', '2025-02-16 12:00:00');

INSERT INTO tweet (id_user, content, creation_date,media1)
VALUES 
(2, 'Je suis chomeur', '2025-02-15 12:45:00','../Assets/Unknown.png');

UPDATE `user`
SET `picture` = '../Assets/cr7prime.webp'
WHERE `id` = 2;

UPDATE `user`
SET `picture` = '../Assets/Unknown.jpeg'
WHERE `id` = 1;

UPDATE user 
SET header = '../Assets/67c5cadcd56dd_naruto-baryon-mode-boruto-scaled.jpg' 
WHERE id = 1;

UPDATE user 
SET biography = 'LA PIRATERIE NEST JAMAIS FINI 🏴‍☠️CEST PAS PIRATES DES CARAIBES' 
WHERE id = 1;

