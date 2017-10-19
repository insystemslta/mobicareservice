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
    
    
    public function insert($user) {
        $this->db->insert('user', $user);
        return $this->db->insert_id();
    }
    
    public function update($user, $condition){
         $update = $this->db->update('user', $user, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('user', $condition);
        return $delete;
    }
    
    public function  getByLoginNumber($loginNumber){
         $sql = "SELECT *
                FROM  user
                WHERE 
                loginnumber='$loginNumber'";

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
                FROM  user
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
    
    public function  countVehicleUser($vehicle_id){
         $sql = "SELECT COUNT(*) AS users
                FROM  user_vehicle
                WHERE 
                vehicle_id=" . $vehicle_id;

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
        $this->db->from('user');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function  getGroup($condition){
         
        $this->db->select('*');        
        $this->db->from('user_vehicle');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function  getbyVehicle($vehicle_id){
         
        $sql = "SELECT user.*
                FROM user INNER JOIN user_vehicle ON user.id = user_vehicle.user_id
                WHERE vehicle_id =" . $vehicle_id;

        $query = $this->db->query($sql);       
        return $query->result();
    }
    
    public function  validate($condition){

        $this->db->select('*');        
        $this->db->from('user');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getAll(){
        
        $this->db->select('*');        
        $this->db->from('user');
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function  getLastId(){
         $sql = "SELECT MAX(id) AS lastId
                FROM  user";

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
