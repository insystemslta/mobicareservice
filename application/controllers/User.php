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
        $this->load->model('contactmodel', 'contact');
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
 
    public function user_put()
    {
         
    }
 
    public function user_post()
    {
        // update an existing user and respond with a status/errors
    }
 
    public function user_delete()
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
