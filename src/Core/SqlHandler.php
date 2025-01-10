<?php

namespace StackSite\Core;

use Exception;
use mysqli;
use StackSite\Core\Mailing\StandardMails;

class SqlHandler
{
    private static ?mysqli $mysqli;

    private static function createSql(): void
    {
        if (isset(self::$mysqli)) {
            return;
        }

        self::$mysqli = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_DATABASE']
        );

        if (self::$mysqli->connect_error) {
            header('HTTP/1.1 500 Internal Server Error');
            echo 'Connection failed: ' . self::$mysqli->connect_error;
            exit();
        }
    }

    public static function insert(string $query): int
    {
        self::createSql();

        try {
            self::$mysqli->query($query);
            return self::getInsertId();
        } catch (Exception) {
            self::exception($query);
            die();
        }
    }

    public static function update(string $query): int
    {
        self::createSql();

        try {
            self::$mysqli->query($query);
        } catch (\Exception) {
            self::exception($query);
        }

        return (int)self::$mysqli->affected_rows;
    }

    public static function fetch(string $query): array
    {
        self::createSql();

        try {
            $result = self::$mysqli->query($query);
        } catch (\Exception) {
            self::exception($query);
            return [];
        }

        if ($result === false) {
            self::exception($query);
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $result->free();

        return $data;
    }

    public static function closeConnection(): void
    {
        if (self::$mysqli !== null) {
            self::$mysqli->close();
            self::$mysqli = null;
        }
    }

    public static function cleanString(string $string): string
    {
        self::createSql();

        return self::$mysqli->real_escape_string($string);
    }

    private static function getInsertId(): int
    {
        return self::$mysqli->insert_id;
    }

    public static function exception($query): void
    {
        header('HTTP/1.1 500 Internal Server Error');
        StandardMails::apiHandleFailed(
            "Can't data into SQL server.",
            'Failed to data into SQL server using query: <br>
                             <strong>' . $query . '</strong>
                             <br><br>
                             sql error:<br>' . self::$mysqli->error);
    }
}