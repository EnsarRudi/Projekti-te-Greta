<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'novadrive');
define('DB_USER', 'root');
define('DB_PASS', '12345');


// Site configuration
define('SITE_URL', 'http://localhost');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Session configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// File upload configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif']);
define('ALLOWED_PDF_TYPES', ['application/pdf']);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Europe/Tirane');

/* contact update */