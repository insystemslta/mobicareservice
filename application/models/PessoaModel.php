<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PessoaModel
 *
 * @author Voloide Tamele
 */
class PessoaModel extends CI_Model{
    //put your code here
     public function __construct() {
        parent::__construct();
    }
    const TABLE_NAME = "pessoa";
    
     public function insert($data) {
        $this->db->insert(self::TABLE_NAME, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $condition){
         $update = $this->db->update(self::TABLE_NAME, $data, $condition);
        return $update;
    }
    
    public function delete($condition){
        $delete = $this->db->delete(self::TABLE_NAME, $condition);
        return $delete;
    }
    
    public function  get($data_id){
         $sql = "SELECT *
                FROM  pessoa
                WHERE 
                id=" . $data_id;

        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }  else {
            return FALSE;
        }
    }
}
