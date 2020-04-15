<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Ramsey\Uuid\Uuid;
use SparkPost\SparkPost;

 class Password_model extends CI_Model {

    public function generateAuthCode()
    {
        $auth_code = Uuid::uuid4()->toString();
        return $auth_code;
    }

    public function insertAuthCode($user_id, $auth_code)
    {
        $data = [
            'auth_code' =>  $auth_code,
            'user_id'   =>  $user_id,
            'type'      =>  'password_reset',
        ];
        $this->db->insert('auth', $data);
        $auth_id = $this->db->insert_id();
        if ($auth_id) {
            return $auth_id;
        }
        return false;
    }

    public function validateAuthCode($auth_code)
    {
        $this->db->where('auth_code', $auth_code);
        $this->db->where('deleted_at', null);
        $this->db->where('redeemed', null);
        $query = $this->db->get('auth');
        if ($query->num_rows() === 1) {
            return true;
        }
        return false;
    }

    public function sendAuthEmail($auth_code, $email, $name)
    {
        // use sparkpost
        $httpClient = new GuzzleAdapter(new Client());
        $sparkAuth = [
            'key' => $this->config->item('sparkpost_api_key')
        ];
        $sparky = new SparkPost($httpClient, $sparkAuth);

        // Define the email body
        $promise = $sparky->transmissions->post([
            'content' => [
                    "template_id" => "sparkpost-template-id"
            ],
            "substitution_data" => [
                "name" => $name,
                "auth_code" => $auth_code
            ],
            'recipients' => [
                [
                    'address' => [
                        'email' => $email
                    ]
                ]
            ],
        ]);
    
        try {
            $response = $promise->wait();
            if($response->getStatusCode() === 200) {
                // Mark auth record as sent
                $data = ['sent' => date("Y-m-d H:i:s", time())];
                $this->updateAuthCode($auth_code, $data);
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', 'sendAuthEmail Error: '.$e->getMessage());
            echo $e->getCode()."\n";
            echo $e->getMessage()."\n";
            return false;
        }
    }


    public function updateAuthCode($auth_code, $data)
    {
        $this->db->where('auth_code', $auth_code);
        $this->db->where('deleted_at', null);
        $this->db->where('redeemed', null);
        $this->db->update('auth', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    function getUserIdFromAuthCode($auth_code) {
        // Pull user_id for the email address passed in
        $this->db->select('user_id');
        $this->db->where('auth_code', $auth_code);
        $this->db->where('deleted_at', null);
        $query = $this->db->get('auth');

        if($query->num_rows() === 1) {
            return $query->row('user_id');
        }
        return false;
    }


    function getUsersIdFromEmail($email) {
         // Pull user_id for the email address passed in
         $this->db->select('user_id');
         $this->db->where('email', $email);
         $this->db->where('status_id', 1);
         $getUsersIdFromEmailQuery = $this->db->get('users');

         if($getUsersIdFromEmailQuery) {
             foreach($getUsersIdFromEmailQuery->result() as $row) {
                 return $row->user_id;
             }
         } else {
             return false;
         }
     }


     function getUsernameFromEmail($email) {
         // Pull user_id for the email address passed in
         $this->db->select('username');
         $this->db->where('email', $email);
         $this->db->where('status_id', 1);
         $getUsersIdFromEmailQuery = $this->db->get('users');

         if($getUsersIdFromEmailQuery) {
             foreach($getUsersIdFromEmailQuery->result() as $row) {
                 return $row->username;
             }
         } else {
             return false;
         }
     }

     public function getHashFromUsername($username) {
         // Pull the stored password hash for the username passed in
         $this->db->select('password');
         $this->db->where('username', $username);
         $getHashFromUsernameQuery = $this->db->get('users');

         if($getHashFromUsernameQuery) {
             foreach($getHashFromUsernameQuery->result() as $row) {
                 return $row->password;
             }
         } else {
             return false;
         }
     }


     public function encryptPassword($password) {
         // Returns the bcrypt hash of the password passed in
         $this->load->library('bcrypt');
         return $this->bcrypt->hash_password($password);
     }


     public function validatePassword($password, $username) {

         // Load the bcrypt library
         $this->load->library('bcrypt');

         // Pull the stored password hash for the username passed in
         $stored_hash = $this->password_model->getHashFromUsername($username);

         // Compare the stored hash to the one generated for the password passed in
         if ($this->bcrypt->check_password($password, $stored_hash))
         {
             return true;
         } else {
             return false;
         }
     }


     public function uniqueAuthToken($auth_token) {
         // Check the auth_token passed in to see if it already exists in the auth table
         $this->db->where('auth_token', $auth_token);
         $uniqueAuthTokenQuery = $this->db->get('auth');

         if($uniqueAuthTokenQuery->num_rows()>0) {
             return false;
         } else {
             return true;
         }
     }

     public function updatePasswordReset($data) {
         // Set date variable
//         date_default_timezone_set('CST6PDT');
         $curdate = date("Y-m-d H:i:s", time());

         // Encrypt the password
         $this->load->model('password_model');
         $password = $this->password_model->encryptPassword($this->input->post('password_reset_new'));

         $formData = array(
             'date_modified' => $curdate,
             'password'     =>  $password,
         );

         $this->db->where('username', $this->input->post('username'));
         $updatePasswordReset = $this->db->update('users', $formData);

         if($updatePasswordReset) {
             return true;
         } else {
             return false;
         }
     }

     public function updatePasswordChange($data) {
         // Set date variable
//         date_default_timezone_set('CST6PDT');
         $curdate = date("Y-m-d H:i:s", time());

         // Encrypt the password
         $this->load->model('password_model');
         $password = $this->password_model->encryptPassword($this->input->post('password_change_password'));

         $formData = array(
             'date_modified' => $curdate,
             'password'     =>  $password,
         );

         // If the force_password_reset flag was set, turn it off
         if($this->session->userdata('force_password_reset')=='1') {
             $this->session->set_userdata('force_password_reset','0');
             $formData['force_password_reset'] = 0;
         }
         $this->db->where('id', $this->session->userdata('user_id'));
         $updatePasswordChange = $this->db->update('users', $formData);

         if($updatePasswordChange) {
             return true;
         } else {
             return false;
         }
     }

}
