<?php
// app/config/config.php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Your database username
define('DB_PASS', '');     // Your database password
define('DB_NAME', 'eduhub_db');

// Base URL for your application
define('BASE_URL', 'http://localhost/eduhub'); // Adjust this to your project URL

// Default controller and method
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_METHOD', 'index');

// Path for uploaded files
define('UPLOAD_PATH', __DIR__ . '/../../storage/uploads/');

// Optional: Set default timezone
date_default_timezone_set('Asia/Karachi'); // Adjust to your timezone
?>