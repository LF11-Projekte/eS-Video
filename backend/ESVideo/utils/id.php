<?php
// ----------------------------------------------------------------------------
// Kleine Helferklasse zur einfachen Umwandlung von Text-IDs in die von der
// Datenbank verwendeten numerischen IDs
// ----------------------------------------------------------------------------

class id {
    // ------------------------------------------------------------------------
    // Benutzer
    // ------------------------------------------------------------------------

    // ID Nummer -> Loginname
    public static function getAD($uid) {
        $db = new database();
        $res = $db->Query("SELECT LoginName FROM USER WHERE UID = :uid", [
            'uid' => $uid,
        ]);
        
        if (count($res) == 0) return false;
        return $res[0]['LoginName'];
    }
    
    // Loginname -> ID Nummer
    public static function getUID($ad) {
        $db = new database();
        $res = $db->Query("SELECT UID FROM USER WHERE LoginName = :ad", [
            'ad' => $ad,
        ]);

        if (count($res) == 0) return false;
        return $res[0]['UID'];Benutzer
    }

    // ------------------------------------------------------------------------
    // Videos
    // ------------------------------------------------------------------------

    // Video-"Schlüssel" -> ID Nummer
    public static function getVID($key) {
        $db = new database();
        $res = $db->Query("SELECT UID FROM VIDEO WHERE `Key` = :key", [
            'key' => $key,
        ]);

        if (count($res) == 0) return false;
        return $res[0]['UID'];
    }
}