<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 15/08/13
 * Time: 14:47
 * To change this template use File | Settings | File Templates.
 */

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        $this->load->model('dashboard_model');
        $this->load->model('job_model');
        
        $this->load->library('session');
    }

    public function index()
    {
        $result = $this->dashboard_model->db_connect();

        $jobs = $result[0];
        $data['sectors'] = $result[1];
        $data['locations'] = $result[2];

        $data['total'] = $jobs[0];
        $data['feed'] = $jobs[1];
        
        $data['userdata'] = $this->session->all_userdata();
        
        $this->load->view('dashboard', $data);
    }

    public function view_job()
    { 
        $data['jobInfo'] = $this->job_model->get_job();

        $this->load->view('templates/job_description', $data);
    }

    public function getOtherJobs()
    {
        $data['otherJobs'] = $this->job_model->get_other_jobs();

        $this->load->view('templates/other_jobs', $data);
    }

    public function applyForJob()
    {
        $data['result'] = $this->job_model->send_app();
    }

    public function searchForJob()
    {
        $result = $this->job_model->search_feed();

        $data['total'] = $result[0];
        $data['search_result'] = $result[1];
        $this->load->view('templates/search_result', $data);
    }
}


