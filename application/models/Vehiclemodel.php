<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vehiclemodel
 *
 * @author Voloide
 */
class Vehiclemodel extends CI_Model {

    public function insert($vehicle) {
        $this->db->insert('vehicle', $vehicle);
        $insert = $this->db->insert_id();
        return $insert;
    }

    public function insertUserVehicleData($userVehicleData) {
        $insert = $this->db->insert('user_vehicle', $userVehicleData);
        return $insert;
    }

    public function contarAll() {
        $this->db->select('*');
        $this->db->from('vehicle');

        $query = $this->db->get();
        return $query->num_rows;
    }

    public function listarTodos($limit = 0, $offset = 0) {
        $sql = "SELECT 

                vehicle._id as vehicle_id,
                make as make,
                model as model,
                nr_plate as nr_plate,
                emai as imei,
                registration_date as registration_date,
                call_number as call_number,
                vehicle.type as vehicle_type,
                imageUri as imageUri,
                packegeId as packegeId,
                state as state,
                owner._id as owner_id,
                owner.name as owner_name,
                surname as owner_surname,
                package.name as package_name

                FROM  vehicle
                INNER JOIN owner ON vehicle.owner_id = owner._id
                INNER JOIN package ON vehicle.packegeId = package.id
                LIMIT $limit OFFSET " . $offset;

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function update($vehicle, $condition) {
        $update = $this->db->update('vehicle', $vehicle, $condition);
        return $update;
    }

    public function delete($condition) {
        $delete = $this->db->delete('vehicle', $condition);
        return $delete;
    }

    public function insertExtraItem($items) {
        $insert = $this->db->insert_batch('vehicle_extra_item', $items);
        return $insert;
    }

    public function listarPorId($vehicle_id) {
        $sql = "SELECT 
                vehicle._id     as vehicle_id,
                make            as make,
                model           as model,
                nr_plate        as nr_plate,
                emai            as imei,
                registration_date as registration_date,
                call_number     as call_number,
                vehicle.type    as vehicle_type,
                imageUri        as imageUri,
                packegeId       as packegeId,
                state           as state,
                owner._id       as owner_id,
                owner.name      as owner_name,
                surname         as owner_surname,
                package.name    as package_name

                FROM  vehicle
                INNER JOIN owner    ON vehicle.owner_id     = owner._id
                INNER JOIN package  ON vehicle.packegeId    = package.id
                WHERE
                vehicle._id=" . $vehicle_id;

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function get($vehicle_id) {
        $sql = "SELECT *
                FROM  vehicle
                WHERE 
                _id=" . $vehicle_id;

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            return FALSE;
        }
    }
    
    /*
    public function getByUser($condition) {

        $this->db->select('*');
        $this->db->from('vehicle');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->result();
    }
    */
    public function getByUser($user_id) {
        $sql = "SELECT vehicle.* 
                FROM vehicle INNER JOIN user_vehicle ON user_vehicle.vehicle_id = vehicle._id
                WHERE user_id = {$user_id}";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getAll($condition) {

        $this->db->select('*');
        $this->db->from('vehicle');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query->result();
    }

    public function getExtraItem($vehicle_id) {
        $sql = "SELECT * 
        FROM  vehicle_extra_item WHERE VEHICLE_ID = {$vehicle_id}";
        $query = $this->db->query($sql);
        return $query->result();
    }

}

?>
