<?php

namespace App\Config;

use App\Config\Env;

/**
 * Classe Config - Gestionnaire de configuration centralisé
 */
class Config
{
    private static array $config = [];
    private static bool $loaded = false;

    /**
     * Charger la configuration
     */
    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        // Charger les variables d'environnement
        Env::load();
        
        // Charger la configuration complète
        self::$config = require __DIR__ . '/config.php';
        self::$loaded = true;
    }

    /**
     * Obtenir une valeur de configuration
     * Utilise la notation pointée (ex: 'database.connections.mysql.host')
     */
    public static function get(string $key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }

    /**
     * Définir une valeur de configuration
     */
    public static function set(string $key, $value): void
    {
        if (!self::$loaded) {
            self::load();
        }

        $keys = explode('.', $key);
        $config = &self::$config;

        foreach ($keys as $segment) {
            if (!isset($config[$segment]) || !is_array($config[$segment])) {
                $config[$segment] = [];
            }
            $config = &$config[$segment];
        }

        $config = $value;
    }

    /**
     * Obtenir toute la configuration
     */
    public static function all(): array
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config;
    }

    /**
     * Obtenir la configuration de la base de données
     */
    public static function database(string $connection = null): array
    {
        $dbConfig = self::get('database', []);
        
        if ($connection === null) {
            $connection = $dbConfig['default'] ?? 'mysql';
        }

        return $dbConfig['connections'][$connection] ?? [];
    }

    /**
     * Obtenir la configuration des uploads
     */
    public static function upload(): array
    {
        return self::get('upload', []);
    }

    /**
     * Obtenir la configuration de l'application
     */
    public static function app(): array
    {
        return self::get('app', []);
    }

    /**
     * Vérifier si on est en mode debug
     */
    public static function isDebug(): bool
    {
        return self::get('app.debug', false);
    }

    /**
     * Obtenir l'URL de base de l'application
     */
    public static function appUrl(): string
    {
        return self::get('app.url', 'http://localhost:8000');
    }

    /**
     * Obtenir le nom de l'application
     */
    public static function appName(): string
    {
        return self::get('app.name', 'MAxit');
    }

    /**
     * Obtenir le répertoire des uploads
     */
    public static function uploadDir(): string
    {
        return self::get('upload.dir', 'public/uploads');
    }

    /**
     * Obtenir le chemin complet des uploads
     */
    public static function uploadPath(): string
    {
        return self::get('upload.path', dirname(__DIR__, 2) . '/public/uploads');
    }

    /**
     * Obtenir la taille maximale des uploads
     */
    public static function uploadMaxSize(): int
    {
        return self::get('upload.max_size', 5242880);
    }

    /**
     * Obtenir les types de fichiers autorisés
     */
    public static function uploadAllowedTypes(): array
    {
        return self::get('upload.allowed_types', ['image/jpeg', 'image/png', 'image/gif']);
    }

    /**
     * Obtenir la configuration de sécurité
     */
    public static function security(): array
    {
        return self::get('security', []);
    }
}
