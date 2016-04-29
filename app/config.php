<?php

/**
 * This file contains setting consts
 */


// Variables very used.
define('DS', DIRECTORY_SEPARATOR);
define('PROHIBIT_LIMIT_SCORE', 6);

// Folders
define('APP_DIRECTORY', dirname(__FILE__));
define('APP_IMAGES_DIRECTORY', APP_DIRECTORY. DS. '..'. DS. 'public'. DS. 'images');

// App variable names
define('APP_NAME', 'Touristiamo');
define('APP_PROTOCOL', (!isset($_SERVER['HTTPS'])) ? 'http' : 'https');
define('APP_URL', APP_PROTOCOL. '://localhost');
define('APP_IMAGES_UPLOAD_NAME', 'images');

// Connection to the DB.
define('APP_BD_HOST', 'localhost');
define('APP_BD_NAME', 'touristiamo');
define('APP_BD_USER', 'root');
define('APP_BD_PASSWORD', 'root');

// PHP Mailer
define('APP_EMAIL', 'pepe@outlook.com');
define('APP_EMAIL_PASS', 'XXXXXXXXXXXXXX');
define('APP_EMAIL_HOST', 'smtp-mail.outlook.com');
define('APP_EMAIL_PORT', 587);
define('APP_EMAIL_ENCRYPT', 'tls');
define('APP_EMAIL_DEBUG_MODE', 1);

/**
 * APP CONSTS
 */
define('APP_TOKEN', '815c7f63628608a65582e6aead6fcb9eb178b3ef24bf4677fb185ddb187c6da2'); // Anything - sha256
define('APP_HASH', 'HS512');
define('APP_MODE_DEBUG', false);