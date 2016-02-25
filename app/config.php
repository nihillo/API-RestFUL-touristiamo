<?php

/**
 * This file contains setting consts
 */


// Folders
define('APP_DIRECTORY', dirname(__FILE__));

// Variables very used.
define('DS', DIRECTORY_SEPARATOR);

// App names
define('APP_NAME', 'Touristiamo');
define('APP_URL', 'http://localhost');

// Connection to the DB.
define('APP_BD_HOST', 'localhost');
define('APP_BD_NAME', 'touristiamo');
define('APP_BD_USER', 'root');
define('APP_BD_PASSWORD', 'root');

// PHP Mailer
define('APP_EMAIL', 'cristobillas_22@hotmail.com');
define('APP_EMAIL_PASS', 'cf947f3ce605bffc261fee07bbbe8d139444d683957ba05fde9c6af6d75bdb21');
define('APP_EMAIL_HOST', 'smtp-mail.outlook.com');
define('APP_EMAIL_PORT', 587);
define('APP_EMAIL_ENCRYPT', 'tls');
define('APP_EMAIL_DEBUG_MODE', 1);

/**
 * APP CONSTS
 */
define('APP_TOKEN', '815c7f63628608a65582e6aead6fcb9eb178b3ef24bf4677fb185ddb187c6da2'); // Touristiamo_2016 - sha256
define('APP_MODE_DEBUG', false);