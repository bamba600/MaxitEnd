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
            $host = Env::get('DB_HOST', 'localhost');
            $dbname = Env::get('DB_NAME', 'maxit');
            $user = Env::get('DB_USER', 'root');
            $pass = Env::get('DB_PASS', '');
            $charset = Env::get('MYSQL_CHARSET', 'utf8mb4');
            $port = $driver === 'mysql'
                ? Env::get('MYSQL_PORT', '3306')
                : Env::get('PGSQL_PORT', '5432');

            // Construction du DSN
            if ($driver === 'mysql') {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            } elseif ($driver === 'pgsql') {
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