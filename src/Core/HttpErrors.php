<?php

namespace StackSite\Core;

class HttpErrors
{
    public static function unauthorized(): void
    {
        header('HTTP/1.1 401 Unauthorized');
        echo "401, fraud";
        die();
    }

    public static function badRequest(): void
    {
        header('HTTP/1.1 400 Bad Request');
        echo "400, bad request (check parameters)";
        die();
    }

    public static function serverSide(): void
    {
        header('HTTP/1.1 500 Internal Server Error');
        echo "500, internal server error";
        die();
    }
}