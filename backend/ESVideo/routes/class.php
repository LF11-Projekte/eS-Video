<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Info über eine Klasse abrufen
// ---------------------------------------------------------------------------------
$app->get('/class/{cid}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            DisplayName
        FROM CLASS
        WHERE `Key` = :cid
       ", [
           'cid' => $args['cid']
    ]);

    // Keine Klasse gefunden
    if (count($res) == 0)
        return $response->withStatus(404); // not found

    // Informationen zurückliefern
    $response->getBody()->write(json_encode([
        'name' => $res[0]['DisplayName']
    ]));
    return $response->withStatus(200); // OK!
});

// Mitglieder einer Klasse abrufen
// ---------------------------------------------------------------------------------
$app->get('/class/{cid}/members', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            U.LoginName AS `ad`
        FROM USER U
        INNER JOIN CLASS C 
         ON U.Class = C.UID
        WHERE C.`Key` = :cid
       ", [
        'cid' => $args['cid']
    ]);

    // Nutzerliste zurückgeben
    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200);
});