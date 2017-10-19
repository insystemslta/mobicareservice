<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of commandmodel
 *
 * @author Voloide
 */
class Commandmodel extends CI_Model{
    
    public function insert($command) {
        $insert = $this->db->insert('command', $command);
        return $insert;
    }
    
    public function update($command, $condition){
         $update = $this->db->update('command', $command, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('command', $condition);
        return $delete;
    }
    
    public function  get($command_id){
         $sql = "SELECT *
                FROM  command
                WHERE 
                id=" . $command_id;

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
        $this->db->from('command');
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getAllParts(){
        
        $this->db->select('*');        
        $this->db->from('command_parts');
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getAllCommandResponse(){
        $this->db->select('*');        
        $this->db->from('commandresponse');
        $query = $this->db->get();        
        return $query->result();
    }
    
     public function getToSyncOnDevice($condition){
        
        $this->db->select('*');        
        $this->db->from('command');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function getCommandParts($condition){
        
        $this->db->select('*');        
        $this->db->from('command_parts');
        $this->db->where($condition);
        $query = $this->db->get();        
        return $query->result();
    }
}

?>
