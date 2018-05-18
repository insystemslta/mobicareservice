<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author Voloide
 */

require APPPATH . 'libraries/REST_Controller.php';

class user extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usermodel', 'user');
        $this->load->model('contactomodel', 'contacto');
        $this->load->model('pessoamodel', 'pessoa');
        $this->load->model('enderecomodel', 'endereco');
    }
    
    public function getById_get($id)
    {
        if ($id === NULL) {
            $this->response([
                    'status' => FALSE,
                    'message' => 'Arguments missising!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        $user = $this->user->get($id);
        
        if ($user){
            if ($user->pessoa_id != NULL){
                $pessoa = $this->pessoa->get($user->pessoa_id);
                $pessoa->contacto = $this->contacto->get($pessoa->contacto_id);
                $endereco = $this->endereco->get($pessoa->endereco_id);
                if ($endereco->posto_id != NULL) $endereco->posto = $this->posto->get($endereco->posto_id);
                if ($endereco->bairro_id != NULL) $endereco->bairro = $this->bairro->get($endereco->bairro_id);
                $pessoa->endereco = $endereco;
                $user->pessoa = $pessoa;
            }else{
                $farmacia = $this->farmacia->get($user->farmacia_id);
                $farmacia->contacto = $this->contacto->get($farmacia->contacto_id);
                $endereco = $this->endereco->get($farmacia->endereco_id);
                if ($endereco->posto_id != NULL) $endereco->posto = $this->posto->get($endereco->posto_id);
                if ($endereco->bairro_id != NULL) $endereco->bairro = $this->bairro->get($endereco->bairro_id);
                $farmacia->endereco = $endereco;
                $user->farmacia = $farmacia;
            }
            
            $this->response($user, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No user were found'
                ], REST_Controller::HTTP_NOT_FOUND); 
        }
         
    }
    
    public function isUserNameAvailable_get($userName)
    {
        if ($userName === NULL) {
            $this->response([
                    'status' => -100,
                    'message' => 'Arguments missising!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        $user = $this->user->checkUserName($userName);
        if ($user){
             $this->response([
                    'status' => -100,
                    'message' => 'User name is not available'
                ], REST_Controller::HTTP_FOUND); 
        } else {
                $this->response([
                    'status' => 100,
                    'message' => 'User name is available'
                ], REST_Controller::HTTP_CONTINUE); 
        }
    }
 
    public function create_put()
    {
         $userData = [
             'user_name'    => $this->put('user_name'),
             'password'     => md5($this->put('password')),
             'estado'       => $this->put('estado'),
         ];
         
         $getByCredentialsCondition = array("user_name" => $userData["user_name"], "password" => $userData["password"]);
         $userOnDb = $this->user->getByCredentials($getByCredentialsCondition);
         if ($userOnDb){
              $this->response([
                       'status' => 100,
                       'message' => 'user created'
                   ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
         }else {
            $pessoa = json_decode(json_encode($this->put('pessoa')));


            //$pessoa = json_decode(json_decode(json_encode($this->put('pessoa'))));

            $pessoaData = [
                'nome'     => $pessoa -> nome,
                'apelido'  => $pessoa ->apelido,
            ];



            $contacto = json_decode(json_encode($pessoa->contacto));
            $endereco = json_decode(json_encode($pessoa->endereco));
            if (isset($endereco->bairro)) $bairro = json_decode(json_encode($endereco->bairro));
            if (isset($endereco->postoAdministrativo)) $posto = json_decode(json_encode($endereco->postoAdministrativo));


            $enderecoData = [
                'latitude'     =>$endereco->latitude,
                'longitude'    =>$endereco->longitude,
                'bairro_id'    => (isset($bairro) && $bairro != NULL) ? $bairro->id : NULL,
                'posto_id'     => (isset($posto) && $posto != NULL) ? $posto->id : NULL,
                'ruaAvenida'   =>$endereco->ruaAvenida,
                'zona'         =>$endereco->zona,
                'ncasa'        =>$endereco->ncasa,
            ];

            $contactoData = [
                'email'            =>$contacto->email,
                'mainMobileNumber' =>$contacto->mainMobileNumber,
                'auxMobileNumber'  =>$contacto->auxMobileNumber
            ];


            $this->db->trans_start();
            $endereco_id = $this->endereco->insert($enderecoData);
            $contacto_id = $this->contacto->insert($contactoData);

            $pessoaData["endereco_id"] = $endereco_id;
            $pessoaData["contacto_id"] = $contacto_id;

            $pessoa_id = $this->pessoa->insert($pessoaData);

            $userData["pessoa_id"]=$pessoa_id;

            $user_id = $this->user->insert($userData);
            $this->db->trans_complete();


           if ($user_id){
                $this->response([
                       'status' => 100,
                       'message' => 'user created'
                   ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
           } else {
               // Set the response and exit
                   $this->response([
                       'status' => -100,
                       'message' => 'An error ocurred'
                   ], REST_Controller::HTTP_BAD_REQUEST); // NOT_FOUND (400) being the HTTP response code
           }
        }
    }
    
    
     public function updateLogInStatus_post()
    {
        $condiction = ['id' => $this->post('id')];
        $stausData = ['estado'       => $this->post('estado')];
        $status = $this->user->update($stausData, $condiction);
        
        if ($status){
            $this->response([
                    'status' => 100,
                    'message' => 'user authenticated'
                ], REST_Controller::HTTP_OK); 
        } else {
            $this->response([
                    'status' => FALSE,
                    'message' => 'An error ocurred'
                ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }
    
     public function logoff_post()
    {
        // update an existing user and respond with a status/errors
    }
  
 
    public function update_post()
    {
        // update an existing user and respond with a status/errors
    }
 
    public function delete_delete()
    {
        // delete a user and respond with a status/errors
    }
    
    public function getByCredentials_get($user_name, $password)
    {
        
        
         if ($user_name === NULL || $password === NULL) {
            $this->response([
                    'status' => FALSE,
                    'message' => 'Arguments missising!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        $user = $this->user->getByCredentials($user_name, $password);
        
        if ($user){
            if ($user->pessoa_id != NULL){
                $pessoa = $this->pessoa->get($user->pessoa_id);
                
                unset($user->pessoa_id);
                unset($user->farmacia_id);
                
                $pessoa->contacto = $this->contacto->get($pessoa->contacto_id);
                $endereco = $this->endereco->get($pessoa->endereco_id);
                
                if ($endereco->posto_id != NULL) {
                    $postoadministrativo = array("id" => $endereco->posto_id);
                    $endereco->postoadministrativo = $postoadministrativo;
                }
                if ($endereco->bairro_id != NULL) {
                    $bairro = array("id" => $endereco->bairro_id);
                    $endereco->bairro = $bairro;
                }
                unset($endereco->bairro_id);
                unset($endereco->posto_id);
                
                unset($pessoa->contacto_id);
                unset($pessoa->endereco_id);
                
                $pessoa->endereco = $endereco;
                $user->pessoa = $pessoa;
            }else{
                $farmacia = $this->farmacia->get($user->farmacia_id);
                $farmacia->contacto = $this->contacto->get($farmacia->contacto_id);
                $endereco = $this->endereco->get($farmacia->endereco_id);
                if ($endereco->posto_id != NULL) $endereco->posto = $this->posto->get($endereco->posto_id);
                if ($endereco->bairro_id != NULL) $endereco->bairro = $this->bairro->get($endereco->bairro_id);
                $farmacia->endereco = $endereco;
                $user->farmacia = $farmacia;
            }
            
            $this->response($user, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No user were found'
                ], REST_Controller::HTTP_NOT_FOUND); 
        }
    }
    
    public function getAll_get()
    {
        $response = array();
        $users = $this->user->getAll();
        if ($users) {
            $response["users"] = array();
            foreach ($users as $user) {
                $user->contact = $this->contact->get($user->contacto_id);
                array_push($response["users"], $user);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
