<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 26/08/13
 * Time: 16:16
 * To change this template use File | Settings | File Templates.
 */

/*
* Last updated by NSH - added in "runinsert" and $USERNAME $PASSWORD
*/

require_once "arithon_config.php";

function rc_runquery($query, $no_array_result = 0, $db_server = null) {
    if ($db_server == null) $db_server = $GLOBALS["MYSQL_DB_HOST"];

    // Take configuration info from config file
    $config["db_user"] = $GLOBALS["MYSQL_DB_USER"];

    //echo "Global MYSQL_DB_HOST:" . $GLOBALS["MYSQL_DB_HOST"];
    $config["db_pass"] = $GLOBALS["MYSQL_DB_PASS"];
    $config["db"] = $GLOBALS["ROUNDCUBE_DB"];

    // Establish Permanaent DB Connection
    $link_id = mysql_connect($db_server, $config["db_user"] ,$config["db_pass"]);
    mysql_select_db($config["db"], $link_id);
    //	$result = mysql_db_query($config["db"], $query, $link_id);
    $result = mysql_query($query, $link_id);

    /*
     If we get a positive result return the array with a field containing the result ID
    so that it can be re-used for further row requests, otherwise return -1 to signify an error
    */
    if($result ===true) {
        $return["ResultID"] = $result;
    }
    elseif ($result && ! $no_array_result) {
        $return = mysql_fetch_array($result, MYSQL_ASSOC);
        $return["ResultID"] = $result;
    } else {
        $return["ResultID"] = "-1";
        $return["ERROR"] = mysql_error();
    }

    $no_array_result = 0;
    return $return;
}


function runquery($query, $no_array_result = 0, $db_server = null) {
    if ($db_server == null) $db_server = $GLOBALS["MYSQL_DB_HOST"];

    // Take configuration info from config file
    $config["db_user"] = $GLOBALS["MYSQL_DB_USER"];

    //echo "Global MYSQL_DB_HOST:" . $GLOBALS["MYSQL_DB_HOST"];
    $config["db_pass"] = $GLOBALS["MYSQL_DB_PASS"];
    $config["db"] = $GLOBALS["MYSQL_ARITHON_DB"];

    // Establish Permanaent DB Connection
    $link_id = mysql_connect($db_server, $config["db_user"] ,$config["db_pass"]);
    mysql_select_db($config["db"], $link_id);
//	$result = mysql_db_query($config["db"], $query, $link_id);
    $result = mysql_query($query, $link_id);

    /*
        If we get a positive result return the array with a field containing the result ID
        so that it can be re-used for further row requests, otherwise return -1 to signify an error
    */
    if($result ===true) {
        $return["ResultID"] = $result;
    }
    elseif ($result && ! $no_array_result) {
        $return = mysql_fetch_array($result);
        $return["ResultID"] = $result;
    } else {
        $return["ResultID"] = "-1";
        $return["ERROR"] = mysql_error();
    }

    $no_array_result = 0;
    return $return;
}

function runinsert($query, $no_array_result = 0, $db_server = null) {
    if ($db_server == null) $db_server = $GLOBALS["MYSQL_DB_HOST"];
    // Take configuration info from config file
    $config["db_user"] = $GLOBALS["MYSQL_DB_USER"];

    $config["db_pass"] = $GLOBALS["MYSQL_DB_PASS"];
    $config["db"] = $GLOBALS["MYSQL_ARITHON_DB"];

    // Establish Permanaent DB Connection
    $link_id = mysql_connect($db_server, $config["db_user"] ,$config["db_pass"]);
    mysql_select_db($config["db"], $link_id);
//	$result = mysql_db_query($config["db"], $query, $link_id);
    $result = mysql_query($query, $link_id);
    /*
    */
    if ($result && ! $no_array_result) {
        //$return = mysql_fetch_array($result);
        $return["ResultID"] = $result;
    } else {
        $return["ResultID"] = "-1";
        $return["ERROR"] = mysql_error();
    }

    $no_array_result = 0;
    return $return;
}


function runquery_remote($query, $no_array_result = 0) {
    // Take configuration info from config file
    $config["db"] = $GLOBALS["MYSQL_ARITHON_DB"];
    $db_server = $GLOBALS["MYSQL_DB_HOST"];
    $db_user = $GLOBALS["MYSQL_DB_USER"];
    $db_pass = $GLOBALS["MYSQL_DB_PASS"];
// Establish Permanaent DB Connection
    $link_id = mysql_connect($db_server, $db_user ,$db_pass);
    #echo "Server:" . $db_server ." DB user:". $db_user ."DB Pass: ". $db_pass . "\n";
    mysql_select_db($config["db"], $link_id);
//	$result = mysql_db_query($config["db"], $query, $link_id);
    $result = mysql_query($query, $link_id);
    //mail("e.kennedy@nsp.ie", "Login info", $result);

    /*
        If we get a positive result return the array with a field containing the result ID
        so that it can be re-used for further row requests, otherwise return -1 to signify an error
    */
    if($result ===true) {
        $return["ResultID"] = $result;
    }
    elseif ($result && ! $no_array_result) {
        $return = mysql_fetch_array($result);
        $return["ResultID"] = $result;
    }
    else {
        $return["ResultID"] = "-1";
        $return["ERROR"] = mysql_error();
    }

    $no_array_result = 0;
    return $return;
}

function generate_insert_values ($formdata) {
    foreach($formdata as $key => $value) {
        if (! is_numeric($key)) {
            if (isset($keys)) $keys .= ", ".$key;
            else $keys = $key;
            if (isset($values)) $values .= ", '".$value."'";
            else $values = "'".$value."'";
        }
    }
    $return["keys"] = $keys;
    $return["values"] = $values;
    return $return;
}

function generate_update_values ($formdata) {
    foreach($formdata as $key => $value) {
        if (! is_numeric($key)) {
            if (isset($list)) $list .= ", ".$key." = '".$value."'";
            else $list .= $key." = '".$value."'";
        }
    }
    return $list;
}

$GLOBALUSERNAME = 'support@arithon.com';
$GLOBALPASSWORD = 'qud22peS6';