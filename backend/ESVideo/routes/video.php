<?php
global $app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Video-Datei hochladen (Stream im hintergrund)
// ---------------------------------------------------------------------------------
$app->post('/video/upload', function (Request $request, Response $response) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    // Dateiupload zwischenspeichern

    $files = $request->getUploadedFiles();

    // Wurde ein Video mitgeliefert
    if (count($files) != 1) {
        return $response->withStatus(400); // bad request
    }

    // Dateiobjekt namens 'video' abrufen
    $file = $files['video'];

    // Dateiupload fehlgeschlagen
    if ($file->getError() !== UPLOAD_ERR_OK) {
        return $response->withStatus(400); // bad request
    }

    // Dateinamen und Pfade generieren
    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $extension;
    $path = __DIR__ . '/../upload-tmp/' . $filename;

    // Datei im Temp zwischenlagern
    $file->moveTo($path);

    // Upload-Informationsarray in der Sitzung anlegen
    if (!array_key_exists('upl', $_SESSION)) {
        $_SESSION['upl'] = [];
    }

    // Infos über diesen hinter einer zufälligen ID ablegen
    $id = uniqid("upl_");
    $_SESSION['upl'][$id] = [
        'path' => $path,
        'file' => $filename,
    ];

    // ID-Schlüssel zu den Upload-Informationen an den Nutzer zurückgeben 
    $response->getBody()->write(json_encode([
        'fileid' => $id
    ]));
    return $response->withStatus(200); // OK!
});

// Videoupload einsenden
// ---------------------------------------------------------------------------------
$app->post('/video/submit', function (Request $request, Response $response) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    // Dateiupload behandeln

    $files = $request->getUploadedFiles();

    // keine Dateien mitgegeben
    if (count($files) != 1)
        return $response->withStatus(400); // bad request

    // Dateiobjekt namens 'thumbnail' beschaffen
    $file = $files['thumbnail'];

    // Dateiupload fehlgeschlagen
    if ($file->getError() !== UPLOAD_ERR_OK) {
        return $response->withStatus(400); // bad request
    }

    // Dateinamen und Pfade generieren
    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $extension;
    $path = __DIR__ . '/../public/upload/thumbnails/' . $filename;

    // Datei in den öffentlichen Uploadordner verschieben
    $file->moveTo($path);

    // -----------------------------------------------------------------
    // Videodatei aus Upload-Temp verschieben
    $form = $request->getParsedBody();
    $videoId = $form['video'];

    // Informationen zu diesem Upload aus der Sitzung lesen
    $videoPath = $_SESSION['upl'][$videoId]['path'];
    $videoFile = $_SESSION['upl'][$videoId]['file'];

    // Verwiesene Videodatei existiert nicht
    if (!file_exists($videoPath)) {
        return $response->withStatus(400); // bad request
    }

    // Videodatei in den '/public'-Ordner verschieben
    copy($videoPath, __DIR__ . '/../public/upload/videos/' . $videoFile);
    unlink($videoPath);

    // Videodaten
    $title       = $form['title'];
    $description = $form['description'];
    $thumbnail   = "/upload/thumbnails/$filename";
    $video       = "/upload/videos/$videoFile";
    $key         = substr(hash('sha256', uniqid()), 0, 32);

    // Video in der Datenbank vermerken
    $db = new database();
    $db->Query("
        INSERT INTO VIDEO (Owner, `Key`, Title, Description, File, Thumbnail)
        VALUES (
                :uid, :key, :title, :desc, :file, :thumb
        )
    ", [
        "uid"   => $_SESSION['UID'],
        "key"   => $key,
        "title" => $title,
        "desc"  => $description,
        "file"  => $video,
        "thumb" => $thumbnail
    ]);

    // Video-Text-ID zurückgeben
    $response->getBody()->write(json_encode([
        'vid' => $key
    ]));

    return $response->withStatus(200); // OK!
});

// Video abrufen
// ---------------------------------------------------------------------------------
$app->get('/video/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // Video Text-ID zu ID-Nummer auflösen
    $vid = id::getVID($args['key']);

    // -----------------------------------------------------------------
    $db = new database();
    $res = $db->Query("
        SELECT
            `Title`                         AS `title`,
            `Description`                   AS `description`,
            `File`                          AS `file`,
            U.LoginName                     AS `owner`,
            COUNT(DISTINCT VW.UID)          AS `views`
        FROM VIDEO V
         INNER JOIN USER U
          ON V.Owner = U.UID
         LEFT JOIN VIEW VW
          ON V.UID = VW.Video
         LEFT JOIN RATING R
          ON V.UID = R.Video
        WHERE V.`Key` = :key
        GROUP BY V.UID;
       ", [
           'key' => $args['key']
    ]);

    // Video existiert nicht
    if (count($res) == 0)
        return $response->withStatus(404); // not found

    // Aufruf registrieren
    $db->Query("
        INSERT INTO VIEW (User, Video)
        VALUES (:uid, :vid);
    ", [
        'uid' => $_SESSION['UID'],
        'vid' => $vid
    ]);

    // Daten zurückgeben
    $response->getBody()->write(json_encode($res[0]));
    return $response->withStatus(200); // OK!
});

// Video löschen
// ---------------------------------------------------------------------------------
$app->delete('/video/{key}', function (Request $request, Response $response, $args) {
    // Ist der aufrufende Nutzer angemeldet?
    if (!isset($_SESSION['UID'])) return $response->withStatus(403); // forbidden

    // -----------------------------------------------------------------
    $db = new database();

    // Videodatei und Thumbnaildatei abrufen
    $res = $db->Query("
        SELECT
            Thumbnail AS `Thumb`,
            File      AS `Video`
        FROM VIDEO
        WHERE `Key` = :key
          AND Owner = :uid;
       ", [
        'key' => $args['key'],
        'uid' => $_SESSION['UID']
    ]);

    // Nicht gefunden
    if (count($res) == 0)
        return $response->withStatus(404); // not found

    // Video und Vorschaubild im Dateisystem löschen
    unlink(__DIR__ . '/../public' . $res['Video']);
    unlink(__DIR__ . '/../public' . $res['Thumb']);

    // Datenbankeintrag löschen
    $db->Query("
        DELETE FROM VIDEO 
        WHERE `Key` = :key
          AND Owner = :uid;
    ", [
        'key' => $args['key'],
        'uid' => $_SESSION['UID'],
    ]);

    return $response->withStatus(200); // OK!
});