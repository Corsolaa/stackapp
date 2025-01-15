CREATE TABLE `users`
(
    `id`          int          NOT NULL,
    `username`    varchar(50)  NOT NULL,
    `email`       varchar(100) NOT NULL,
    `password`    VARCHAR(255) NOT NULL,
    `confirmed`   tinyint(1)   NOT NULL DEFAULT '0',
    `created_at`  int                   DEFAULT '0',
    `modified_at` int                   DEFAULT '0'
);

ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `unique_email` (`email`),
    ADD UNIQUE KEY `unique_username` (`username`);

ALTER TABLE `users`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;