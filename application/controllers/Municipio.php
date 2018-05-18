<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Municipio
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/REST_Controller.php';
class Municipio extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('municipiomodel', 'municipio');
        //$this->load->model('provinciamodel', 'provincia');
    }
    
    public function getAll_get()
    {
        $response = array();
        
        $municipioList= $this->municipio->getAll();
        foreach ($municipioList as $municipio) {
            $provinicia = array("id" => $municipio->provincia_id);
            $municipio->provincia = $provinicia;
            array_push($response, $municipio);
            unset($municipio->provincia_id);
           
        }
        
        if ($municipioList) {
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No municipio were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
         
    }
    
    public function getById_get($id)
    {
        $response = array();
        $municipioList= $this->municipio->getById($id);
        if ($municipioList) {
            $this->response($municipioList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No municipio were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
