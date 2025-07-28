<?php
use App\Core\App;
use App\Core\Session;
use App\Core\Database;
use App\Config\Env;
use App\Config\Config;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger les variables d'environnement avec notre système
try {
    Env::load();
} catch (Exception $e) {
    // Render : utilise les variables Render directement
    error_log("Info: .env non chargé, les variables d'environnement Render seront utilisées.");
}

// Charger la configuration
$config = require_once __DIR__ . '/config.php';

// Définir des constantes à partir de la configuration
define('DB_HOST', Env::get('DB_HOST', 'localhost'));
define('DB_NAME', Env::get('DB_NAME', 'maxit'));
define('DB_USER', Env::get('DB_USER', 'root'));
define('DB_PASS', Env::get('DB_PASS', ''));
define('DB_DRIVER', Env::get('DB_DRIVER', 'mysql'));
define('AUTH_URL', Env::get('AUTH_URL', 'http://localhost:8000'));

// Nouvelles constantes pour les uploads
define('UPLOAD_DIR', Env::get('UPLOAD_DIR', 'public/uploads'));
define('UPLOAD_PATH', Env::get('UPLOAD_PATH', __DIR__ . '/../../public/uploads'));
define('UPLOAD_MAX_SIZE', Env::get('UPLOAD_MAX_SIZE', 5242880));

// Charger la configuration des middlewares
require_once __DIR__ . '/middlewares.php';

// Charger les fonctions d'aide
require_once __DIR__ . '/helpers.php';

// Enregistrer les dépendances principales avec la configuration
App::setDependency('config', Config::all());
App::setDependency('session', Session::getInstance());
App::setDependency('db', Database::getConnection());

// Charger les routes
require_once __DIR__ . '/../../routes/route.web.php';
