<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;
use App\Config\Env;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Charger les variables d'environnement
            Env::load();

            $driver = Env::get('DB_DRIVER', 'mysql');

            if ($driver === 'mysql') {
                $host = Env::get('DB_HOST', 'localhost');
                $port = Env::get('MYSQL_PORT', '3306');
                $dbname = Env::get('DB_NAME', 'railway');
                $user = Env::get('DB_USER', 'root');
                $pass = Env::get('DB_PASS', '');
                $charset = Env::get('MYSQL_CHARSET', 'utf8mb4');
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            } elseif ($driver === 'pgsql') {
                $host = Env::get('PGSQL_HOST', 'localhost');
                $port = Env::get('PGSQL_PORT', '5432');
                $dbname = Env::get('PGSQL_DATABASE', 'railway');
                $user = Env::get('PGSQL_USERNAME', 'postgres');
                $pass = Env::get('PGSQL_PASSWORD', '');
                $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            } else {
                throw new Exception("Driver de base de données non supporté: {$driver}");
            }

            try {
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}