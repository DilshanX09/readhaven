<?php
class Database
{
    public static $connection;

    public static function setConnection()
    {
        if (!isset(Database::$connection)) {
            Database::$connection = new mysqli("localhost", "root", "*****", "ebookstore", "3306");
        }
    }

    public static function insert($query)
    {
        Database::setConnection();
        Database::$connection->query($query);
    }

    public static function search($query)
    {
        Database::setConnection();
        $result = Database::$connection->query($query);
        return $result;
    }
}
