<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_model extends CI_Model {

    public function __construct()
    {
        // load stuff
    }

    function getConfigSetting($setting)
    {
        $this->db->select('value');
        $this->db->where('setting', $setting);
        $this->db->where('deleted_at', null);
        $query = $this->db->get('config');
        if($query->num_rows() > 0) {
            return $query->row('value');
        } else {
            return false;
        }
    }

}