<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authetication
 *
 * @author Voloide Tamele
 */
class AuthService{
    private $ci = null;
    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->model('usermodel', 'user');
    }
    
    public function authenticate($username, $password){
        if ($username && $password){
            $Login = $this->ci->user->login(array('user_name' => $username,'password' => md5($password)));
            if ($Login){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
}