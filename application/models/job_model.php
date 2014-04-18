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

class Job_model extends CI_Model
{
    public function __construct()
    {
        $this->load->library('db_object');
        $this->load->model('dashboard_model');
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

    function get_job()
    {
        $jobs_id = $_POST['job_id'];

        $jobInfo = array();
        $db = $this->get_db_info();


        $oracle_query = "SELECT jobs.user_id, jobs.jobs_id, jobs.title, jobs.status, jobs.location, jobs.location2, jobs.location3, jobs.salary, jobs.salaryhigh, jobs.department, jobs.sector, jobs.type, jobs.jobs_custom1, jobs.jobs_custom2, jobs.starting, jobs.education, to_char(jobs.updated,'DY, DD MON YYYY HH24:MI:SS') as updated, comments.com, jobs.desc_internal,
                         jobs.jobs_custom1
                         FROM jobs, comments
                         WHERE jobs.jobs_id = " . $jobs_id . " AND jobs.status = 'Open'
                         AND jobs.title is not null AND comments.type = 'WEBSITE' AND LENGTH(comments.com) > 0
                         AND comments.jobs_id = jobs.jobs_id";


        $db->query($oracle_query);
        while ($db->next_record())
        {

            $user_id = $db->f('jobs_custom1');
            $jobs_id = $db->f('jobs_id');

            $jobtitle = preg_replace("/[^A-Za-z0-9\s\s+\.\:\-\/%+\*\&\$\#\!\@\(\)]/","",$db->f('title'));

            $sector = $db->f('sector');
            if (strlen($sector) < 1) $sector = "N/A";

            $type = $db->f('type');
            if (strlen($type) < 1) $type = "N/A";

            $education = $db->f('education');
            if (strlen($education) < 1) $education = "N/A";

            $location = $db->f('location');
            if (strlen($location) < 1) $location = "N/A";

            $salary = $db->f('salary');
            $salaryhigh = $db->f('salaryhigh');
            if (strlen($salary) > 0 && $salary != 0)
            {
                if (strlen($salaryhigh) > 1) $salary = $salary.' - '.$salaryhigh;
            }elseif (strlen($salary) < 1 || $salary = 0)
            {
                if (strlen($salaryhigh) > 1)
                {
                    $salary = $salaryhigh;
                }else{
                    $salary = "Salary Not Available";
                }
            }

            $starting = $db->f('starting');
            if (strlen($starting) < 1) $starting = "N/A";

            $description = $db->f('com');

            $description = utf8_encode($description);

            $jobInfo[] = array(
                'user_id' => $user_id, 'jobs_id' => $jobs_id,
                'jobTitle' => $jobtitle, 'sector' => $sector,
                'location' => $location,'salary' => $salary,
                'description' => $description, 'type' => $type,
                'education' => $education, 'starting' => $starting
            );
        }

        return $jobInfo;
    }

    function get_other_jobs()
    {

        $user_id = $_POST['user_id'];
        $jobInfo = array();
        $db = $this->get_db_info();

        $oracle_query = "SELECT clntinfo.company, jobs.user_id, jobs.jobs_id, jobs.title, jobs.status, jobs.location, jobs.location2, jobs.location3, jobs.salary, jobs.salaryhigh, jobs.department, jobs.sector, jobs.type, jobs.jobs_custom1, jobs.jobs_custom2, to_char(jobs.updated,'DY, DD MON YYYY HH24:MI:SS') as updated, comments.com, jobs.desc_internal
                         FROM jobs, comments, clntinfo
                         WHERE jobs.jobs_custom1 = '".$user_id."' and jobs.status = 'Open' AND jobs.title is not null AND comments.type = 'WEBSITE' AND LENGTH(comments.com) > 0 AND comments.jobs_id = jobs.jobs_id AND clntinfo.clnt_id = jobs.clnt_id";

        //added to limit to one row return per job
        $oracle_query .= " and comments.comment_id in (select max(comment_id)from comments where type = 'WEBSITE' group by jobs_id)";

        $oracle_query .= " order by jobs.updated desc";


        $db->query($oracle_query);

        $total = 0;

        while ($db->next_record() && $total < 10)
        {

            $jobtitle = preg_replace("/[^A-Za-z0-9\s\s+\.\:\-\/%+\*\&\$\#\!\@\(\)]/","",$db->f('title'));
            $user_id = $db->f('user_id');
            $jobs_id = $db->f('jobs_id');

            $location = $db->f('location');

            $salary = $db->f('salary');
            $salaryhigh = $db->f('salaryhigh');

            if (strlen($salary) > 0 && $salary != 0)
            {
                if (strlen($salaryhigh) > 1) $salary = $salary.' - '.$salaryhigh;
            }elseif (strlen($salary) < 1 || $salary = 0)
            {
                if (strlen($salaryhigh) > 1)
                {
                    $salary = $salaryhigh;
                }else{
                    $salary = "Salary Not Available";
                }
            }

            $jobInfo[] = array(
                'jobTitle' => $jobtitle, 'location' => $location,'salary' => $salary,
                'user_id' => $user_id, 'jobs_id' => $jobs_id
            );

            $total++;
        }
        return $jobInfo;

    }

    function connectDB($db_username, $db_password, $connect_string)
    {
        $db = new db_object;

        $db->User = $db_username;
        $db->Password = $db_password;
        $db->Database = $connect_string;

        return $db;
    }

    function send_app()
    {
        $sql_query = "SELECT * FROM arithon_users WHERE db_username = 'arithon_7736'";
        $arithonUser = runquery($sql_query);

        $clientID = $arithonUser["ID"];

        $this->dupeCheck($arithonUser["db_username"],$arithonUser["db_password"],$arithonUser["connect_string"],$arithonUser["paths"]);
    }

    function dupeCheck($db_username, $db_password, $connect_string, $paths)
    {
        $dbDupCheck = $this->connectDB($db_username, $db_password, $connect_string);
        $dbRecord = $this->connectDB($db_username, $db_password, $connect_string);
        $dbC = $this->connectDB($db_username, $db_password, $connect_string);
        $db = $this->connectDB($db_username, $db_password, $connect_string);
        $db2 = $this->connectDB($db_username, $db_password, $connect_string);

        $q = "SELECT cand_id, email, home_phone, forename, surname
                FROM candinfo
                    WHERE ( email ='".str_replace("'","''",$_POST["email"])."' )
                                OR (forename = '".str_replace("'","''",$_POST["forename"])."'
                                AND surname = '".str_replace("'","''",$_POST["surname"])."')";

        $dbDupCheck->query($q);

        $match_found = 0;
        $cand_id = 0;
        $message = "<div>";

        if ($dbDupCheck->next_record())
        {
            $check_next = 1;
            while ($check_next)
            {
                $match_found = 0;

                // get last 5 digits of phone number & strip out any whitespace to check for duplicates
                $user_phone = str_replace(' ', '', rtrim($_POST["home_phone"]));
                $user_5_digit = substr($user_phone, strlen($user_phone)-5, 5);

                // get last 5 digits of phone number in database & strip out any whitespace to check for duplicates
                $db_phone = str_replace(' ', '', rtrim($dbDupCheck->f('home_phone')));
                $db_5_digit = substr($db_phone, strlen($db_phone)-5, 5);

                if ( (strlen($_POST["email"]) > 0 && $_POST["email"] == $dbDupCheck->f('email')) || ($user_5_digit == $db_5_digit) )
                {
                    $match_found = 1;
                    $cand_id = $dbDupCheck->f('cand_id');
                    $cand_email = $dbDupCheck->f('email');

                    $message = '<p class="thanks">
                                        <img src="../images/thanks.jpg"/>
                                        Thank You For Your Application.
                                    </p>';

                    $q = "UPDATE candinfo SET updated = SYSDATE WHERE cand_id = '".$cand_id."'";
                    $dbRecord->query($q);
                    /*
                    if (!($dbRecord->affected_rows() > 0)){
                        echo "There was a problem updating an existing candidate record<BR>";
                    }
                    */
                    $check_next = 0;
                    if ($user_5_digit == $db_5_digit)
                    {
                        $cand_email = $_POST["email"];
                        // $GLOBALS["new_candidate"] = 1;
                    }
                    continue;
                }

                if($dbDupCheck->next_record()) {
                    null;
                }
                else {
                    $check_next = 0;
                }
            }
        }

        if (!$match_found)
        {
            $q = "select CANDID.NEXTVAL as cid from blank";
            $dbC->query($q);
            $dbC->next_record();
            $cand_id = $dbC->f('cid');

            $q = "INSERT INTO candinfo (cand_id,forename,surname,email,status,entered,updated,entered_by,updated_by,user_id,department,home_phone)
                    VALUES ('$cand_id','".str_replace("'","''",$_POST["forename"])."','".str_replace("'","''",$_POST["surname"])."','".str_replace("'","''",$_POST["email"])."','New Record',SYSDATE,SYSDATE,'careers@arithon.com','careers@arithon.com','careers@arithon.com','', '".str_replace("'","''",$_POST["home_phone"])."')";
            $dbRecord->query($q);

            $cand_email = $_POST["email"];

            //$message = "Thank you for your application. Your new Candidate ID is: <B>".$cand_id;

            $message = '<p class="thanks">
                                        <img src="../images/thanks.jpg"/>
                                        Thank You For Your Application.
                                    </p>';

            //$GLOBALS["new_candidate"] = 1;
        }

        $this->uploadFile($message,$paths, $cand_id, $db,$db2);

    }

    function uploadFile($message,$path,$cand_id,$db,$db2)
    {
        $pattern = "/[0-9a-f]{32}/";
        $filesystmp = "";
        preg_match($pattern,$path,$filesystmp);
        $filesys = '/var/www/filesys/'.$filesystmp[0].'/'; //NSH - filesys wasnt being passed over had to do this may 07

        $filename = $_FILES['resume']['name'];
        $filetype = $_FILES['resume']['type'];
        $filesize = $_FILES['resume']['size'];
        $tmp_name = $_FILES['resume']['tmp_name'];
        $file_error = $_FILES['resume']['error'];

        if ($filesize > 0)
        {
            if(!is_dir($filesys."cand/".$cand_id."/"))
            {
                umask(0000);
                @mkdir($filesys."cand/".$cand_id."/",0775);
                if(!is_dir($filesys."cand/".$cand_id."/")){
                 //   mail('m.esan@arithon.com@arithon.com','web_module not able to create new folder',$filesys."cand/".$cand_id);
                }
            }

            $attachmentDir = $filesys."cand"."/".$cand_id."/";

            if (strlen($filename) > 0 )
            {
                if (file_exists($attachmentDir.$filename))
                {
                    $attachmentPath = $attachmentDir.time().$filename;
                    $filename = time().$filename;
                }
                else {
                    $attachmentPath = $attachmentDir.$filename;
                }

                @move_uploaded_file($tmp_name, $attachmentPath);
                @chmod($attachmentPath,0755);
;
                $names = "filename,";
                $names .= "entered,";
                $names .= "created,";
                $names .= "type,";
                $names .= "use,";
                $names .= "attachment_path,";
                $names .= "cand_id";

                $values = "'".str_replace("'","''",$filename)."',";
                $values .= "SYSDATE,";
                $values .= "TO_DATE('".date('Y-m-d H:i:s',@filectime($filename))."','YYYY-MM-DD HH24:MI:SS'),";
                $values .= "'PRIVATE',";
                $values .= "'CV',";
                $values .= "'".str_replace("'","''",$attachmentPath)."',";
                $values .= "'$cand_id'";

                $q = "insert into attachments ($names,attach_id)  values ($values,ATTACHID.NEXTVAL)";
                $db->query($q);

                if (!($db->affected_rows() > 0)) {
                    echo "There was a problem inserting into the attachments table!<BR>";
                }
            }
        }

        $this->add_selection($message,$cand_id,$db);
    }

    function add_selection($message, $cand_id, $db)
    {
        $jobs_id = $_POST["jobs_id"];

        $q = "INSERT INTO sendouts (SELECT cont_id,clnt_id,'".$cand_id."','".$jobs_id."',user_id,'NONE',SYSDATE,SENDOUTID.NEXTVAL,'','','' from jobs where jobs_id=".$jobs_id." )" ;
        $db->query($q);

        if (!($db->affected_rows() > 0))
        {
            $message .= "<span>There was a problem with saving your application.<br>Please Refresh The Page and try again.</span>";
            echo $message;

        }else {
            $q = "INSERT INTO comments (jobs_id,cand_id,com,user_id,comment_id,entered,type)
                  VALUES ('".$jobs_id."','".$cand_id."','Application automatically received through Jobs Connect. Candidate was placed on Selection Screen.','careers@arithon.com',COMMENT_ID.NEXTVAL,SYSDATE,'EMAIL')";
            $db->query($q);

            if (!($db->affected_rows() > 0))
            {
                //echo "There was a problem inserting the comment relating to automatic selection screen insertion<BR>";
            }else{
                $tempQ = "UPDATE candinfo SET updated = SYSDATE, updated_by = 'careers@arithon.com' WHERE cand_id = '$cand_id'";
                $db->query($tempQ);
                $tempQ = "UPDATE jobs SET updated = SYSDATE, updated_by = 'careers@arithon.com' WHERE jobs_id = '$jobs_id'";
                $db->query($tempQ);
            }

            //$this->email_notify($message, $jobs_id, $cand_id, $db);

            /* Temp */
            $message .= "<span>Your Application has been sent.<br>You are now one step closer to your dream job/career.<br>Good Luck.</span></div>";

            echo $message;

        }
    }

    function email_notify($message, $jobs_id, $cand_id, $db)
    {

        $q = "SELECT forename, email from cont where email IN (SELECT jobs_custom1 FROM jobs WHERE jobs_id = '".$jobs_id."')";
        $db->query($q);


        if ($db->next_record())
        {

            $user_name = $db->f('forename');
            $user_email = $db->f('email');

            $email["message"] = '<B>Dear '.$user_name.',</B><BR><BR>
		You are receiving this email to notify you that a candidate has applied for your vacancy reference: <B>'.$jobs_id.'</B>. The candidate now has a record set up on Arithon with the unique ID: <B>'.$cand_id.'</B>.<BR>
		<BR>
		If you have any difficulties with this or feel that you have received email in error, please contact <A HREF="mailto:support@arithon.com">support@arithon.com</A>.<BR><BR>
		<BR><BR><B>
		DO NOT REPLY TO THIS EMAIL
		</B>';


/*
            $email["html_head"] = '
		<HTML>
		<STYLE type="text/css">
		td {
		font-family:arial,helvetica;
		text:#000000;
		color:#000000;
		font-weight: normal;
		font-size:11;
		text-align: justify;
	}
	A {
	font : arial, helvetica;
	font-family: arial, helvetica;
	font-size : 9pt;
	color : #336699;
	font-weight : normal;
	text-decoration: none;
	}
	BODY {
	font-family:arial,helvetica;
	text:#000000;
	color:#000000;
	font-weight: normal;
	font-size:11;
	}
	A:HOVER {
	font : arial, helvetica;
	font-family: arial, helvetica;
	font-size : 9pt;
	font-weight : normal;
	color : #ff3300;
	text-decoration: underline;
	}
	DIV.page {
	page-break-after: always;
	}


	</STYLE>*/
            $email["html_head"] = '<HTML><BODY bgcolor="#FFFFFF" LEFTMARGIN=0 RIGHTMARGIN=0>';

            $email["html_foot"] = '</BODY></HTML>';

            $body = '<TABLE border="1" BORDERCOLOR="#C0C0C0" STYLE="border: 1px" cellpadding="0" cellspacing="0" align="center" width="650" bgcolor="#ffffff">
		<TR>
		<TD>
		<TABLE border="0" cellpadding="0" cellspacing="0" align="center" width="650" bgcolor="#ffffff">
		<TR BGCOLOR="#FF9F00">
		<TD HEIGHT=15 COLSPAN=2><FONT COLOR="#FFFFFF"><B>.::</B></FONT></TD>
		</TR>
		<TR>
		<TD COLSPAN=2>
		<TABLE BORDER=0 WIDTH="95%" >
		<TR>
		<TD><IMG SRC="http://www.arithon.com/fileadmin/July03/images/logo.gif" WIDTH="221" HEIGHT="68" ALT="" BORDER="0"></TD>
		<TD><DIV ALIGN=RIGHT><BR><H3><FONT COLOR="#5050FF">ArithonASP</FONT><BR>New Applicant: '.$cand_id.'</H3></DIV></TD>
		</TR>
		</TABLE>
		</TD>
		</TR>
		<TR>
		<TD WIDTH=10>&nbsp;</TD>
		<TD>
		<TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0 WIDTH="100%">
		<TR>
		<TD COLSPAN=2><BR><BR>

		<BR>
		<FONT COLOR="#707070" SIZE=2>'.$email["message"].'<BR>

		</B></FONT>
		</TD>
		</TR>
		</TABLE>
		</TD>
		</TR>
		</TABLE>
		</TD></TR></TABLE>';

            $text_message = str_replace("<BR>", "\r\n", $email["message"]);
            $text = "ArithonASP New Applicant: ".$cand_id."\r\n
		\r\n
		".$text_message."\r\n\r\n";

            $html = $email["html_head"].$body.$email["html_foot"];

            require_once 'application/config/class.html.mime.mail.inc';
            $mail = new html_mime_mail('X-Mailer: ArithonASP');
            $mail->add_html($html, $text);
            $mail->set_charset('iso-8859-1', TRUE);
            $mail->build_message();

            $subject = 'Arithon: New Candidate Application - '.$cand_id.' for JobID: '.$jobs_id;

            //$mail->send($user_name, $user_email, 'Arithon Notification', 'noreply@arithon.com', $subject);
             if ($mail->send($user_name, 'm.esan@arithon.com', 'Arithon Notification', 'noreply@arithon.com', $subject))
             {
                 $message .= "<br><br>Your Application has been sent to ".$user_name." Good Luck.";
             }
        }

    }


    /*
                // if this is a new candidate, display the full form for them to fill in other details
                if ($GLOBALS["new_candidate"]) {
                    $content .= show_cand_form($cID, $cand_email);
                    $content .= myPortfolio($cID);
                }
    */


    function search_feed()
    {
        $db = $this->get_db_info();

        $sector = $_POST['sector'];
        $location = $_POST['location'];
        $keyword = strtoupper($_POST['keyword']);

        $allJobs = array();
        $total = 0;

        $oracle_query = "SELECT clntinfo.company, jobs.user_id, jobs.jobs_id, jobs.title, jobs.sector, jobs.location, jobs.location2, jobs.location3, jobs.salary, jobs.salaryhigh, jobs.department,  jobs.type, jobs.jobs_custom1, jobs.jobs_custom2, to_char(jobs.updated,'DY, DD MON YYYY HH24:MI:SS') as updated, comments.com, jobs.desc_internal
                         FROM jobs, comments, clntinfo
                         WHERE jobs.status = 'Open' AND jobs.title is not null
                         AND comments.type = 'WEBSITE' AND LENGTH(comments.com) > 0
                         AND comments.jobs_id = jobs.jobs_id AND clntinfo.clnt_id = jobs.clnt_id";


        //added to limit to one row return per job
        $oracle_query .= " and comments.comment_id in (select max(comment_id)from comments where type = 'WEBSITE' group by jobs_id)";

        if (strlen($sector) > 0 && $sector !== "all") $oracle_query .= " AND jobs.sector LIKE '%".$sector."%'";
        if (strlen($location) > 0 && $location !== "all") $oracle_query .= " AND jobs.location LIKE '%".$location."%'";
        //if (strlen($keyword) > 0) $oracle_query .= "AND (UPPER(jobs.sector) LIKE '%".$keyword."%' OR UPPER(jobs.title) LIKE '%".$keyword."%' OR CONTAINS(comments.com,'".$keyword."',1) > 0)";
        if (strlen($keyword) > 0) $oracle_query .= "AND (UPPER(jobs.sector) LIKE '%".$keyword."%' OR UPPER(jobs.title) LIKE '%".$keyword."%' OR comments.com LIKE '%".$keyword."%' )";

        $oracle_query .= " order by jobs.updated desc";

        /*
        $oracle_query = "SELECT company, user_id, jobs_id, title, sector, location, location2, location3, salary, salaryhigh, department, type, jobs_custom1, jobs_custom2, updated, com, desc_internal FROM
                         (SELECT clntinfo.company, jobs.user_id, jobs.jobs_id, jobs.title, jobs.sector, jobs.location, jobs.location2, jobs.location3, jobs.salary, jobs.salaryhigh, jobs.department,  jobs.type, jobs.jobs_custom1, jobs.jobs_custom2, to_char(jobs.updated,'DY, DD MON YYYY HH24:MI:SS') as updated, comments.com, jobs.desc_internal
                         FROM jobs, comments, clntinfo
                         WHERE jobs.status = 'Open' AND jobs.title is not null
                         AND comments.type = 'WEBSITE' AND LENGTH(comments.com) > 0
                         AND comments.jobs_id = jobs.jobs_id AND clntinfo.clnt_id = jobs.clnt_id";


        //added to limit to one row return per job
        $oracle_query .= " and comments.comment_id in (select max(comment_id)from comments where type = 'WEBSITE' group by jobs_id)";

        if (strlen($sector) > 0 && $sector !== "all") $oracle_query .= " AND jobs.sector LIKE '%".$sector."%'";
        if (strlen($location) > 0 && $location !== "all") $oracle_query .= " AND jobs.location LIKE '%".$location."%'";
        //if (strlen($keyword) > 0) $oracle_query .= "AND (UPPER(jobs.sector) LIKE '%".$keyword."%' OR UPPER(jobs.title) LIKE '%".$keyword."%' OR CONTAINS(comments.com,'".$keyword."',1) > 0)";
        if (strlen($keyword) > 0) $oracle_query .= "AND (UPPER(jobs.sector) LIKE '%".$keyword."%' OR UPPER(jobs.title) LIKE '%".$keyword."%' OR comments.com LIKE '%".$keyword."%' )";

        $oracle_query .= " order by jobs.updated desc) where ROWNUM <= 5";

       */

        $db->query($oracle_query);

        while ($db->next_record())
        {
            $jobtitle = preg_replace("/[^A-Za-z0-9\s\s+\.\:\-\/%+\*\&\$\#\!\@\(\)]/","",$db->f('title'));
            $company = $db->f('company');

            $user_id = $db->f('jobs_custom1');
            $jobs_id = $db->f('jobs_id');

            $sector = $db->f('sector');
            $updated = $db->f('updated');

            $description = strip_tags(trim($db->f('com')));
            $description = substr( $description , 0 , 208);

            $description = utf8_encode($description);

            if (strlen(trim($db->f('com'))) > 208 ) $description .= '...';


            $location = $db->f('location');
            $salary = $db->f('salary');
            $salaryhigh = $db->f('salaryhigh');

            if (strlen($salary) > 0 && $salary != 0)
            {
                if (strlen($salaryhigh) > 1) $salary = $salary.' - '.$salaryhigh;
            }elseif (strlen($salary) < 1 || $salary = 0)
            {
                if (strlen($salaryhigh) > 1)
                {
                    $salary = $salaryhigh;
                }else{
                    $salary = "Salary Not Available";
                }
            }

            $linkedInProfile = $this->dashboard_model->get_linkedin_profile($user_id);

            $allJobs[] = array(
                'jobTitle' => $jobtitle, 'company' => $company, 'sector' => $sector,
                'location' => $location,'salary' => $salary,
                'description' => $description, 'user_id' => $user_id, 'jobs_id' => $jobs_id,
                'linkedin' => $linkedInProfile, 'updated' => $updated
            );

            $total++;
        }

        return array($total, $allJobs);
    }
}