CREATE TABLE `users`
(
    `id`          int AUTO_INCREMENT PRIMARY KEY,
    `username`    varchar(50)  NOT NULL UNIQUE,
    `email`       varchar(100) NOT NULL UNIQUE,
    `password`    varchar(255) NOT NULL,
    `confirmed`   tinyint(1)   NOT NULL DEFAULT '0',
    `created_at`  int                   DEFAULT '0',
    `modified_at` int                   DEFAULT '0'
);