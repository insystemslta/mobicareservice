<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Servico
 *
 * @author Voloide Tamele
 */
require_once APPPATH . 'libraries/REST_Controller.php';
class Servico extends REST_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('farmaciamodel', 'farmacia');
        $this->load->model('servicomodel', 'servico');
    }
    
    public function search_get($description)
    {
        $response = array();
        $response["servicos"] = array();
        
        $condition = array("designacao" => $description);
        
        $servicoList = $this->servico->getByDescription($condition);
        if ($servicoList) {
            
            
            foreach ($servicoList as $servico) {
                
                
                array_push($response["servicos"], $servico);
            }
                
                
            $this->response($response["servicos"], REST_Controller::HTTP_OK);
        } else {
            
            $response = array('status' => '0',
                            'message' => 'No servico were found');
           
            $this->response(array($response), REST_Controller::HTTP_OK);
            
        }
    }
    
    public function getById_get($id)
    {
        $response = array();
        $servico = $this->servico->getById($id);
        if ($servico) {
            
            $this->response($servico, REST_Controller::HTTP_OK);
        } else {
           $response = array('status' => 'FALSE',
                            'message' => 'No servico were found');
           
            $this->response($response, REST_Controller::HTTP_OK);
            
        }
    }
}
