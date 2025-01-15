CREATE TABLE `push_subscriptions`
(
    `id`         int          NOT NULL,
    `user_id`    int          NOT NULL,
    `name`       varchar(255) NOT NULL,
    `endpoint`   varchar(500) NOT NULL,
    `p256dh`     varchar(255) NOT NULL,
    `auth`       varchar(255) NOT NULL,
    `created_at` INT          NOT NULL
);

ALTER TABLE `push_subscriptions`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `push_subscriptions`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;