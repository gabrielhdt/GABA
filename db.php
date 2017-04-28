<?php

$servername = 'localhost';
$username = 'gaba';
$dbname = 'IENAC_GABA';
$password = 'abag';
$charset = 'utf8mb4';

function build_where($val, $col)
{
    /* $col and $val such as WHERE $col =|LIKE $val
     * returns where query with LIKE or = depending on datatype
     */
    $query = '';
    if (gettype($val) == 'string')
    {
        $query .= "'$col' LIKE '%$val%'";
    }
    else
    {
        $query .= "'$col' = $val";
    }
    return($query);
}

function array_map_keys($callback, $array)
{
    /* like map but callback accepts two parameters:
     * first is value, second is the key
     * returns: array containing (f($val1, $key1), ..., f($valn, $keyn))
     */
    return(array_map($callback, array_values($array), array_keys($array)));
}

function arithmetic_mean($real_arr) {
    return array_sum($real_arr)/count($real_arr);
}

function add_line($table, $valarr)
{
    /* Values are set to lowercase!
     * $valarr["column name"] = column_value
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO $table";
        $columns = '(';
        $values = '(';
        foreach ($valarr as $col => $val)  //Implode not used to keep order
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
    $login .= mb_substr($first_name, 0, 2);
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

function joined_view($view_name, $table_ref, $tables_to_join)
{
    /* tables_to_join tablename => column join on
     * returns: list of columns available
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "CREATE VIEW $view_name AS SELECT * FROM";
        $query_tables = "";
        $wherearr = array();
        $col_ref = $tables_to_join[$table_ref];
        foreach ($tables_to_join as $table => $joincol)
        {
           $query_tables .= $table . ', ';
           $wherearr["$table.$joincol"] = "$table_ref.$col_ref";
        }
        $query_tables = rtrim($query_tables, ' ,');
        $conn -> exec($query);
        $viewcols = get_columns($view_name);
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return($viewcols);
}

function get_values($select, $table, $where=array())
{
    /* select: array of selected fields
     * $table: name of table
     * where: array 'column name' => 'value'
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT ';
        $query .= implode(', ', $select);
        $query .= " FROM $table";
        if (!$where)
        {
            $query .= ';';
        }
        else
        {
            $query.= ' WHERE ';
            $whereqrys = array();
            $whereqrys = array_map_keys("build_where", $where);
            $query .= implode($whereqrys, ' AND ');
            $query .= ';';
        }
        echo $query;
        $stmt = $conn->query($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rslt = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return $rslt;
}

function get_columns($table)
{
    /* outputs array of colummns of $table 
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SHOW columns FROM $table;";
        $stmt = $conn->query($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rslt = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return $rslt;
}

function main_tables_from_keys()
{
    /* returns associative array
     * $table => $primary_key
     * does not include table containing two primary keys
     * possible bug with classes
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SHOW tables;";
        $stmt = $conn->query($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tables_zipped = $stmt->fetchAll();
        $tables = array();
        // unzipping
        foreach ($tables_zipped as $tab)
        {
            array_push($tables, $tab['Tables_in_IENAC_GABA']);
        }

        $key_table = array();
        foreach ($tables as $table)
        {
            $query = "SHOW INDEX FROM $table;";
            $stmt = $conn -> query($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $pkeys = $stmt -> fetchAll();
            if (count($pkeys) == 1) {
                $key_table[$pkeys[0]['Column_name']] = $table;
            }
        }
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return $key_table;
}


function update_line($table, $change, $col_condition, $val_condition)
{
    /* change : array('col' => 'val')
     * updates line satisfying $col_condition = $val_condition
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE $table SET ";

        $uparr = array_map_keys(function($col, $val) {
            return("'$col' = '$val'");
        }, $change);
        $query .= implode($uparr, ', ');

        $query .= " WHERE $col_condition='$val_condition';";
        $conn->exec($query);
    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
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
        $stmt -> bindParam(1, $login, PDO::PARAM_STR, 30);
        $stmt -> execute();
        $log = $stmt -> fetch(PDO::FETCH_ASSOC);

        $query = "SELECT pwhash FROM Staff WHERE login=?";
        $stmt = $conn->prepare($query);
        $stmt -> bindParam(1, $login, PDO::PARAM_STR, 30);
        $stmt -> execute();
        $hpwd = $stmt -> fetch(PDO::FETCH_ASSOC);

        $authok = $log && password_verify($pwd, $hpwd['pwhash']); // bon password ?

    } catch (PDOException $e) {
        echo 'Something went wrong: ' . $e->getMessage();
    }
    $conn = null;
    return $authok; // bon duo login pwd ? (bool)
}
?>
