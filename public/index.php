<?php
// public/index.php

// Autoload composer dependencies if any (optional, but good practice)
require_once __DIR__ . '/../vendor/autoload.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configurations
require_once __DIR__ . '/../app/config/config.php';

// Load core classes
require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Router.php';

// Instantiate App (this will handle routing)
$app = new App();
?>