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
            $user->contacto = $this->contact->get($user->contacto_id);
            $this->response($user, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No user were found'
                ], REST_Controller::HTTP_NOT_FOUND); 
        }
    }
    
    public function getByLoginNumber_get($loginNumber)
    {
        if ($loginNumber === NULL) {
            $this->response([
                    'status' => FALSE,
                    'message' => 'Arguments missising!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        $user = $this->user->getByLoginNumber($loginNumber);
        if ($user){
            $user->contacto = $this->contact->get($user->contacto_id);
            $this->response($user, REST_Controller::HTTP_OK);
        } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No user were found'
                ], REST_Controller::HTTP_NOT_FOUND); 
        }
    }
 
    public function create_put()
    {
         $userData = [
             'user_name'    => $this->put('user_name'),
             'password'     => md5($this->put('password')),
             'estado'       => $this->put('estado'),
         ];
         $pessoa = json_decode(json_encode($this->put('pessoa')));
         
         
         //$pessoa = json_decode(json_decode(json_encode($this->put('pessoa'))));
         
         $pessoaData = [
             'nome'     => $pessoa -> nome,
             'apelido'  => $pessoa ->apelido,
         ];
         
         
         $contacto = $pessoa->contacto;
         $endereco = $pessoa->endereco;
         $bairro = $endereco->bairro;
         $posto = $endereco->postoAdministrativo;
         
         
         $enderecoData = [
             'latitude'     =>$endereco->latitude,
             'longitude'    =>$endereco->longitude,
             'bairro_id'    => ($bairro != NULL) ? $bairro->id : NULL,
             'posto_id'     => ($posto != NULL) ? $posto->id : NULL,
             'ruaAvenida'   =>$endereco->ruaAvenida,
             'zona'         =>$endereco->zona,
             'ncasa'        =>$endereco->ncasa,
         ];
         
         $contactoData = [
             'email'            =>$contacto->email,
             'mainMobileNumber' =>$contacto->mainMobileNumber,
             'auxMobileNumber'  =>$contacto->auxMobileNumber
         ];
          
          
        //$this->set_response($userData, REST_Controller::HTTP_CREATED);
         
         $this->db->trans_start();
         $endereco_id = $this->endereco->insert($enderecoData);
         $contacto_id = $this->contacto->insert($contactoData);
         
         $pessoaData["endereco_id"] = $endereco_id;
         $pessoaData["contacto_id"] = $contacto_id;
         
         $pessoa_id = $this->pessoa->insert($pessoaData);
         
         $userData["pessoa_id"]=$pessoa_id;
         
         $user_id = $this->user->insert($userData);
         $this->db->trans_complete();
         
         $user = $this->user->get($user_id);
        if ($user){
            $pessoa = $this->pessoa->get($pessoa_id);
            $pessoa->contacto = $this->contacto->get($contacto_id);
            $pessoa->endereco = $this->endereco->get($endereco_id);
            
            $user->pessoa = $pessoa;
            $this->set_response($user, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        } else {
            // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'An error ocurred'
                ], REST_Controller::HTTP_BAD_REQUEST); // NOT_FOUND (400) being the HTTP response code
        }
        
    }
    
  
 
    public function update_post()
    {
        // update an existing user and respond with a status/errors
    }
 
    public function delete_delete()
    {
        // delete a user and respond with a status/errors
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
