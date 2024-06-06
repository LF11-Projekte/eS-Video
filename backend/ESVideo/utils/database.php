<?php
// ----------------------------------------------------------------------------
// Kleine Helferklasse zum Vereinfchen von Datenbankabfragen mit Prepared-
// Statements zur Verhinderung von SQL-Injections 
// ----------------------------------------------------------------------------

class database
{
    private PDO $pdo;

    function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=esvideo",
            "php", "localphp"); // Datenbanklogin
    }

    // Query function for prepared statements
    public function Query(string $query, $args) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        return $stmt->fetchAll();
    }
}