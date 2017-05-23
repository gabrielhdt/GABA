<?php

$servername = 'localhost';
$username = 'gaba';
$dbname = 'IENAC_GABA';
$password = 'abag';
$charset = 'utf8mb4';

function arithmetic_mean($real_arr) {
    return array_sum($real_arr)/count($real_arr);
}

function add_line($table, $values)
{
    /* table a string, values an associtive array:
     * 'column name' => array('value' => mixed, 'type' => PDO type)
     * will result in
     * INSERT INTO (colname1, ...) VALUES ('value1', ...)
     * not exactly same args as other functions, due to the keyword
     * VALUES in the middle. Might be changed.
     */
    $query = 'INSERT INTO ' . $table . ' ';
    $columns = array_keys($values);  // Keys are ordered here
    $num_adds = count($values);
    $query .= '(' . implode(', ', $columns) . ')';
    $query .= ' VALUES ';
    $query .= '(' . implode(', ', array_fill(0, $num_adds, '?')) . ')';
    $query .= ';';
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $stmt = $conn->prepare($query);
        $qumarkcounter = 1;
        foreach ($columns as $col)
        {
            $stmt->bindValue($qumarkcounter,
                mb_strtolower($values[$col]['value']), $values[$col]['type']);
            $qumarkcounter++;
        }
        $stmt->execute();
        $id_addition = $conn->lastInsertId();
    } catch (PDOException $e) {
        echo "Something went wrong (add_line): " . $e->getMessage();
        $conn = null;
        return(false);
    }
    $conn = null;
    return($id_addition);
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
        return(false);
    }
    $conn = null;
    return(true);
}

function get_values($select,
    $tables, $where=array(), $groupby='', $having=array(),
    $orderby='', $fetch_style=PDO::FETCH_ASSOC)
{
    /* $select, tables, orderby, groupby: strings, as would appear in the
     * sql query, but without the keywords.
     * $where and having:
     *  array('str' => query with ? instead of values,
     *        'valtype' => array(
     *            i => array('value' => value of the i-th ?,
     *                       'type' => pdo type of value
     *             )
     *        )
     *    )
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $query = "SELECT $select FROM $tables";
        $query .= $where ? ' WHERE '.$where['str'] : null;
        $query .= $groupby ? ' GROUP BY '.$groupby : null;
        $query .= $having ? ' HAVING '.$having['str'] : null;
        $query .= $orderby ? ' ORDER BY '.$orderby : null;
        $query .= ';';
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare($query);
        $qumarkcounter = 1; // ? Indexed from 1
        if ($where)
        {
            foreach ($where['valtype'] as $whval)
            {
                $stmt->bindValue($qumarkcounter, $whval['value'], $whval['type']);
                $qumarkcounter++;
            }
        }
        if ($having && isset($having['valtype']))  // If having depends on var
        {
            foreach ($having['valtype'] as $hvval)
            {
                $stmt->bindValue($qumarkcounter, $hvval['value'], $hvval['type']);
                $qumarkcounter++;
            }
        }
        $stmt->execute();
        $stmt->setFetchMode($fetch_style);
        $rslt = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Something went wrong (get_values): '.$e->getMessage();
        $conn = null;
        return(false);
    }
    $conn = null;
    return $rslt;
}


function get_columns($table)
{
    // outputs array of colummns of $table
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
        echo 'Something went wrong (get_columns): ' . $e->getMessage();
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
        echo 'Something went wrong (main_tables_from_keys): ' .
            $e->getMessage();
    }
    $conn = null;
    return $key_table;
}

function update_line($table, $updates, $where)
{
    /* table a string,
     * updates and where are the same type of array, i.e.
     * where['str'] is the sql string with '?' instead of values
     * where['valtype'] is array of
     * array('value' => the value, 'type' => PDO type)
     * order of elements in valtype arrays MUST match the order of '?' in
     * the string
     */
    $query = 'UPDATE ' . $table . ' SET ';
    $query .= $updates['str'];
    $query .= ' WHERE ';
    $query .= $where['str'];
    $query .= ';';
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $stmt = $conn->prepare($query);
        $qumarkcounter = 1;
        foreach ($updates['valtype'] as $upvt)
        {
            $stmt->bindValue($qumarkcounter,
                mb_strtolower($upvt['value']), $upvt['type']);
            $qumarkcounter++;
        }
        foreach ($where['valtype'] as $wh)
        {
            $stmt->bindValue($qumarkcounter, $wh['value'], $wh['type']);
            $qumarkcounter++;
        }
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Something went wrong (update_line): " . $e->getMessage();
        $conn = null;
        return(false);
    }
    $conn = null;
    return(true);
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
        echo 'Something went wrong (classify_process): ' . $e->getMessage();
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

        if ($authok && $login == "admin"){
            $id = 'admin';
        } elseif ($authok) {
            $id = 'staff';
        } else {
            $id = FALSE;
        }

    } catch (PDOException $e) {
        echo 'Something went wrong (verify_login): ' . $e->getMessage();
    }
    $conn = null;
    return $id; // qui se connecte ? (admin/staff/invalide)
}

