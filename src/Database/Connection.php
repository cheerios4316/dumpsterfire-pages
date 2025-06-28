<?php

namespace DumpsterfirePages\Database;

use DumpsterfirePages\Exceptions\DatabaseException;
use PDO;
use Throwable;

class Connection
{
    /**
     * @column stronzo
     */
    protected static string $dsnTemplate = "mysql:host={host};dbname={dbname};port={port}";
    protected ?PDO $connection = null;

    public function connect(string $host, string $dbname, string $username, int $port, string $password): PDO
    {
        if (!is_null($this->connection)) {
            throw new DatabaseException("Connection error: this instance of Connection is already connected to a database. Use ->disconnect().");
        }

        try {
            $dsnString = $this->buildDsnString($host, $dbname, strval($port));

            $connection = new PDO($dsnString, $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection = $connection;
            return $this->connection;
        } catch (Throwable $e) {
            throw new DatabaseException("Connection error: " . $e->getMessage());
        }
    }

    public function query(string $query, array $params = []): array
    {
        $this->checkConnection('query');

        try {
            $statement = $this->connection->prepare($query);

            foreach ($params as $key => $value) {
                $bindKey = ':' . ltrim($key, ':');
                $statement->bindValue($bindKey, $value);
            }

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new DatabaseException("Connection error: " . $e->getMessage());
        }
    }

    public function disconnect(): void
    {
        $this->connection = null;
    }

    protected function checkConnection(string $action): void
    {
        if (is_null($this->connection)) {
            throw new DatabaseException("Connection error during action $action: no connection to the database. Use ->connect().");
        }
    }

    protected function buildDsnString(string $host, string $dbname, string $port): string
    {
        return strtr(self::$dsnTemplate, [
            '{host}' => $host,
            '{dbname}' => $dbname,
            '{port}' => $port,
        ]);
    }
}