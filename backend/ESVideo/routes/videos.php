<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Alle Videos der Plattform abrufen
// ---------------------------------------------------------------------------------
$app->get('/videos', function (Request $request, Response $response) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            `Key`                           AS `key`,
            `Title`                         AS `title`,
            `Description`                   AS `description`,
            `Thumbnail`                     AS `thumbnail`,
            U.LoginName                     AS `owner`,
            COUNT(DISTINCT VW.UID)          AS `views`,
            SUM(R.Rating) / COUNT(R.Rating) AS `rating`,
            UploadDate                      AS `date`
        FROM VIDEO V
         INNER JOIN USER U
          ON V.Owner = U.UID
         LEFT JOIN VIEW VW
          ON V.UID = VW.Video
         LEFT JOIN RATING R
          ON V.UID = R.Video
        GROUP BY V.UID;
       ", []);

    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200); // OK!
});

// Videos von gefolgten Nutzern abrufen
// ---------------------------------------------------------------------------------
$app->get('/videos/followed', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            `Key`                           AS `key`,
            `Title`                         AS `title`,
            `Description`                   AS `description`,
            `Thumbnail`                     AS `thumbnail`,
            FU.LoginName                    AS `owner`,
            COUNT(DISTINCT VW.UID)          AS `views`,
            SUM(R.Rating) / COUNT(R.Rating) AS `rating`,
            UploadDate                      AS `date`
         FROM USER U
         INNER JOIN FOLLOWER F
          ON F.User = U.UID
         INNER JOIN USER FU
          ON FU.UID = F.Following
         INNER JOIN VIDEO V
          ON V.Owner = F.Following
         LEFT JOIN VIEW VW
          ON V.UID = VW.Video
         LEFT JOIN RATING R
          ON V.UID = R.Video
        WHERE U.UID = :uid
        GROUP BY V.UID;
       ", [
        'uid' => $_SESSION['UID']
    ]);

    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200); // OK!
});

// Videos eines bestimmten Benutzers abrufen
// ---------------------------------------------------------------------------------
$app->get('/videos/{ad}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            `Key`                           AS `key`,
            `Title`                         AS `title`,
            `Description`                   AS `description`,
            `Thumbnail`                     AS `thumbnail`,
            U.LoginName                     AS `owner`,
            COUNT(DISTINCT VW.UID)          AS `views`,
            SUM(R.Rating) / COUNT(R.Rating) AS `rating`,
            UploadDate                      AS `date`
        FROM VIDEO V
         INNER JOIN USER U
          ON V.Owner = U.UID
         LEFT JOIN VIEW VW
          ON V.UID = VW.Video
         LEFT JOIN RATING R
          ON V.UID = R.Video
        WHERE U.LoginName = :ad
        GROUP BY V.UID;
       ", [
        'ad' => $args['ad']
    ]);

    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200);
});

// Videos einer bestimmten Klasse abrufen
// ---------------------------------------------------------------------------------
$app->get('/videos/class/{cid}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            V.`Key`                         AS `key`,
            `Title`                         AS `title`,
            `Description`                   AS `description`,
            `Thumbnail`                     AS `thumbnail`,
            U.LoginName                     AS `owner`,
            COUNT(DISTINCT VW.UID)          AS `views`,
            SUM(R.Rating) / COUNT(R.Rating) AS `rating`,
            UploadDate                      AS `date`
        FROM VIDEO V
         INNER JOIN USER U
          ON V.Owner = U.UID
         INNER JOIN CLASS C 
          ON C.UID = U.Class
         LEFT JOIN VIEW VW
          ON V.UID = VW.Video
         LEFT JOIN RATING R
          ON V.UID = R.Video
        WHERE C.`Key` = :cid
        GROUP BY V.UID;
       ", [
        'cid' => $args['cid']
    ]);

    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200); // OK!
});