function id_from_login($login)
{
    /* Returns id of a give $login name
     * Works because logins are bijectively linked to ids
     * $login: string
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT (idStaff) FROM Staff WHERE login=?";
        $stmt = $conn->prepare($query);
        $stmt -> bindParam(1, $login, $data_type=PDO::PARAM_STR,
            $length=12);
        $stmt -> execute();
        $rslt = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Something went wrong (id_from_login): ' . $e->getMessage();
        return(false);
    }
    $conn = null;
    return $rslt['idStaff'];
}

function format_msg($id, $date, $name, $email, $msg){
    /* fonction qui formate l'affichge d'un message pour l'affichge sur la page
     * de l'admin
     */
    echo <<<FMT
<div class='alert alert-info alert-dismissable'>
<a href='#' onclick="myDelete('$id')" class='close' data-dismiss='alert'
aria-label='close'>&times;</a>
Date : $date
<hr>
Nom : $name
<hr>
E-mail : $email
<hr>
Message : $msg
</div>
FMT;
}

function list_msg(){
    // recuperation et affichages des messages pour l'administrateur
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",
                        $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $reponse = $conn->query('SELECT * FROM messages ORDER BY date desc');
        while ($donnees = $reponse->fetch())  {
            format_msg($donnees['id'], $donnees['date'], $donnees['name'],
                $donnees['email'], $donnees['message'])."\n";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

function delete_msg($id)
{
    // fonction qui sumprime le message $id de la base de données par l'admin
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE FROM messages WHERE id=$id";
        $conn->exec($query);
    } catch (PDOException $e) {
        echo 'Insertion failed (delete_msg): ' . $e->getMessage();
    }
    $conn = null;
}

function distinct_measure($idFollowed) {
    /* fonction qui renvoie un tableau de tout les types de mesures connues pour
     * pour le followed $idFollowed
     */
    $where['str'] = 'Measure.idFollowed=?';
    $where['valtype'] = array(
        array('value' => $idFollowed, 'type' => PDO::PARAM_STR)
    );
    $tables = <<<TBL
MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure=Measure.idMeasure
TBL;
    $distinct_type = get_values('DISTINCT MiscQuantity.type',
        $tables, $where
    );
    return $distinct_type;
    }

function latest_meas_of($idFollowed) {
    /* Returns all latest measures (of each type) of followed idfollowed
     * each line contains: type of measure, unit, value and date
     * it should return the last value
     */
    $distinct_type = distinct_measure($idFollowed);
    $rslt = array();
    foreach ($distinct_type as $key) {
        $rslt[] = latest_meas_type($idFollowed, $key['type']);
    }
    return $rslt;
}

function latest_meas_type($idfollowed, $type) {
    // Donne la dernière mesure de type $type pour un followed donné
    $select = "MAX(date_measure) AS last_date";
    $tables = "MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure=Measure.idMeasure
               INNER JOIN Staff ON Staff.idStaff=Measure.idStaff";
    $where = array('str' => "idFollowed=? AND MiscQuantity.type=?", 'valtype' => array(
                array('value' => $idfollowed, 'type' => PDO::PARAM_INT),
                array('value' => $type, 'type' => PDO::PARAM_STR)));
    $date_last_measure = get_values($select, $tables, $where);
    $select = "MiscQuantity.type, value, unit, date_measure, login";
    $where = array('str' => "idFollowed=? AND MiscQuantity.type=? AND date_measure=?",
                   'valtype' => array(
                array('value' => $idfollowed, 'type' => PDO::PARAM_INT),
                array('value' => $type, 'type' => PDO::PARAM_STR),
                array('value' => $date_last_measure[0]["last_date"],
                'type' => PDO::PARAM_INT)));
    $rslt = $date_last_measure = get_values($select, $tables, $where);
    return $rslt[0];
}
?>
