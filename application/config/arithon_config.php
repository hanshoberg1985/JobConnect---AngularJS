<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 26/08/13
 * Time: 16:16
 * To change this template use File | Settings | File Templates.
 */

/**
 * Global configuration file
 *
 *
 */


$GLOBALS["BACKUPSERVER"] = '91.216.241.197';

// If the below is set, this will be used as the connect string instead of the value from arithon_users;
/*
if ($_SERVER['SERVER_ADDR'] == $GLOBALS["BACKUPSERVER"] || $_SERVER['SERVER_ADDR'] == '91.216.241.191' || $_SERVER['SERVER_ADDR'] == '91.216.241.192') {

   $GLOBALS["connect_string_override"] = "  (DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=db.ie.arithon.com)(PORT=1521)))(CONNECT_DATA=(SERVER=POOLED)(SID=arithon2))) ";
}
*/


// Application Server Address Load Balancer
$GLOBALS["APPLICATION_SERVER"]			= 'dev8-vi.arithon.com';
$GLOBALS["APPLICATION_PATH"]			= "/apache/dev2/html";

//EXTERNAL_ADDRESS = the address for the website update link
$GLOBALS["EXTERNAL_ADDRESS"]			= $GLOBALS["APPLICATION_SERVER"];

// Oracle connection
$GLOBALS["ORACLE_DB_HOST"]				= 'db1-vi.uk.arithon.com';//'eu-db.arithon.com';
//$GLOBALS["ORACLE_DB_HOST"]				= 'eu-backup.arithon.com';
$GLOBALS["ORACLE_USER"]					= 'system';
$GLOBALS["ORACLE_PASS"]					= 'TA8azu4edU';
$GLOBALS["ORACLE_DATAFILE_LOCATION"]	= '/u02/oradata/arithon2/';
$GLOBALS["ORACLE_BASE"]					= "/u01/app/oracle";
$GLOBALS["ORACLE_HOME"]					= "/u01/app/oracle/product/11.2.0/dbhome_1";
$GLOBALS["ORACLE_SID"]					= "arithon2";

$GLOBALS["NEW_ORACLE_SERVER"] = $GLOBALS["ORACLE_DB_HOST"];

// MySQL Connection

if ($_SERVER['SERVER_ADDR'] == $GLOBALS["BACKUPSERVER"]) {
    $GLOBALS["MYSQL_DB_HOST"]				= 'mysql.arithon.com';
} else {
    $GLOBALS["MYSQL_DB_HOST"]				= 'mysql.arithon.com';
}

$GLOBALS["MYSQL_DB_HOST_SLAVE"]               = '212.78.67.114';

$GLOBALS["MYSQL_DB_USER"]                               = 'typo3';
$GLOBALS["MYSQL_DB_PASS"]                               = 'y6mAcAPh4y';
$GLOBALS["MYSQL_ARITHON_DB"]                    = "arithon2009";

// Only this server is allowed to run mailq.php, and in startmailq.php we call mailq on the server given here
$GLOBALS["MAILQ_IP"]					= '212.78.67.111';
$GLOBALS["MAILQ_IP2"]					= '10.0.170.111';


// Setup & Process Transactions
$GLOBALS["SETUP"]						= $GLOBALS["APPLICATION_SERVER"];
$GLOBALS["ARITHON_SETUP_PATH"]			= $GLOBALS["APPLICATION_PATH"].'/ArithonSetup/';

// Mail processing
$GLOBALS["MAIL_PROCESSING"]				= $GLOBALS["APPLICATION_SERVER"];

// ROUNDCUBE DATABSE USERNAME AND PASSWORD
$GLOBALS["RC_DB_USER"] = "roundcube_mail";
$GLOBALS["RC_DB_PASS"] = "5lezie5l";
$GLOBALS["RC_DB_NAME"] = "roundcube_mail";
$GLOBALS["FILESYSTEM"] = "/var/www/filesys/";

$GLOBALS["LINKEDIN_API_KEY"] = "xn1rgmmgfxsv"; //u2zxicnuhp42"; //sm8l2uawe4ed";
$GLOBALS["LINKEDIN_API_SECRET"] = "G2iUFrB8geFPiCC6"; //"DSSBUZmaC4ad1OSY"; // //DSSBUZmaC4ad1OSY";

//$GLOBALS["PARSER_URL"] = 'http://daxtra.uk.arithon.com/cvvalid/CVXtractorService.dev2.wsdl';
$GLOBALS["PARSER_URL"] = 'http://daxtra.uk.arithon.com/cvvalid/CVXtractorService-dev.wsdl';


define('APP_KEY', '51355fe01945d66d7df7');
define('APP_SECRET', 'c5e6506fc916b09d54e7');
define('APP_ID', '49482');
?>