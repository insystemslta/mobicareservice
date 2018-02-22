<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contactomodel
 *
 * @author Voloide Tamele
 */
class Contactomodel extends CI_Model{
    
     public function __construct() {
        parent::__construct();
    }
    const TABLE_NAME = "contacto";
    
     public function insert($contact) {
        $this->db->insert(self::TABLE_NAME, $contact);
        return $this->db->insert_id();
    }
    
    public function update($contact, $condition){
         $update = $this->db->update(self::TABLE_NAME, $contact, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete(self::TABLE_NAME, $condition);
        return $delete;
    }
    
    public function  get($contact_id){
         $sql = "SELECT *
                FROM  contacto
                WHERE 
                id=" . $contact_id;

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
