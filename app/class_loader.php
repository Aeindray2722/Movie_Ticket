<?php

// require_once "config/config.php";
// require_once "helpers/url_helper.php";
// require_once "helpers/message_helper.php";
// require_once "helpers/UserValidator.php";
// require_once "../vendor/google/apiclient-services/autoload.php";
// // ✅ Load Composer Autoloader only
// // require_once __DIR__ . '/../../vendor/autoload.php';

// spl_autoload_register(function ($class) {
//     require_once 'libraries/' . $class . '.php';
// });





require_once "config/config.php";
require_once "helpers/url_helper.php";
require_once "helpers/message_helper.php";
require_once "helpers/UserValidator.php";

// Load the main Composer autoloader (adjust the path if needed)
require_once __DIR__ . '/../vendor/autoload.php';

// Your custom class loader (if you want to keep it for your own classes)
spl_autoload_register(function ($class) {
    // Convert namespace separators to directory separators for your classes
    $path = __DIR__ . '/libraries/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});
