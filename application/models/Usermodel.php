<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usermodel
 *
 * @author Voloide
 */
class Usermodel extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    const TABLE_NAME = "mbc_user";


    public function insert($user) {
        $this->db->insert(self::TABLE_NAME, $user);
        return $this->db->insert_id();
    }
    
    public function update($user, $condition){
         $update = $this->db->update(self::TABLE_NAME, $user, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete(self::TABLE_NAME, $condition);
        return $delete;
    }
    
    public function  getByCredentials($user_name, $password){
         $sql = "SELECT *
                FROM  mbc_user
                WHERE 
                user_name= '$user_name' AND password='$password'";

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
    public function  checkUserName($user_name){
         $sql = "SELECT *
                FROM  mbc_user
                WHERE 
                user_name= '$user_name'";

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
    public function  get($user_id){
         $sql = "SELECT *
                FROM  mbc_user
                WHERE 
                id=" . $user_id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
  
    
    public function  login($condition){         
        $this->db->select('*');        
        $this->db->from('mbc_user');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
    
    
    public function getAll(){
        
        $this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function  getLastId(){
         $sql = "SELECT MAX(id) AS lastId
                FROM  mbc_user";

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
}

?>
