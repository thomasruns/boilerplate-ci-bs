<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

    function verifyUser($data_login)
    {
        $username = $data_login['username'];
        $password = $data_login['password'];

        // Make sure this username has an active account
        $this->db->where('(username = "'.$username.'" OR email = "'.$username.'")', '', false);
        $this->db->where('deleted_at', null);
        $query = $this->db->get('users');

        // If 1 result, check the password
        if($query->num_rows() === 1) {
            $this->load->model('password_model');
            $validatePassword = $this->password_model->validatePassword($password, $username);
            if($validatePassword) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }


    }


}