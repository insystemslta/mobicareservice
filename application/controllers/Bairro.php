<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bairro
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/REST_Controller.php';
class Bairro extends REST_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('bairromodel', 'bairro');
    }
    
    public function getAll_get()
    {
        $response = array();
        $bairroList= $this->bairro->getAll();
        
        foreach ($bairroList as $bairro) {
            $municipio = array("id" => $bairro->municipio_id);
            $bairro->municipio = $municipio;
            array_push($response, $bairro);
            unset($bairro->municipio_id);
           
        }
        if ($bairroList) {
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No bairro were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function getById_get($id)
    {
        $response = array();
        $bairroList= $this->bairro->getById($id);
        if ($bairroList) {
            $this->response($bairroList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No bairro were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
