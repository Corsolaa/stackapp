<?php

namespace StackSite\Core;

class Template
{
    public static function getHeader(string $pageTitle = 'StackSite', array $customCss = []): void
    {
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta name="robots" content="noindex, nofollow">
            <meta name="author" content="John Spice">
            <meta name="description" content="description of the website.">

            <title><?php echo htmlspecialchars($pageTitle) ?></title>

            <link rel="stylesheet" href="/assets/css/main.css">
            <link rel="stylesheet" href="/assets/css/animations.css">
            <link rel="stylesheet" href="/assets/css/form.css">
            <?php
            foreach ($customCss as $css) {
                echo '<link rel="stylesheet" href="/assets/css/' . htmlspecialchars($css) . '">';
            }
            ?>

            <link rel="manifest" href="/manifest.json">
            <link rel="icon" type="image/png" href="/icons/favicon-96x96.png" sizes="96x96"/>
            <link rel="icon" type="image/svg+xml" href="/icons/favicon.svg"/>
            <link rel="shortcut icon" href="/icons/favicon.ico"/>
            <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png"/>
            <meta name="apple-mobile-web-app-title" content="StackSite"/>
            <link rel="manifest" href="/icons/site.webmanifest"/>
        </head>

        <body>
        <div class="notification_center">
        </div>
    <?php }

    public static function getFooter(array $customJs = []): void
    {
        ?>
        </body>

        <script src="/assets/js/script.js"></script>
        <script src="/assets/js/util.js"></script>
        <script src="/assets/js/form.js"></script>
        <script src="/assets/js/notification.js"></script>
        <?php
        foreach ($customJs as $js) {
            echo '<script src="/assets/js/' . htmlspecialchars($js) . '"></script>';
        }
        ?>

        </html>
    <?php }
}