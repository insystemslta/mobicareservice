<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Provincia
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/REST_Controller.php';
class Provincia extends REST_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('provinciamodel', 'provincia');
    }
    
    public function getAll_get()
    {
        $response = array();
        $provinciaList= $this->provincia->getAll();
        if ($provinciaList) {
            $this->response($provinciaList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No provincia were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function getById_get($id)
    {
        $response = array();
        $provinciaList= $this->provincia->getById($id);
        if ($provinciaList) {
            $this->response($provinciaList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No provincia were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
