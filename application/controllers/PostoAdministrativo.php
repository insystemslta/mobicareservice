<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostoAdministrativo
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/REST_Controller.php';
class PostoAdministrativo extends REST_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('postomodel', 'posto');
    }
    
    public function getAll_get()
    {
        $response = array();
        $postoList= $this->posto->getAll();
        
        foreach ($postoList as $posto) {
            $distrito = array("id" => $posto->distrito_id);
            $posto->distrito = $distrito;
            array_push($response, $posto);
            unset($posto->distrito_id);
           
        }
        
        if ($postoList) {
            $this->response($postoList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No posto were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function getById_get($id)
    {
        $response = array();
        $postoList= $this->posto->getById($id);
        if ($postoList) {
            $this->response($postoList, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No posto were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
