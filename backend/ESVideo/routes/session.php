<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// LOGIN-ATTEMPT-ROUTE (Anmeldungsversuch starten)
// ---------------------------------------------------------------------------------
$app->get('/session/attempt', function (Request $request, Response $response) {
    $_SESSION['ATP'] = uniqid(); // UUID für diesen Nutzer mit dieser Session erstellen und in der Session ablegen
    $response->getBody()->write(json_encode([
        "atp" => $_SESSION['ATP']
    ]));
    return $response->withStatus(200);
});

// LOGIN-CONFIRM-ROUTE (Anmeldungsversuch mit SKA-Token bestätigen)
// ---------------------------------------------------------------------------------
$app->post('/session/confirm', function (Request $request, Response $response) {

    // Request-body auslesen
    $body = json_decode($request->getBody()->getContents(), true);

    // Attempt-Token und SKA-Token auslesen
    $atp = $body['attempt'];
    $tok = $body['token'];

    // Gehört der Login-Attempt überhaupt zu dieser Sitzung?
    if ($atp != $_SESSION['ATP']) return $response->withStatus(401);

    // ATP sollte jetzt geleert werden, da es sich um einen Einmal-Token handelt
    $_SESSION['ATP'] = "";

    // Vom SKA-Server die Daten zu diesen Nutzer abfragen (wenn nicht gefunden -> Token fehlerhaft)
    $user = ska::getMyDetails($tok);
    $response->getBody()->write(print_r(["tok" => $tok], true));
    if (!$user['adName']) return $response->withStatus(401);

    // Nutzer Anlegen falls dieser noch nicht existiert
    $db = new database();
    $db->Query("
        IF (SELECT COUNT(UID) FROM USER WHERE LoginName = :ad) < 1 THEN
            INSERT INTO USER (LoginName, Class) VALUES (:ad, 1  );
        END IF;
    ", [
        "ad" => $user['adName']
    ]);

    // Nutzerobjekt abrufen
    $res = $db->Query("SELECT UID, LoginName FROM USER WHERE LoginName = :ad;", [
        "ad" => $user['adName']
    ]);

    // hmmm...
    if (count($res) != 1) {
        return $response->withStatus(403);
    }

    // Letztes Logindatum aktualisieren
    $db->Query("UPDATE USER SET LastLogin = NOW() WHERE UID = :uid", [
        "uid" =>  $res[0]['UID'],
    ]);

    // Sitzungsvariable gesetzt
    $_SESSION['UID'] = $res[0]['UID'];
    $_SESSION['UNAME'] = $res[0]['LoginName'];
    $_SESSION['TOKEN'] = $tok;
    return $response;
});

// LOGOUT-ROUTE (Sitzung beenden)
// ---------------------------------------------------------------------------------
$app->post('/session/logout', function (Request $request, Response $response) {

    // Sitzungsvariablen löschen und Sitzung auflösen
    $_SESSION = [];
    session_destroy();

    return $response;
});

// WHOAMI-ROUTE (zum client-seitigem Wiederherstellen der Sitzung)
// ---------------------------------------------------------------------------------
$app->get('/session/whoami', function (Request $request, Response $response) {

    $usr = [
        "loggedIn" => isset($_SESSION['UID']), // wenn UID gesetzt -> Nutzer ist angemeldet
    ];

    // Wenn angemeldet -> UID Mitgeben
    if (isset($_SESSION['UID'])) {
        $usr['uid'] = $_SESSION['UNAME'];
    }

    // Informationen zurückgeben
    $response->getBody()->write(json_encode($usr));
    return $response;
});