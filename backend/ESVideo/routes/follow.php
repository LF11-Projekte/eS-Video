<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// FOLLOWERS-ROUTE (für beliebigen Nutzer)
// ---------------------------------------------------------------------------------
$app->get('/followers/{ad}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Nutzernamen auflösen
    $uid = id::getUID($args['ad']);

    // -----------------------------------------------------------------
    $db = new database();
    $respData = [];

    // Followerzahl
    $res = $db->Query("
        SELECT 
            COUNT(F.Following) AS NumFollowers
        FROM USER U
         LEFT JOIN FOLLOWER F
          ON U.UID = F.Following
        WHERE U.UID = :uid
        GROUP BY U.UID
        ", [
        "uid" => $uid
    ]);

    // Keine Daten erhalten
    if (count($res) == 0) {
        return $response->withStatus(404); // Nutzer nicht gefunden
    }

    // Erhaltene Daten ablegen
    $respData['NumFollowers'] = $res[0]['NumFollowers'];

    // Folgt der angemeldete Nutzer dem gefragten Nutzer
    $res = $db->Query("
        SELECT 
            *
        FROM FOLLOWER
        WHERE User = :me AND Following = :uid;
        ", [
        "me"  => $_SESSION['UID'],
        "uid" => $uid
    ]);

    // Erhaltene Daten ablegen
    $respData['IsFollowing'] = count($res) > 0;

    // Antwortdaten schreiben
    // -> Anzahl der Follower des Nutzers: INT
    // -> Folgt der angemeldete Nutzer dem Nutzer: BOOL
    $response->getBody()->write(json_encode($respData));
    return $response->withStatus(200); // OK!
});

// FOLLOWING-ROUTE (für angemeldeten Nutzer)
// ---------------------------------------------------------------------------------
$app->get('/following', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();
    $respData = [];

    // Nutzer, welchen der angemeldete Nutzer folgt abrufen
    $res = $db->Query("
        SELECT 
            FU.LoginName AS `ad` 
        FROM USER U
         LEFT JOIN FOLLOWER F
          ON U.UID = F.User
         INNER JOIN USER FU
          ON FU.UID = F.Following
        WHERE U.UID = :uid
        GROUP BY U.UID
        ", [
        "uid" => $_SESSION['UID']
    ]);

    // Antwortdaten schreiben
    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200); // OK!
});


// FOLLOW-ROUTE (für beliebigen Nutzer)
// ---------------------------------------------------------------------------------
$app->post('/follow/{ad}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Nutzernamen auflösen
    $uid = id::getUID($args['ad']);

    // Versucht der Nutzer sich selbst zu folgen?
    if ($_SESSION['UID'] == $uid) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();

    // Folgt der angemeldete Nutzer dem gefragten Nutzer
    $res = $db->Query("
        SELECT 
            *
        FROM FOLLOWER
        WHERE User = :me AND Following = :uid;
        ", [
        "me"  => $_SESSION['UID'],
        "uid" => $uid
    ]);

    // Falls ja -> nichts zu tun
    if (count($res) > 0) return $response->withStatus(200);

    // Followbeziehung anlegen
    $db->Query("
        INSERT INTO FOLLOWER (User, Following)
        VALUES (:me, :uid);
        ", [
        "me"  => $_SESSION['UID'],
        "uid" => $uid
    ]);

    return $response->withStatus(200); // OK!
});

// UNFOLLOW-ROUTE (für beliebigen Nutzer)
// ---------------------------------------------------------------------------------
$app->post('/unfollow/{ad}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Nutzernamen auflösen
    $uid = id::getUID($args['ad']);

    // Versucht der Nutzer sich selbst zu entfolgen?
    if ($_SESSION['UID'] == $uid) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();

    // Folgt der angemeldete Nutzer dem gefragten Nutzer
    $res = $db->Query("
        SELECT 
            *
        FROM FOLLOWER
        WHERE User = :me AND Following = :uid;
        ", [
        "me"  => $_SESSION['UID'],
        "uid" => $uid
    ]);

    // Falls nein -> nichts zu tun
    if (count($res) == 0) return $response->withStatus(200);

    // Followbeziehung löschen
    $db->Query("
        DELETE FROM FOLLOWER
        WHERE User = :me AND Following = :uid;
        ", [
        "me"  => $_SESSION['UID'],
        "uid" => $uid
    ]);

    return $response->withStatus(200); // OK!
});