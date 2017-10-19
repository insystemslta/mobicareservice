<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of synchronizemodel
 *
 * @author Voloide
 */
class Synchronizemodel extends CI_Model{
    
    public function insert($data) {
        $insert = $this->db->insert('sync', $data);
        return $insert;
    }
    
    public function setAsSync($user) {
        $insert = $this->db->insert('user_sync', $user);
        return $insert;
    }
    
    public function update($data, $condition){
         $update = $this->db->update('sync', $data, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('sync', $condition);
        return $delete;
    }
    
    public function  get($sync_id){
         $sql = "SELECT *
                FROM  sync
                WHERE 
                id=" . $sync_id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
    public function  countVehicleUserSyncDone($sync_id){
         $sql = "SELECT COUNT(*) AS syncs
                FROM  user_sync
                WHERE 
                sync_id=" . $sync_id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
    
    
    public function getAll($condition){
        
        $this->db->select('*');        
        $this->db->from('sync');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getSyncHeaders($record_id, $user_id){
        $sql = "SELECT * FROM sync 
                WHERE   record_id = {$record_id} AND sync_status = 0
                    AND NOT EXISTS (SELECT *
                                    FROM user_sync
                                    WHERE sync_id = sync.ID 
                                          AND user_id = {$user_id})";
         $query = $this->db->query($sql);
         return $query->result();
    }
    
    public function setAsUpdated($table, $condition){
        $data = array("sync"=>1, "sync_type"=>0);
        $update = $this->db->update($table, $data, $condition);
        return $update;
    }
   
}

?>
