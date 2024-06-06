<?php
use GuzzleHttp\Client;
// ----------------------------------------------------------------------------
// Kleine Helferklasse zur einfachen Interaktion mit den SKA-User-Manger
// ----------------------------------------------------------------------------

class ska {
    // Host des SKA-Systems
    const SKAHost = "http://localhost:3000";
    const SKAHostExt = "http://ska.mindplaner.com";

    // ------------------------------------------------------------------------
    // Abrufen von Nutzerdaten
    // ------------------------------------------------------------------------

    // Benutzerinformationen des momentan angemeldeten Nutzer abrufen
    // --------------------------------------------------------------
    public static function getMyDetails($token) {
        $client = new Client();
        try {
            $res = $client->request('GET', self::SKAHost . "/user/me", [
                'headers' => [
                    'authorization' => "Bearer $token"
                ]
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return [];
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    // Benutzerinformationen eines bestimmten Nutzers abrufen
    // ------------------------------------------------------
    public static function getUserDetails($token, $user) {
        $client = new Client();
        try {
            $res = $client->request('GET', self::SKAHost . "/user/$user", [
                'headers' => [
                    'authorization' => "Bearer $token"
                ]
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return [];
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    // ------------------------------------------------------------------------
    // Setzen von Nutzerdaten
    // ------------------------------------------------------------------------

    // Neuen Anzeigenamen setzen
    // -------------------------
    public static function setUserDisplayName($token, $name) {
        $client = new Client();
        try {
            $res = $client->request('PUT', self::SKAHost . "/user/me/displayName", [
                'headers' => [
                    'authorization' => "Bearer $token",
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'displayName' => $name
                ])
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {

        }
    }

    // Neue Beschreibung setzen
    // ------------------------
    public static function setUserDescription($token, $desc) {
        $client = new Client();
        try {
            $res = $client->request('PUT', self::SKAHost . "/user/me/description", [
                'headers' => [
                    'authorization' => "Bearer $token",
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'description' => $desc
                ])
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {

        }
    }

    // Neues Profilbild setzen
    // -----------------------
    public static function setUserProfilePicture($token, $filename, $stream) {
        $client = new Client();
        try {
            $res = $client->request('POST', self::SKAHost . "/user/me/upload", [
                'headers' => [
                    'authorization' => "Bearer $token",
                ],
                'multipart' => [
                    [
                        'name' => 'pfp',
                        'filename' => $filename,
                        'contents' => $stream,
                    ]
                ]
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return false;
        }

        return true;
    }
}