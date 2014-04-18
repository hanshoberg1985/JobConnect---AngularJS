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

class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        $this->load->library('db_object');
        //$this->load->model('comments_model');
    }


    function db_connect()
    {
        $sql_query = "SELECT * FROM arithon_users WHERE db_username = 'arithon_7736'";
        $arithonUser = runquery($sql_query);

        $clientID = $arithonUser["ID"];

        $db = new db_object;
        $db->User = $arithonUser["db_username"];
        $db->Password = $arithonUser["db_password"];
        $db->Database = $arithonUser["connect_string"];

        $allSector = $this->getSectors($db);
        $allLocation = $this->getLocations($db);
        $allJobs = $this->get_feed($db);

        return array($allJobs, $allSector, $allLocation);
    }

    function getSectors($db)
    {
        $allSector = array();
        $oracle_query = "SELECT DISTINCT(jobs.sector) FROM jobs ORDER BY sector ASC";

        $db->query($oracle_query);

        while ($db->next_record())
        {
            $sector = $db->f('sector');
            $allSector[] = array('sector' => $sector);
        }

        return $allSector;
    }

    function getLocations($db)
    {
        $allLocation = array();
        $oracle_query = "SELECT DISTINCT(jobs.location) FROM jobs ORDER BY location ASC";

        $db->query($oracle_query);

        while ($db->next_record())
        {
            $location = $db->f('location');
            $allLocation[] = array('location' => $location);
        }

        return $allLocation;
    }

    function get_feed($db)
    {
        $allJobs = array();
        $total = 0;

        $oracle_query = "SELECT clntinfo.company, jobs.user_id, jobs.jobs_id, jobs.title, jobs.status, jobs.location, jobs.location2, jobs.location3, jobs.salary, jobs.salaryhigh, jobs.department, jobs.sector, jobs.type, jobs.jobs_custom1, jobs.jobs_custom2, to_char(jobs.updated,'DD Mon YYYY') as updated, comments.com, jobs.desc_internal,
                         cont.forename, cont.email, jobs.jobs_custom1
                         FROM jobs, comments, clntinfo, cont
                         WHERE jobs.status = 'Open' AND jobs.title is not null AND comments.type = 'WEBSITE' AND LENGTH(comments.com) > 0
                         AND comments.jobs_id = jobs.jobs_id
                         AND clntinfo.clnt_id = jobs.clnt_id
                         AND jobs.jobs_custom1 = cont.email";
                         /* Added to retrieve the jobs added or updated today, would come in useful when retrieving jobs from all the different databases,
                         AND (jobs.entered LIKE SYSDATE OR jobs.updated LIKE SYSDATE)";*/

        //added to limit to one row return per job
        $oracle_query .= " and comments.comment_id in (select max(comment_id)from comments where type = 'WEBSITE' group by jobs_id)";

        $oracle_query .= " order by jobs.updated desc";


        $db->query($oracle_query);

        while ($db->next_record())
        {
            $jobtitle = preg_replace("/[^A-Za-z0-9\s\s+\.\:\-\/%+\*\&\$\#\!\@\(\)]/","",$db->f('title'));
            $user_id = $db->f('jobs_custom1');
            $jobs_id = $db->f('jobs_id');
            $company = $db->f('company');
            $sector = $db->f('sector');
            $updated = $db->f('updated');
            $realname = $db->f('forename');
            $email = $db->f('email');

            $type = $db->f('type');
            if (strlen($type) < 1) $type = "Unknown";


            $description = strip_tags(trim($db->f('com')));
            $description = substr( $description , 0 , 308);
            if (strlen(trim($db->f('com'))) > 308 ) $description .= '...';

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

            $linkedInProfile = $this->get_linkedin_profile($user_id);

            $allJobs[] = array(
                'jobTitle' => $jobtitle, 'company' => $company, 'sector' => $sector,
                'location' => $location,'salary' => $salary,
                'description' => $description, 'user_id' => $user_id, 'jobs_id' => $jobs_id,
                'linkedin' => $linkedInProfile, 'updated' => $updated,
                'realname' => $realname, 'email' => $email, 'type' => $type
            );

            $total++;
        }

        return array($total, $allJobs);
    }

    function get_linkedin_profile($user_id)
    {
        $sql_query = "SELECT * FROM arithon_users WHERE username = '".$user_id."'";
        $arithonUser = runquery($sql_query);

        $clientID = $arithonUser["ID"];

        return $arithonUser["LinkedInProfile"];

    }
}