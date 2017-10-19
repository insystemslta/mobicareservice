<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of packagemodel
 *
 * @author Voloide
 */
class Planmodel extends CI_Model{
    
    public function insert($package) {
        $insert = $this->db->insert('package', $package);
        return $insert;
    }
    
    public function update($package, $condition){
         $update = $this->db->update('package', $package, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('package', $condition);
        return $delete;
    }
    
    public function  get($package_id){
         $sql = "SELECT *
                FROM  package
                WHERE 
                id=" . $package_id;

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
        $this->db->from('package');
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getPlanItemsRelation(){
        
        $this->db->select('*');        
        $this->db->from('package_item');
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getNotSync(){
        $this->db->select('*');        
        $this->db->from('package');
        $this->db->where("sync", 0);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getNewLoadedItems(){
         $this->db->select('*');        
        $this->db->from('package_item');
        $this->db->where("sync", 0);
        $query = $this->db->get();        
        return $query->result();
    }
}

?>
