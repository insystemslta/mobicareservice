<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of itemmodel
 *
 * @author Voloide
 */
class Itemmodel extends CI_Model{
    
    public function insert($item) {
        $insert = $this->db->insert('item', $item);
        return $insert;
    }
    
    public function update($item, $condition){
         $update = $this->db->update('item', $item, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('item', $condition);
        return $delete;
    }
    
    public function getExtra($package_id) {
        $sql = "SELECT *
                FROM item
                WHERE id NOT IN (
                                SELECT item.id
                                FROM item INNER JOIN package_item ON ITEM.id = package_item.item_id
                                WHERE package_id =5 OR package_id ={$package_id})";
        $query = $this->db->query($sql);
        return $query->result();        
        
    }
    
    public function  get($item_id){
         $sql = "SELECT *
                FROM  item
                WHERE 
                id=" . $item_id;

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
        $this->db->from('item');
        $query = $this->db->get();        
        return $query->result();
    }
    
     public function getNotSync(){
        $this->db->select('*');        
        $this->db->from('item');
        $this->db->where("sync", 0);
        $query = $this->db->get();        
        return $query->result();
    }
}

?>
