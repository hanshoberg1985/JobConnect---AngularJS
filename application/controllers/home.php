<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');

        $this->load->model('dashboard_model');
        $this->load->model('job_model');
        $this->load->model('login_model'); 
        $this->load->model('register_model');
        
        $this->load->library('session');       
    }     
	
	public function index()	{         
        $result = $this->dashboard_model->db_connect();

        $jobs = $result[0];
        $data['sectors'] = $result[1];
        $data['locations'] = $result[2];
        
        $data['total'] = $jobs[0];
        $data['feed'] = $jobs[1];
        
        $data['userdata'] = $this->session->all_userdata();
        
        $this->load->view('home', $data);
	}

    public function searchForJob() {
        $result = $this->job_model->search_feed();

        $data['total'] = $result[0];
        $data['search_result'] = $result[1];
        $this->load->view('templates/search_result', $data);
    }
    
    public function login() {
        $loginResult = $this->login_model->login();
         
        if($loginResult == null) {
            $this->session->set_userdata('login_state', FALSE);
            
            $result = $this->dashboard_model->db_connect();

            $jobs = $result[0];
            $data['sectors'] = $result[1];
            $data['locations'] = $result[2];
            
            $data['total'] = $jobs[0];
            $data['feed'] = $jobs[1];
            
            $data['userdata'] = $this->session->all_userdata();
            
            $this->load->view('home', $data);
        } else {            
            $this->session->set_userdata('login_state', TRUE);
            $this->session->set_userdata('user_type', $loginResult['userType']);
            $this->session->set_userdata('id', $loginResult['id']);
            $this->session->set_userdata('forename', $loginResult['forename']);
            $this->session->set_userdata('surname', $loginResult['surname']);
            $this->session->set_userdata('email', $loginResult['email']);
            $this->session->set_userdata('user_id', $loginResult['user_id']);
            
            $data['userdata'] = $this->session->all_userdata();
            
            $result = $this->dashboard_model->db_connect();

            $jobs = $result[0];
            $data['sectors'] = $result[1];
            $data['locations'] = $result[2];

            $data['total'] = $jobs[0];
            $data['feed'] = $jobs[1]; 
            
            $this->load->view('dashboard', $data);
        }
    }
    
    public function logout() {
        $this->session->unset_userdata('login_state');
        $this->session->unset_userdata('user_id');
        
        $this->load->view('home');
    }
    
    public function register() {
        $regResult = $this->register_model->register();
        
        $this->session->set_userdata('login_state', TRUE);
        $this->session->set_userdata('user_type', $regResult['userType']);
        $this->session->set_userdata('id', $regResult['id']);
        $this->session->set_userdata('forename', $regResult['forename']);
        $this->session->set_userdata('surname', $regResult['surname']);
        $this->session->set_userdata('email', $regResult['email']);
        $this->session->set_userdata('user_id', $regResult['user_id']);
        
        $result = $this->dashboard_model->db_connect();

        $jobs = $result[0];
        $data['sectors'] = $result[1];
        $data['locations'] = $result[2];
        
        $data['total'] = $jobs[0];
        $data['feed'] = $jobs[1];
        
        $data['login_state'] = TRUE;
        
        $data['userdata'] = $this->session->all_userdata();
        
        $this->load->view('home', $data);
    }
}