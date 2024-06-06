<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// USER-INFO-ROUTE (für beliebigen Nutzer)
// ---------------------------------------------------------------------------------
$app->get('/user/{ad}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Existiert der gesuchte Nutzer auf der Platform?
    if (!adExists($args['ad'])) return $response->withStatus(404); // not found

    // -----------------------------------------------------------------
    // Nutzerdaten abrufen
    $info = ska::getUserDetails($_SESSION['TOKEN'], $args['ad']);

    // Keine Daten erhalten
    if (count($info) == 0) {
        return $response->withStatus(404); // Nutzer nicht gefunden
    }

    $localUser = getByAd($args['ad'], $info['displayName']);

    $res = [
        "displayName"    => $info['displayName'],
        "description"    => $info['description'],
        "profilePicture" => ska::SKAHostExt . $info['profilePicture'],
        "class"          => $localUser['ClassKey'],
        "className"      => $localUser['ClassName'],
    ];

    // Antwortdaten schreiben
    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200);
});

// USER-INFO-ROUTE (für angemeldeten Nutzer)
// ---------------------------------------------------------------------------------
$app->get('/user', function (Request $request, Response $response) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    // Nutzerdaten abrufen
    $ad = id::getAD($_SESSION['UID']);
    $info = ska::getUserDetails($_SESSION['TOKEN'], $ad);

    // Keine Daten erhalten
    if (count($info) == 0) {
        return $response->withStatus(404); // Nutzer nicht gefunden
    }

    $localUser = getByUid($_SESSION['UID'], $info['displayName']);

    $res = [
        "displayName"    => $info['displayName'],
        "description"    => $info['description'],
        "profilePicture" => ska::SKAHostExt . $info['profilePicture'],
        "class"          => $localUser['ClassKey'],
        "className"      => $localUser['ClassName'],
    ];

    // Antwortdaten schreiben
    $response->getBody()->write(json_encode($res));
    return $response->withStatus(200);
});

// ---------------------------------------------------------------------------------
// BEARBEITUNG BENUTZERPROFIL
// ---------------------------------------------------------------------------------

// USER-PUT-DISPLAYNAME (für angemeldeten Nutzer)
// ---------------------------------------------------------------------------------
$app->put('/user/displayname', function (Request $request, Response $response) {
    $body = json_decode($request->getBody()->getContents(), true);

    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    // Nutzerdaten ändern
    ska::setUserDisplayName($_SESSION['TOKEN'], $body['displayName']);

    // Antwortdaten schreiben
    return $response->withStatus(200);
});

// USER-PUT-DESCRIPTION (für angemeldeten Nutzer)
// ---------------------------------------------------------------------------------
$app->put('/user/description', function (Request $request, Response $response) {
    $body = json_decode($request->getBody()->getContents(), true);

    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    // Nutzerdaten ändern
    ska::setUserDescription($_SESSION['TOKEN'], $body['description']);

    // Antwortdaten schreiben
    return $response->withStatus(200);
});

// USER-POST-PROFILBILD (für angemeldeten Nutzer)
// ---------------------------------------------------------------------------------
$app->post('/user/upload', function (Request $request, Response $response) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    // Dateiupload zwischenspeichern
    $files = $request->getUploadedFiles();
    if (count($files) != 1) return $response->withStatus(400); // bad request

    $file = $files['file'];

    if ($file->getError() !== UPLOAD_ERR_OK) {
        return $response->withStatus(400); // bad request
    }

    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $extension;

    //$file->moveTo($path);
    if (ska::setUserProfilePicture($_SESSION['TOKEN'], $filename, $file->getStream()))
        return $response->withStatus(200);
    else
        return $response->withStatus(500);
});


// ---------------------------------------------------------------------------------
// HELPERS
// ---------------------------------------------------------------------------------

// Existiert der Nutzer?
function adExists($ad): bool
{
    $db = new database();
    $res = $db->Query("SELECT UID FROM USER WHERE LoginName = :ad", [
        'ad' => $ad
    ]);
    
    return count($res) > 0;
}

// Nutzer per AD-Namen beschaffen
function getByAd($ad, $dn) {
    $db = new database();
    $res = $db->Query("
        SELECT 
        U.LoginName   AS AdName,
            C.DisplayName AS ClassName,
            C.Key         AS ClassKey
        FROM USER U 
         INNER JOIN CLASS C
          ON U.Class = C.UID
          WHERE LoginName = :ad
    ", [
        'ad' => $ad
    ]);
    $db->Query("
        UPDATE USER SET DisplayName = :dn WHERE LoginName = :ad;
    ",[
        'ad' => $ad,
        'dn' => $dn
    ]);
    return $res[0];
}

// Nutzer per UID beschaffen
function getByUid($uid, $dn) {
    $db = new database();
    $res = $db->Query("
    SELECT 
            U.LoginName   AS AdName,
            C.DisplayName AS ClassName,
            C.Key         AS ClassKey
        FROM USER U 
         INNER JOIN CLASS C
          ON U.Class = C.UID
        WHERE U.UID = :uid
    ", [
        'uid' => $uid
    ]);
    $db->Query("
        UPDATE USER SET DisplayName = :dn WHERE UID = :uid;
    ",[
        'uid' => $uid,
        'dn' => $dn
    ]);
    return $res[0];
}