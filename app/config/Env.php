<?php

namespace App\Config;

use Exception;

/**
 * Gestionnaire des variables d'environnement
 * Charge et parse le fichier .env
 */
class Env
{
    private static array $variables = [];
    private static bool $loaded = false;

    /**
     * Charger le fichier .env
     */
    public static function load(string $path = null): void
    {
        if (self::$loaded) {
            return;
        }

        $envFile = $path ?? dirname(__DIR__, 2) . '/.env';

        if (!file_exists($envFile)) {
            throw new Exception("Fichier .env non trouvé : {$envFile}");
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignorer les commentaires
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parser les variables
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Supprimer les guillemets si présents
                if (preg_match('/^"(.*)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                    $value = $matches[1];
                }

                self::$variables[$name] = $value;

                // Définir aussi dans $_ENV et putenv
                $_ENV[$name] = $value;
                putenv("{$name}={$value}");
            }
        }

        self::$loaded = true;
    }

    /**
     * Obtenir une variable d'environnement
     */
    public static function get(string $key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$variables[$key] ?? $_ENV[$key] ?? getenv($key) ?? $default;
    }

    /**
     * Vérifier si une variable existe
     */
    public static function has(string $key): bool
    {
        if (!self::$loaded) {
            self::load();
        }

        return isset(self::$variables[$key]) || isset($_ENV[$key]) || getenv($key) !== false;
    }

    /**
     * Obtenir toutes les variables
     */
    public static function all(): array
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$variables;
    }

    /**
     * Définir une variable (pour les tests)
     */
    public static function set(string $key, $value): void
    {
        self::$variables[$key] = $value;
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }
}
