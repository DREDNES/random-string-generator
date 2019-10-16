CREATE DATABASE `random_strings`;

CREATE TABLE `strings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `str` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_uidx` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;