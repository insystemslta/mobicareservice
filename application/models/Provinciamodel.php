<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Provinciamodel
 *
 * @author Voloide Tamele
 */
require APPPATH . 'libraries/Base_Model.php';
class Provinciamodel extends Base_Model{
    
    public function __construct() {
        parent::__construct('provincia');
    }
    const TABLE_NAME = "provincia";
}
