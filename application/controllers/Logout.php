<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {
	
    public function index()
    {
        // Check for logged in user
        if($this->session->userdata('logged_in') != 1) {
            redirect('login');
        }

        //Destory session variables
        $this->session->sess_destroy();

        //Destroy cookies if set
        setcookie('mpapp_user', '');
        setcookie('mpapp_pass', '');
        setcookie('mpapp_user', '', time()-42000, '/', '.nerdthomas.com', 0);
        setcookie('mpapp_pass', '', time()-42000, '/', '.nerdthomas.com', 0);

        //Set system message and send back to home page
        redirect('home');
    }	

}
