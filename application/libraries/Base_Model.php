<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base_Model
 *
 * @author Voloide Tamele
 */
class Base_Model extends CI_Model{
    
    protected $table_name = NULL;
    
    public function __construct($TABLE_NAME) {
        parent::__construct();
        $this->table_name = $TABLE_NAME;
    }
    
    public function insert($data) {
        $this->db->insert($this->table_name, $user);
        return $this->db->insert_id();
    }
    
    public function update($data, $condition){
         $update = $this->db->update($this->table_name, $user, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete($this->table_name, $condition);
        return $delete;
    }
    
    public function getAll(){
        $this->db->select('*');        
        $this->db->from($this->table_name);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getByDescription($description){
        $this->db->select('*');        
        $this->db->from($this->table_name);
        $this->db->like($description);
        $query = $this->db->get();        
        return $query->result();
    }
    
     public function  getById($id){
         $sql = "SELECT * FROM   {$this->table_name} WHERE id=" . $id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
}
