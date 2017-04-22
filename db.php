<?php

$servername = 'localhost';
$username = 'gaba';
$dbname = 'IENAC_GABA';
$password = 'abag';
$charset = 'utf8mb4';

function add_line($table, $valarr)
{
    // Values are set to lowercase!
    // $valarr["column name"] = column_value
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO $table";
        $columns = '(';
        $values = '(';
        foreach ($valarr as $col => $val)
        {
            $columns .= $col . ', ';
            $values .= mb_strtolower($val) . ', ';
        }
        $columns = rtrim($columns, ' ,'); //Removes last ', '
        $values = rtrim($values, ' ,');
        $columns .= ')';
        $values .= ')';
        $query .= " $columns VALUES $values;";
        $conn->exec($query);
    } catch (PDOException $e) {
        echo 'Insertion failed: ' . $e->getMessage();
    }
    $conn = null;
}

function add_staff($password, $type, $first_name, $last_name)
{
    // Verify inputs with regexes
    $typeok = preg_match('/^\w*$/', $type);  // One word
    $fnameok = preg_match('/^(\w|-)*$/', $first_name);  // One word with dashes
    // Processing names
    $first_name = mb_strtolower($first_name);
    $last_name = mb_strtolower($last_name);
    $last_name_patt = '/(\s)*(\w{2,3}\b(\s)+){0,2}(\w*\b)/';
    $lname_filtered = preg_replace($last_name_patt, "$4", $last_name);
    if (strlen($lname_filtered) < 6)
    {
        $login = $lname_filtered;
    }
    else
    {
        $login = mb_substr($lname_filtered, 0, 6);
    }
    $login .= mb_substr($firstname, 0, 2);
    // Password hashes must be stored in at least 255 chars (with PW_DEFAULT)
    // algorithm
    $pwhash = password_hash($password, PASSWORD_DEFAULT);

    // Database input
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Is login existing?
        $query = <<<QRY
SELECT login FROM Staff WHERE login LIKE '$login' COLLATE utf8mb4_unicode_ci;
QRY;
        $stmt = $conn->query($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rslt = $stmt->fetchAll();
        if (count($rslt) > 0)
        {
            $login .= (count($rslt) + 1);
        }
        // Login set: input data
        $query = <<<QRY
INSERT INTO Staff (login, pwhash, type, first_name, last_name)
VALUES ('$login', '$pwhash', '$type', '$first_name', '$last_name');
QRY;
        $conn->exec($query);
    } catch (PDOException $e) {
        echo "Something went wrong: " . $e->getMessage();
    }
    $conn = null;
}

function get_values($table, $columns)
{
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT (';
        foreach ($columns as $col)
        {
            $query .= "$col, ";
        }
        $query = rtrim($query, ' ,');
        $query .= ") FROM $table;";
        $stmt = $conn->query($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rslt = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return $rslt;
}

function update_line($table, $change, $col_condition, $val_condition)
{
    // change : array('col' => 'val')
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE $table SET ";
        foreach ($change as $col => $val) {
            $query .= "$col='$val', ";
        }
        $query = rtrim($query, ' ,');
        $query .= " WHERE $col_condition='$val_condition';";
        $conn->exec($query);
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
}

function arithmetic_mean($real_arr) {
    return array_sum($real_arr)/count($real_arr);
}

function classify_process($table, $valc, $critc, $mod, $fct = arithmetic_mean)
{
    /* Let $valc and $critc two columns containing respectively values
     * (v_i) and criteria (c_i): each value has one criterium.
     * Let $mod be the set of classes, denoted (k_i),
     * Let E_i be the set of values whose criterium is in k_i.
     * This function outputs an array: k_i => $fct(E_i),
     * therefore $fct must be, considering the table Measures, from R^n to
     * anything.
     * In facts, $mod will contain classes boundaries.
     */
    $rslt = array();
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query_comm = "SELECT $valc FROM $table WHERE $critc BETWEEN ";
        for ($i = 0 ; $i < count($mod) - 1 ; $i++)
        {
            $query_end = $mod[$i] . ' AND ' . $mod[$i + 1] .';';
            $stmt = $conn->query($query_comm . $query_end);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rslt[$mod[$i]] = $fct($stmt->fetchAll());
        }

    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return $rslt;
}

function verify_login($login, $pwd){
    // fonction de test login/pwd pour la connexion
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT login FROM Staff WHERE login=?";
        $stmt = $conn->prepare($query);
        stmt -> binParam(1, $login, PDO::PARAM_STR, 30);
        $stmt -> execute();
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if ($result != null) { // test login in database
            $query = "SELECT pwhash FROM Staff WHERE login=?";
            $stmt = $conn->prepare($query);
            stmt -> binParam(1, $login, PDO::PARAM_STR, 30);
            $stmt -> execute();
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);
            if (password_hash($pwd, PASSWORD_DEFAULT) == $result) { // test hash(pwd) ok
                echo "ok"
            }
        }
        else {
            echo  "login ou mot de passe incorrect"
        }
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;

}
?>
