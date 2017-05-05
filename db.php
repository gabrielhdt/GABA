<?php

$servername = 'localhost';
$username = 'gaba';
$dbname = 'IENAC_GABA';
$password = 'abag';
$charset = 'utf8mb4';

function build_where($val, $col, $strict=FALSE)
{
    /* $col and $val such as WHERE $col =|LIKE $val
     * strict defines whether we force the use of '=' and no 'LIKE'
     * returns where query with LIKE or = depending on datatype
     */
    $query = '';
    if (gettype($val) == 'string' && !$strict)
    {
        $query .= "'$col' LIKE '%$val%'";
    }
    else
    {
        $query .= "'$col' = $val";
    }
    return($query);
}

function build_where_strict($val, $col)
{
    return("$col = $val");
}

function build_whereplus($where)
{
    /* where as defined in get_values
     * returns the part
     * WHERE $where[i]['field']$where[i]['binrel']$where['value']
     */
    $query= 'WHERE ';
    $whereqrys = array();  // Will contain separate criteria
    foreach ($where as $wh)
    {
        if (is_array($wh['value']))
        {
            // Typically, for IN (v1, v2, ...)
            $len = count($wh['value']);
            $paramlst = '('.implode(array_fill(0, $len, '?'), ', ').')';
            // Should contain '(?, ?, ?, ...)'
            array_push($whereqrys,
                $wh['field'].' '.$wh['binrel'].' '.$paramlst);
        }
        else
        {
            array_push($whereqrys, $wh['field'].$wh['binrel'].'?');
        }
    }
    $query .= implode($whereqrys, ' AND ');
    return($query);
}

function array_map_keys($callback, $array)
{
    /* like map but callback accepts two parameters:
     * first is value, second is the key
     * callback: function with two or three args
     * returns: array containing (f($val1, $key1), ..., f($valn, $keyn))
     */
    return(array_map($callback, array_values($array), array_keys($array)));
}

function arithmetic_mean($real_arr) {
    return array_sum($real_arr)/count($real_arr);
}

function view_exists($viewname)
{
    /* $viewname: string, name of a view
     * returns: boolean, TRUE if view exists, else FALSE
     */
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = <<<QRY
SHOW FULL tables WHERE Table_type='VIEW';
QRY;
        $stmt = $conn -> query($query);
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $views = $stmt -> fetchAll();
        $exists = false;
        foreach ($views as $view)
        {
            $exists = $exists || $view['Tables_in_IENAC_GABA'] == $viewname;
        }
    } catch (PDOException $e) {
        echo 'Something went wrong (output_views): ' . $e->getMessage();
    }
    $conn = null;
    return($exists);
}

function add_line($table, $valarr)
{
    /* Values are set to lowercase!
     * $valarr["column name"] = column_value
     * returns: id of last inserted row
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
            $values .= "'$val', ";
        }
        $columns = rtrim($columns, ' ,'); //Removes last ', '
        $values = rtrim($values, ' ,');
        $columns .= ')';
        $values .= ')';
        $query .= " $columns VALUES $values;";
        $conn->exec($query);
        $id_addition = $conn ->lastInsertId();
    } catch (PDOException $e) {
        echo 'Insertion failed (add_line): ' . $e->getMessage();
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

function get_values($select, $table, $where=array())
{
    /* select: array of selected fields
     * table: explicit
     * where: array of arrays, with
     * where[i] = array('binrel' => R, 'field' => field, 'value' =>  value,
     *  'type' => pdotype)
     * which results in the query
     * SELECT $select FROM $table WHERE
     * $where[i]['field'] $where[i]['binrel'] $where[i]['value']
     * In addition, $where[i]['value'] can be an array of values,
     * e.g. for WHERE field IN (v0, v1, ...)
     */
    global $servername, $username, $dbname, $password, $charset;
    if (!$select) {
        echo "Nothing to select in get_values, exiting\n";
        return(False);
    }
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT ';
        $query .= implode(', ', $select);
        $query .= " FROM $table";
        if ($where)
        {
            $query .= ' '.build_whereplus($where);
        }
        $query .= ';';
        $stmt = $conn->prepare($query);
        $qumarkcounter = 1;  // ? indexed from 1
        foreach ($where as $wh)
        {
            if (is_array($wh['value']))
            {
                $len = count($wh['value']);
                foreach($wh['value'] as $whelt)
                {
                    $stmt->bindValue($qumarkcounter, $whelt, $wh['type']);
                    $qumarkcounter++;
                }
            }
            else
            {
                $stmt->bindValue($qumarkcounter, $wh['value'], $wh['type']);
                $qumarkcounter++;
            }
        }
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rslt = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'Something went wrong (get_values): ' . $e->getMessage();
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
        echo 'Something went wrong (update_line): ' . $e->getMessage();
        return(true);
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
            $id = 'inconnu';
        }

    } catch (PDOException $e) {
        echo 'Something went wrong (verify_login): ' . $e->getMessage();
    }
    $conn = null;
    return $id; // qui se connecte ? (admin/staff/invalide)
}

function update_view($view)
{
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($view == 'vSearchFoll')
        {
            $query = <<<QRY
CREATE OR REPLACE VIEW vSearchFoll AS
SELECT Followed.idFollowed, Followed.idSpecies, Followed.idFacility,
    binomial_name AS sp_binomial_name,
    common_name AS sp_common_name,
    Facility.name AS fa_name,
    gender, birth, death, health
FROM Followed, Species, Facility
WHERE Followed.idSpecies = Species.idSpecies AND
    Followed.idFacility = Facility.idFacility;
QRY;
        }
        else
        {
            echo "No view named $view.\n";
            $conn = null;
            return(false);
        }
    $conn -> exec($query);
    } catch (PDOException $e) {
        echo 'Something went wrong (update_view): ' . $e->getMessage();
        return(false);
    }
    $conn = null;
    return(true);
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
        $qery = "SELECT (idStaff) FROM Staff WHERE login=?";
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
    echo <<<FMT
<div class='alert alert-info alert-dismissable'>
<a href='#' onclick="myDelete('$id')" class='close' data-dismiss='alert' aria-label='close'>&times;</a>
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
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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
    global $servername, $username, $dbname, $password, $charset;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset",
            $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE FROM messages WHERE id=$id";
        $conn->exec($query);
    } catch (PDOException $e) {
        echo 'Insertion failed (add_line): ' . $e->getMessage();
    }
    $conn = null;
}
?>
