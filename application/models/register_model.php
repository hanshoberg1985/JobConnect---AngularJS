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

class Register_model extends CI_Model
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

    function register() {
        $db = $this->get_db_info();
        $dbCand = $this->get_db_info();
        $dbClnt = $this->get_db_info();
        $dbCont = $this->get_db_info();
        
        $sql = "SELECT MAX(cand_id)+1 AS cid FROM candinfo";
        $dbCand->query($sql);
        $dbCand->next_record();
        $cand_id = $dbCand->f('cid');
        
        $sql = "SELECT MAX(clnt_id)+1 AS cid FROM clntinfo";
        $dbClnt->query($sql);
        $dbClnt->next_record();
        $clnt_id = $dbClnt->f('cid');
        
        $sql = "SELECT MAX(cont_id)+1 AS cid FROM cont";
        $dbCont->query($sql);
        $dbCont->next_record();
        $cont_id = $dbCont->f('cid');
        
        $userType = $_POST['reg-usertype'];
        $firstName = $_POST['reg-firstname'];      
        $surName = $_POST['reg-surname'];      
        $email = $_POST['reg-email'];
        $pwd = $_POST['reg-pwd'];
        
        $pwd = MD5($pwd);
        
        $today = date("d-M-y");
        
        if($userType == "candidate") {
            $sql = "INSERT INTO candinfo (cand_id,forename,surname,email,custom1,user_id,entered,updated,entered_by,updated_by)
                    VALUES ('$cand_id','$firstName','$surName','$email','$pwd','$email','$today','$today','$email','$email')";
            $db->query($sql);
            
            return array(
                'userType' => 'candidate',
                'id' => $cand_id,
                'forename' => $firstName,
                'surname' => $surName,
                'email' => $email,
                'user_id' => $email
            );    
        } else if($userType == "recruiter") {
            $sql = "INSERT INTO clntinfo (clnt_id,user_id,entered,updated,entered_by,updated_by)
                    VALUES ('$clnt_id','$email','$today','$today','$email','$email')";     
            $db->query($sql);
            
            $sql = "INSERT INTO cont (cont_id,clnt_id,forename,surname,email,cont_custom1,user_id,entered,updated,entered_by,updated_by)
                    VALUES ('$cont_id','$clnt_id','$firstName','$surName','$email','$pwd','$email','$today','$today','$email','$email')";        
            $db->query($sql);
            
            return array(
                'userType' => 'recruiter',
                'id' => $cont_id,
                'forename' => $firstName,
                'surname' => $surName,
                'email' => $email,
                'user_id' => $email
            );
        }
        
        
    }
}