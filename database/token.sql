CREATE TABLE `token`
(
    `id`         int                                                NOT NULL,
    `user_id`    int                                                NOT NULL,
    `token`      char(32)                                           NOT NULL,
    `type`       enum ('verify_user','password_reset','login_user') NOT NULL,
    `expires_at` int                                                NOT NULL,
    `created_at` int                                                NOT NULL
);

ALTER TABLE `token`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `token`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;