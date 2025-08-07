<?php

// DB Params
define('DB_HOST', 'db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'movie_ticket');


// Define App Root
define ('APPROOT', dirname(dirname(__FILE__)));

// Define URL Root
define('URLROOT', 'http://localhost:8000');




define('GOOGLE_CLIENT_ID', '545819856659-v1gndceuh2eajlmrs3aijcm745vausn8.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', URLROOT . '/auth/googleCallback');

// Define SITENAME
define('SITENAME', 'Cash Flow');