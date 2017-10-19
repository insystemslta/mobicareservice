<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sys_config
 *
 * @author Voloide
 */
class Sys_config extends CI_Model{
    
    public function getApiKey(){
        $this->db->select('*');        
        $this->db->from('sys_config');
        $this->db->where(array("config"=>"API_KEY"));
        $query = $this->db->get();        
        return $query->row();
    }
}

?>
