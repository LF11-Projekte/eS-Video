<?php
// ----------------------------------------------------------------------------------
use Slim\Factory\AppFactory;

// Libraries laden
require __DIR__ . '/../vendor/autoload.php';

// Helferklassen lasen
require __DIR__ . '/../utils/database.php';
require __DIR__ . '/../utils/cors.php';
require __DIR__ . '/../utils/ska.php';
require __DIR__ . '/../utils/id.php';

// PHP-Sitzung starten
session_start();
// ----------------------------------------------------------------------------------

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// CORS-Richtlinien setzen
cors::apply($app);

// Alle Routen und deren Funktionen laden
require __DIR__ . '/../routes/user.php';
require __DIR__ . '/../routes/session.php';
require __DIR__ . '/../routes/follow.php';
require __DIR__ . '/../routes/video.php';
require __DIR__ . '/../routes/videos.php';
require __DIR__ . '/../routes/rating.php';
require __DIR__ . '/../routes/comment.php';
require __DIR__ . '/../routes/class.php';
require __DIR__ . '/../routes/search.php';

// App starten
$app->run();