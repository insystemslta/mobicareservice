<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Farmaco
 *
 * @author Voloide Tamele
 */
require_once APPPATH . 'libraries/REST_Controller.php';
class Farmaco extends REST_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('farmaciamodel', 'farmacia');
        $this->load->model('contactomodel', 'contacto');
        $this->load->model('enderecomodel', 'endereco');
        $this->load->model('farmacomodel', 'farmaco');
        $this->load->model('grupofarmacomodel', 'grupoFarmaco');
    }
    
    public function search_get($description)
    {
        $response = array();
        $response["farmacos"] = array();
        
        $condition = array("designacao" => $description);
        
        $farmacoList = $this->farmaco->getByDescription($condition);
        if ($farmacoList) {
            
            
            foreach ($farmacoList as $farmaco) {
                $farmaco->grupo_farmaco = $this->grupoFarmaco->get($farmaco->grupofarmaco_id);
                $farmaco->grupo_farmaco = $this->grupoFarmaco->get($farmaco->grupofarmaco_id);
                
                $endereco = $this->endereco->get($farmaco->endereco_id);
                
                unset($farmaco->contacto_id);
                unset($farmaco->endereco_id);
                
                if ($endereco->posto_id != NULL) $endereco->posto = $this->posto->get($endereco->posto_id);
                if ($endereco->bairro_id != NULL) $endereco->bairro = $this->bairro->get($endereco->bairro_id);
                
                unset($endereco->posto_id);
                unset($endereco->bairro_id);
                
                $farmaco->endereco = $endereco;
                //print_r($farmacia);
                array_push($response["farmacos"], $farmaco);
            }
           // print_r($response);
                
                
            $this->response($response["farmacos"], REST_Controller::HTTP_OK);
        } else {
            
            $response = array('status' => '0',
                            'message' => 'No farmaco were found');
           
            $this->response(array($response), REST_Controller::HTTP_OK);
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
}
