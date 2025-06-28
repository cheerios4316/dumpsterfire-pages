<?php

namespace DumpsterfirePages\Database;

use DumpsterfirePages\Exceptions\DatabaseException;

abstract class DatabaseConnection
{
    protected static ?Connection $connection = null;

    public function __construct()
    {
        if(is_null(self::$connection)) {
            throw new DatabaseException("Trying to use database connection without being connected to a database. Please use App::connectDatabase()");
        }
    }

    public static function setConnection(Connection $connection): void
    {
        self::$connection = $connection;
    }
}