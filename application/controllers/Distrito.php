<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Distrito
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/REST_Controller.php';
class Distrito extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('distritomodel', 'distrito');
    }
    
    public function getAll_get()
    {
        $response = array();
        $distritoList= $this->distrito->getAll();
        
        foreach ($distritoList as $distrito) {
            $provinicia = array("id" => $distrito->provincia_id);
            $distrito->provincia = $provinicia;
            array_push($response, $distrito);
            unset($distrito->provincia_id);
           
        }
        
        if ($distritoList) {
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No distrito were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function getById_get($id)
    {
        $response = array();
        $distritoList= $this->distrito->getById($id);
        if ($distritoList) {
            $this->response($distritoList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No distrito were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
