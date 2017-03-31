<?php

$servername = "localhost";
$username = "gaba";
$dbname = "IENAC_GABA";
$password = "abag";

function insert_species($name, $kingdom, $phylum, $class, $order, $family,
    $conservation_status) {
    global $servername, $username, $dbname, $password;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO Species (name, kingdom, phylum, class, order_s, ";
        $query .= "family, conservation_status, footprint) ";
        $query .= "VALUES ('$name', '$kingdom', '$phylum', '$class', '$order', ";
        $query .= "'$family', '$conservation_status', NULL);";

        $conn->exec($query);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $conn = null;
}
?>
