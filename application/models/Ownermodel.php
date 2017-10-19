<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ownermodel
 *
 * @author Voloide
 */
class Ownermodel extends CI_Model{
    
    public function insert($owner) {
        $insert = $this->db->insert('owner', $owner);
        return $insert;
    }
    
    public function update($owner, $condition){
         $update = $this->db->update('owner', $owner, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('owner', $condition);
        return $delete;
    }
    
    public function  get($owner_id){
         $sql = "SELECT *
                FROM  owner
                WHERE 
                _id=" . $owner_id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
    public function  getByUser($user_id){
         $sql = "SELECT owner.*
                FROM  owner 
                WHERE  _id IN (SELECT owner_id 
                                         FROM vehicle WHERE _id IN (SELECT vehicle_id 
                                                                    FROM user_vehicle WHERE user_id ={$user_id}))";

        $query = $this->db->query($sql);
         return $query->result();
    }
    
    public function getAll(){
        
        $this->db->select('*');        
        $this->db->from('owner');
        $query = $this->db->get();        
        return $query->result();
    }
}

?>
