<?php
require_once __DIR__ . '/../../vendor/autoload.php';
// use Dotenv\Dotenv; // ✅ This line is important

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

// DB Params
define('DB_HOST', 'db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'movie_ticket');

// App settings
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost:8000');
define('SITENAME', 'Cash Flow');

define('GOOGLE_CLIENT_ID', $_ENV['GOOGLE_CLIENT_ID'] ?? '');
define('GOOGLE_CLIENT_SECRET', $_ENV['GOOGLE_CLIENT_SECRET'] ?? '');
define('GOOGLE_REDIRECT_URI', $_ENV['GOOGLE_REDIRECT_URI'] ?? '');


// var_dump(getenv('GOOGLE_CLIENT_ID'));
// var_dump(GOOGLE_CLIENT_ID);
// exit;