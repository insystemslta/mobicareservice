<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContactModel
 *
 * @author Voloide
 */
class ContactModel extends CI_Model{
    public function insert($contact) {
        $insert = $this->db->insert('contacto', $contact);
        return $insert;
    }
    
    public function update($contact, $condition){
         $update = $this->db->update('contacto', $contact, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete('contacto', $condition);
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
        $this->db->from('contacto');
        $query = $this->db->get();        
        return $query->result();
    }
}

?>
