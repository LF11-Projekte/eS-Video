<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Suche
// ---------------------------------------------------------------------------------
$app->get('/search/{search}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden
    
    // -----------------------------------------------------------------
    $db = new database();
    
    // Alle Benutzer auswählen, wo:
    // -> der Suchbegriff ähnlich dem Anzeigenamen ist
    // -> der Nutzer Uploader eines Videos ist, wessen Titel ähnlich dem Suchbegriff ist
    $users = $db->Query("
        SELECT U.LoginName AS ad
        FROM USER U
        WHERE U.DisplayName LIKE :search
        UNION
    SELECT U.LoginName AS ad
        FROM VIDEO V
        INNER JOIN USER U
          ON V.Owner = U.UID
          WHERE V.Title LIKE :search
    ;
    ", [
        'search' => "%". $args['search'] ."%"
    ]);
    // -----------------------------------------------------------------

    // Alle Klassen auswählen, wo:
    // -> der Suchbegriff ähnlich dem Anzeigenamen ist
    // -> in der Klasse ein Nutzer ist, wessen Anzeigename ähnlich dem Suchbegriff ist
    // -> in der Klasse ein Nutzer ist, welcher Uploader eines Videos ist, wessen Titel ähnlich dem Suchbegriff ist
    $classes = $db->Query("
        SELECT
            C.`Key`       AS cid,
            C.DisplayName AS name 
        FROM CLASS C
        WHERE C.DisplayName LIKE :search
    UNION
        SELECT 
            C.`Key`       AS cid,
            C.DisplayName AS name 
        FROM CLASS C
         INNER JOIN USER U
          ON U.Class = C.UID
        WHERE U.DisplayName LIKE :search
    UNION
        SELECT
            C.`Key`       AS cid,
            C.DisplayName AS name 
        FROM CLASS C
         INNER JOIN USER U
          ON U.Class = C.UID
         INNER JOIN VIDEO V
          ON V.Owner = U.UID
        WHERE V.Title LIKE :search
    ;
    ", [
        'search' => "%". $args['search'] ."%"
    ]);
    // -----------------------------------------------------------------

    // Alle Videos auswählen, wo:
    // -> der Suchbegriff ähnlich dem Titel ist
    $videos = $db->Query("
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
        WHERE V.Title LIKE :search
        GROUP BY V.UID;
    ;
    ", [
        'search' => "%". $args['search'] ."%"
    ]);

    // Daten zurückgeben
    $response->getBody()->write(json_encode([
        'users' => $users,
        'classes' => $classes,
        'videos' => $videos
    ]));
    return $response->withStatus(200); // OK!
});