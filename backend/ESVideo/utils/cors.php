<?php
// -----------------------------------------------------------------------------
// Diese Klasse liefert für alle Routen die nötigen CORS-Einstellungen
// CORS ist ein browserbasierter Kontrollmechanismus um sicherzustellen, dass
// JavaScript Webanfragen nur auf Inhalte zugreifen können, wenn die jeweilige 
// Website dies erlaubt. Daher müssen wir hier unser VueJS-Frontend freigeben.
// /ds
// -----------------------------------------------------------------------------
use Slim\App;

class cors {
    const ALLOWED_ORIGINS = [
        "http://localhost:5173",
        "http://esvideo.mindplaner.com"
    ];

    public static function Apply(App &$app) {
        $app->options('/auth/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });

        $app->add(function ($req, $handler) {
            $response = $handler->handle($req);

            foreach (Cors::ALLOWED_ORIGINS as $origin) {
                $response = $response->withHeader('Access-Control-Allow-Origin', $origin);
            }

            return $response
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
                ->withHeader('Access-Control-Allow-Credentials', 'true');
        });

    }
}