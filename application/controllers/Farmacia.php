<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Farmacia
 *
 * @author Voloide Tamele
 */
require_once APPPATH . 'libraries/REST_Controller.php';
class Farmacia extends REST_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('farmaciamodel', 'farmacia');
        $this->load->model('contactomodel', 'contacto');
        $this->load->model('enderecomodel', 'endereco');
        $this->load->model('responsemodel', 'errorresponse');
    }
    
    public function search_get($description)
    {
        $response = array();
        $response["farmacias"] = array();
        $response["books"] = array();
        
        $condition = array("nome" => $description);
        
        $farmaciaList = $this->farmacia->getByDescription($condition);
        if ($farmaciaList) {
            
            
            foreach ($farmaciaList as $farmacia) {
                $farmacia->contacto = $this->contacto->get($farmacia->contacto_id);
                $endereco = $this->endereco->get($farmacia->endereco_id);
                
                unset($farmacia->contacto_id);
                unset($farmacia->endereco_id);
                
                if ($endereco->posto_id != NULL) $endereco->posto = $this->posto->get($endereco->posto_id);
                if ($endereco->bairro_id != NULL) $endereco->bairro = $this->bairro->get($endereco->bairro_id);
                
                unset($endereco->posto_id);
                unset($endereco->bairro_id);
                
                $farmacia->endereco = $endereco;
                //print_r($farmacia);
                array_push($response["farmacias"], $farmacia);
            }
           // print_r($response);
                
                
            $this->response($response["farmacias"], REST_Controller::HTTP_OK);
        } else {
            
            $response = array('status' => '0',
                            'message' => 'No farmacia were found');
           
            $this->response(array($response), REST_Controller::HTTP_OK);
            
            //$errorResponse = $this->errorresponse->getAll();
             //$this->response($errorResponse, REST_Controller::HTTP_OK);
        }
    }
    
    public function getById_get($id)
    {
        $response = array();
        $farmaciaList = $this->farmacia->getById($id);
        if ($farmaciaList) {
            
            $this->response($farmaciaList, REST_Controller::HTTP_OK);
        } else {
           $response = array('status' => '0',
                            'message' => 'No farmacia were found');
           
            $this->response($response, REST_Controller::HTTP_NOT_FOUND);
            
        }
    }
    //put your code here
}
