<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();

        // Load the models
		$this->load->model('users_model');
	}

	function index()
    {
        //If user is already logged in, send them back to the home page
        if($this->session->userdata('logged_in')==1) {
            redirect('home');
        }

        //Check to make sure POST data is present, otherwise someone just typed the URL. Send them back to the home page
        if(isset($_POST['login_submit'])) {
            // Check validation
            $this->load->library('form_validation');
            $this->form_validation->set_rules('login_username', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('login_password', 'Password', 'trim|required');

            if($this->form_validation->run() === TRUE) {
                //Load the model
                $this->load->model('login_model');

                //Check to see if that email address has already been used
                $data_login = array(
                    'username' => $this->input->post('login_username'),
                    'password' => $this->input->post('login_password'),
                );

                if($this->login_model->verifyUser($data_login)==1) {

					$user_info = $this->users_model->getUserByUsername($data_login['username']);
                    foreach ($user_info as $row) {
						//Set session variables
						$this->session->set_userdata('username', $row->username);
						$this->session->set_userdata('email', $row->email);
						$this->session->set_userdata('password', $row->password);
                        $this->session->set_userdata('user_id', $row->id);
                        $this->session->set_userdata('view_user_id', $row->id);
						$this->session->set_userdata('first_name', $row->first_name);
                        $this->session->set_userdata('full_name', $row->first_name." ".$row->last_name);
                        $this->session->set_userdata('level', $row->level);
						$this->session->set_userdata('logged_in', 1);
                        $this->session->set_userdata('force_password_reset', $row->force_password_reset);

						// Set timezone
						$timezone = empty($row->timezone) ? $this->config->item('default_timezone') : $row->timezone;
						$this->session->set_userdata('timezone', $timezone);

						setcookie($this->config->item('site_name')."_user", $row->username, time()+60*60*24*100, "/", '.nerdthomas.com');
						setcookie($this->config->item('site_name')."_pass", $row->password, time()+60*60*24*100, "/", '.nerdthomas.com');

						// Update last_login field in user table
                        $this->users_model->updateUser($row->id, ['last_login' => date("Y-m-d H:i:s", time())]);

						$redirect = $this->input->get('redirect');
						if($redirect) {
							redirect($redirect);
						} else {
							redirect('home');
						}
					}
				} else {
                    //Email or Password is incorrect
                    $this->session->set_flashdata('login_status', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><h5>There was a problem logging you in. Check your username/password.</h5><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

                    // Display the login form
                    $data['page_title']=$this->config->item('site_name').' [ login ]';

                    $this->load->view('header', $data);
                    $this->load->view('login');
                    $this->load->view('footer');
                }
            } else {
                // Display the login form
                $data['page_title']=$this->config->item('site_name').' [ login ]';

                $this->load->view('header', $data);
                $this->load->view('login');
                $this->load->view('footer');
            }


        } else {
            // Display the login form
            $data['page_title']=$this->config->item('site_name').' [ login ]';

            $this->load->view('header', $data);
            $this->load->view('login');
            $this->load->view('footer');
        }
	}

}
