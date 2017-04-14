<?php

$servername = "localhost";
$username = "gaba";
$dbname = "IENAC_GABA";
$password = "abag";

function add_species($name, $kingdom, $phylum, $class, $order, $family,
    $conservation_status) {
    global $servername, $username, $dbname, $password;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = <<<QRY
INSERT INTO Species (name, kingdom, phylum, class, order_s, family,
conservation_status, footprint)
VALUES ($name, $kingdom, $phylum, $class, $order, $family, $conservation_status,
NULL);
QRY;
        $conn->exec($query);
    } catch (PDOException $e) {
        echo "Insertion failed: " . $e->getMessage();
    }
    $conn = null;
}

function add_followed($idSpecies, $gender, $health, $idFacility = NULL,
    $birth = NULL, $death = NULL)
{
    global $servername, $username, $dbname, $password;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = <<<QRY
INSERT INTO Followed (gender, birth, death, health, idSpecies, idFacility)
VALUES ($gender, $birth, $death, $health, $idSpecies, $idFacility);
QRY;
        $conn->exec($query);
    } catch (PDOException $e) {
        echo "Insertion failed: " . $e->getMessage();
    }
}

function add_staff($password, $type, $first_name, $last_name)
{
    global $servername, $username, $dbname, $password;
    // Processing names
    $first_name = strtolower($first_name);
    $last_name = strtolower($last_name);
    $last_name_patt = "#(\S{2,3}\s){0,2}(\S*)#";
    $login = preg_replace($last_name_patt, "$2", $last_name);
    $login .= $first_name;
    // Password hashes must be stored in at least 255 chars (with PW_DEFAULT)
    // algorithm
    $pwhash = password_hash($password, $PASSWORD_DEFAULT);
}
?>
