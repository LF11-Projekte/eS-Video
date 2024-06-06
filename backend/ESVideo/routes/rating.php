<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Videobewertung abrufen
// ---------------------------------------------------------------------------------
$app->get('/rating/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();

    // Videobewertung als Ganzes abrufen
    $res = $db->Query("
        SELECT
            SUM(R.Rating)                AS `positive-ratings`,
            COUNT(R.UID) - SUM(R.Rating) AS `negative-ratings`,
            SUM(R.Rating) / COUNT(R.UID) AS `rating`
        FROM VIDEO V
         LEFT JOIN RATING R
          ON V.UID = R.Video
        WHERE V.`Key` = :key
        GROUP BY V.UID;
       ", [
        'key' => $args['key']
    ]);

    // Keine Bewertungen liegen  vor
    if (count($res) == 0)
        return $response->withStatus(404); // not found

    // Sonst: Daten ablegen
    $info = $res[0];

    // -----------------------------------------------------------------

    // Wie hat der angemeldete Nutzer das Video bewertet?
    $res = $db->Query("
        SELECT
            R.Rating
        FROM VIDEO V
         LEFT JOIN RATING R
          ON V.UID = R.Video
        WHERE V.`Key` = :key 
          AND R.User = :uid;
       ", [
        'key' => $args['key'],
        'uid' => $_SESSION['UID']
    ]);

    // Keine Bewertung
    if (count($res) == 0) {
        $info['your-rating'] = 0;

    // Positive oder Negative Bewertung
    } else {
        $info['your-rating'] = $res[0]['Rating'] == 1 ? 1 : -1;
    }

    // Infos zurückgeben
    $response->getBody()->write(json_encode($info));
    return $response->withStatus(200); // OK!
});

// Videobewertung zurückziehen
// ---------------------------------------------------------------------------------
$app->put('/rating/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    $body = json_decode($request->getBody()->getContents(), true);
    $vid = id::getVID($args['key']);

    // -----------------------------------------------------------------
    $db = new database();
    $db->Query("
        IF (SELECT COUNT(UID) FROM RATING WHERE User = :uid AND Video = :vid) = 0 THEN
            INSERT INTO RATING (User, Video) VALUES (:uid, :vid);
        END IF;
       ", [
        'uid' => $_SESSION['UID'],
        'vid' => $vid
    ]);

    $db->Query("
            UPDATE RATING SET Rating = :rt WHERE User = :uid AND Video = :vid;
       ", [
        'uid' => $_SESSION['UID'],
        'vid' => $vid,
        'rt' => $body['rating'] == 1 ? 1 : 0
    ]);

    return $response->withStatus(200);
});


// Videobewertung zurückziehen
// ---------------------------------------------------------------------------------
$app->delete('/rating/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Video Text-ID zu ID-Nummer auflösen
    $vid = id::getVID($args['key']);

    // -----------------------------------------------------------------
    $db = new database();
    $db->Query("
        DELETE FROM RATING WHERE User = :uid AND Video = :vid
       ", [
        'uid' => $_SESSION['UID'],
        'vid' => $vid
    ]);

    return $response->withStatus(200); // OK!
});