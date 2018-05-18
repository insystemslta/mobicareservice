<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Responsemodel
 *
 * @author Voloide Tamele
 */
//require_once APPPATH . 'libraries/Base_Model.php';
class Responsemodel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    const TABLE_NAME = "response";
    
    public function  get($id){
         $sql = "SELECT *
                FROM  response
                WHERE 
                id=" . $id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
    public function getAll(){
        
        $this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $query = $this->db->get();        
        return $query->result();
    }
}
