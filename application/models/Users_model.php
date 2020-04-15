<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

    private $table;
    public function __construct()
    {
        $this->table = 'users';
    }

    public function getUsers()
    {
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    function getUserByUsername($username)
    {
        $this->db->where('username', $username);
        $this->db->where('deleted_at', null);
        $query = $this->db->get($this->table);
        if($query->num_rows()>0) {
            return $query->result();
        }
        return false;
    }

    function getUser($id)
    {
        $this->db->where('id', $id);
        $this->db->where('deleted_at', null);
        $query = $this->db->get($this->table);
        if($query->num_rows() === 1) {
            return $query->result();
        }
        return false;
    }

    public function checkUserExists($data)
    {
        $this->db->where('deleted_at', null);
        $this->db->where('(username = "'.$data['username'].'" OR email = "'.$data['email'].'")');
        $query = $this->db->get($this->table);
        if ($query->num_rows()>0) {
            return true;
        }
        return false;
    }

    public function updateUser($user_id, $data)
    {
        $this->db->where('id', $user_id);
        $query = $this->db->update($this->table, $data);
        if ($this->db->affected_rows() === 1) { 
            return true;
        }
        return false;
    }

    public function getUserLevel($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get($this->table);
        if ($this->db->affected_rows() === 1) { 
            return $query->row('level');
        }
        return false;
    }

    public function getUserFromEmailOrUsername($username)
    {
        $this->db->select('id');
        $this->db->where('deleted_at', null);
        $this->db->where('(username = "'.$username.'" OR email = "'.$username.'")');
        $query = $this->db->get($this->table);
        if ($query->num_rows()>0) {
            return $query->row('id');
        }
        return false;
    }

    public function getEmailFromUserId($user_id)
    {
        $this->db->select('email');
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        if ($query->num_rows() === 1) {
            return $query->row('email');
        }
        return false;
    }

    public function getFirstNameFromUserId($user_id)
    {
        $this->db->select('first_name');
        $this->db->where('deleted_at', null);
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        if ($query->num_rows() === 1) {
            return $query->row('first_name');
        }
        return false;
    }

    function delete($user_id)
    {
        $data = [
            'deleted_at'    =>  date("Y-m-d H:i:s", time())
        ];
        $this->db->where('id', $user_id);
        $delete = $this->db->update('users', $data);
        if($delete) {
            return true;
        }
        return false;
    }

    function restore($user_id)
    {
        $data = [
            'deleted_at'    =>  null
        ];
        $this->db->where('id', $user_id);
        $delete = $this->db->update('users', $data);
        if($delete) {
            return true;
        }
        return false;
    }

}