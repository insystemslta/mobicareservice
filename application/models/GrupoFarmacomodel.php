<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GrupoFarmacomodel
 *
 * @author Voloide Tamele
 */
require_once APPPATH . 'libraries/Base_Model.php';
class GrupoFarmacomodel extends Base_Model{
    
    public function __construct() {
        parent::__construct('grupo_farmaco');
    }
    const TABLE_NAME = "grupo_farmaco";
}
