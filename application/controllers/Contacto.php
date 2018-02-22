<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contacto
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/REST_Controller.php';
class Contacto extends REST_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function create_put(){
       
         $contactoData = [
             'id'                   => $this->put('id'),
             'email'             => $this->put('email'),
             'mainMobileNumber'  => $this->put('mainMobileNumber'),
             'auxMobileNumber'   => $this->put('auxMobileNumber'),
         ];
         
         $this->set_response($contactoData, REST_Controller::HTTP_CREATED);
    }
}
