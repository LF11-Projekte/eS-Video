<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Kommentare eines Videos abrufen
// ---------------------------------------------------------------------------------
$app->get('/comment/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            C.UID         AS `cid`,
            U.LoginName   AS `poster`,
            C.Comment     AS `comment`,
            C.CommentDate AS `time-stamp`
        FROM VIDEO V
         INNER JOIN COMMENT C
          ON V.UID = C.Video
         INNER JOIN USER U 
          ON C.User = U.UID
        WHERE V.`Key` = :key
        ORDER BY C.CommentDate DESC;
       ", [
        'key' => $args['key']
    ]);

    // Keine Kommentare gefunden
    if (count($res) == 0)
        return $response->withStatus(404); // not found

    // Kommentarliste zurückliefern
    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200); // OK!
});

// Video Kommentieren
// ----------------------------------------------------------------------------------
$app->post('/comment/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Requestbody auslesen
    $body = json_decode($request->getBody()->getContents(), true);

    // Video Text-ID zu ID-Nummer auflösen
    $vid = id::getVID($args['key']);

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        INSERT INTO COMMENT (User, Video, Comment)
        VALUES (:uid, :vid, :com)
       ", [
        'uid' => $_SESSION['UID'],
        'vid' => $vid,
        'com' => $body['comment']
    ]);

    return $response->withStatus(200); // OK!
});

// Kommentar löschen
// ------------------------------------------------------------------------------------------
$app->delete('/comment/{key}/{cid}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Video Text-ID zu ID-Nummer auflösen
    $vid = id::getVID($args['key']);

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        DELETE FROM COMMENT 
        WHERE Video = :vid
          AND User  = :uid
          AND UID   = :cid 
       ", [
        'uid' => $_SESSION['UID'],
        'vid' => $vid,
        'cid' => $args['cid']
    ]);

    return $response->withStatus(200); // OK!
});