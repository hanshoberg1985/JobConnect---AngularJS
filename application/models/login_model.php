<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 26/08/13
 * Time: 15:57
 * To change this template use File | Settings | File Templates.
 */

require_once 'application/config/utils.inc.php';
require_once("application/config/db_oci8.inc");
require_once 'application/config/arithon_config.php';

class Login_model extends CI_Model
{
    public function __construct()
    {
        $this->load->library('db_object');
    }
    
    function get_db_info()
    {
        $sql_query = "SELECT * FROM arithon_users WHERE db_username = 'arithon_7736'";
        $arithonUser = runquery($sql_query);

        $clientID = $arithonUser["ID"];

        $db = new db_object;
        $db->User = $arithonUser["db_username"];
        $db->Password = $arithonUser["db_password"];
        $db->Database = $arithonUser["connect_string"];

        return $db;
    }

    function login() {
        $username = $_POST['username'];
        $pwd = $_POST['password'];        
        
        $pwd = MD5($pwd);
        
        $dbCand = $this->get_db_info();
        $dbCont = $this->get_db_info();
        
        $userInfo = array();
        
        $queryCand = "SELECT * FROM candinfo WHERE email='" . $username . "' AND custom1='" . $pwd . "'";
        $queryCont = "SELECT * FROM cont WHERE email='" . $username . "' AND cont_custom1='" . $pwd . "'";

        $dbCand->query($queryCand);
        $dbCont->query($queryCont);
        
        $dbCand->next_record();
        $dbCont->next_record();
        
        if($dbCand->affected_rows() > 0) {
            $cand_id = $dbCand->f('cand_id');
            $forename = $dbCand->f('forename');
            $surname = $dbCand->f('surname');
            $email = $dbCand->f('email');
            $user_id = $dbCand->f('user_id');
            
            $userInfo = array(
                'userType' => 'candidate',
                'id' => $cand_id,
                'forename' => $forename,
                'surname' => $surname,
                'email' => $email,
                'user_id' => $user_id
            );
            
            return $userInfo;                       
        } else if($dbCont->affected_rows() > 0) {
            $cont_id = $dbCont->f('cont_id');
            $forename = $dbCont->f('forename');
            $surname = $dbCont->f('surname');
            $email = $dbCont->f('email');
            $user_id = $dbCont->f('user_id');
            
            $userInfo = array(
                'userType' => 'recruiter',
                'id' => $cont_id,
                'forename' => $forename,
                'surname' => $surname,
                'email' => $email,
                'user_id' => $user_id
            );
            
            return $userInfo;
        } else {
            return null;
        }        
    }
}