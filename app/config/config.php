<?php

/**
 * Configuration de l'application utilisant les variables d'environnement
 * Ce fichier retourne un tableau de configuration
 */

// S'assurer que la classe Env est chargée
if (!class_exists('Env')) {
    require_once __DIR__ . '/Env.php';
}

// Charger les variables d'environnement
Env::load();

// Retourner la configuration complète
return [
    // Base de données
    'database' => [
        'default' => Env::get('DB_DRIVER', 'mysql'),
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => Env::get('DB_HOST', 'localhost'),
                'port' => Env::get('MYSQL_PORT', '3306'),
                'database' => Env::get('DB_NAME', 'maxit'),
                'username' => Env::get('DB_USER', 'root'),
                'password' => Env::get('DB_PASS', ''),
                'charset' => Env::get('MYSQL_CHARSET', 'utf8mb4'),
                'collation' => Env::get('MYSQL_COLLATION', 'utf8mb4_unicode_ci'),
                'dsn' => Env::get('DSN_MYSQL'),
            ],
            'pgsql' => [
                'driver' => 'pgsql',
                'host' => Env::get('PGSQL_HOST', 'localhost'),
                'port' => Env::get('PGSQL_PORT', '5432'),
                'database' => Env::get('PGSQL_DATABASE', 'maxit'),
                'username' => Env::get('PGSQL_USERNAME', 'postgres'),
                'password' => Env::get('PGSQL_PASSWORD', ''),
                'charset' => Env::get('PGSQL_CHARSET', 'utf8'),
                'dsn' => Env::get('DSN_PGSQL'),
            ]
        ]
    ],

    // Application
    'app' => [
        'name' => Env::get('APP_NAME', 'MAxit'),
        'env' => Env::get('APP_ENV', 'development'),
        'debug' => Env::get('APP_DEBUG', 'true') === 'true',
        'url' => Env::get('APP_URL', 'http://localhost:8000'),
        'auth_url' => Env::get('AUTH_URL', 'http://localhost:8000'),
    ],

    // Upload
    'upload' => [
        'dir' => Env::get('UPLOAD_DIR', 'public/uploads'),
        'path' => Env::get('UPLOAD_PATH', dirname(__DIR__, 2) . '/public/uploads'),
        'max_size' => (int) Env::get('UPLOAD_MAX_SIZE', 5242880), // 5MB par défaut
        'allowed_types' => explode(',', Env::get('UPLOAD_ALLOWED_TYPES', 'image/jpeg,image/png,image/gif')),
    ],

    // Sécurité
    'security' => [
        'session_lifetime' => (int) Env::get('SESSION_LIFETIME', 3600),
        'csrf_token_name' => Env::get('CSRF_TOKEN_NAME', 'csrf_token'),
        'encryption_key' => Env::get('ENCRYPTION_KEY', ''),
    ]
];
