<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of packageitem
 *
 * @author Voloide
 */
require APPPATH . 'libraries/API.php';
require APPPATH . 'libraries/synchronizeble.php';

class packageitem extends API implements synchronizeble {

    public function __construct() {
        parent::__construct();
        $this->load->model('packageitemmodel', 'packageitem');
    }

    public function create() {

        // array for JSON response
        $response = array();

        if (isset($_POST['package_id']) && isset($_POST['item_id'])) {
            $data = array(
                'package_id' => $this->input->post('package_id'),
                'item_id' => $this->input->post('item_id')
            );

            $insert = $this->packageitem->insert($data);

            if ($insert) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Item associado ao plano com sucesso.";

                // echoing JSON response
                API::httpResponse($response);
            } else {
                // failed to insert row
                $response["success"] = 0;
                $response["message"] = "Oops! ocorreu um erro ao sincronizar.";

                // echoing JSON response
                API::httpResponse($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Campos obrigatorios em falta";

            // echoing JSON response
            API::httpResponse($response);
        }
    }

    public function update() {
        // array for JSON response
        $response = array();

        if (isset($_POST['id']) && isset($_POST['package_id']) && isset($_POST['item_id'])) {
            $data = array(
                'package_id' => $this->input->post('package_id'),
                'item_id' => $this->input->post('item_id')
            );

            $condition = array(
                'id' => $this->input->post('id')
            );

            $update = $this->packageitem->update($data, $condition);

            if ($update) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Item associado ao pacote com sucesso.";

                // echoing JSON response
                API::httpResponse($response);
            } else {
                // failed to insert row
                $response["success"] = 0;
                $response["message"] = "Oops! ocorreu um erro ao sincronizar.";

                // echoing JSON response
                API::httpResponse($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Campos obrigatorios em falta";

            // echoing JSON response
            API::httpResponse($response);
        }
    }

    public function delete() {
        
    }

    public function get($package_id, $apiKey) {
        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }
        
        if (isset($command_id) && $command_id != NULL) {
            $command = $this->command->get($command_id);

            if ($command) {
                $cmd = array();
                $cmd["id"] = $command->id;
                $cmd["name"] = $command->name;
                $cmd["command"] = $command->command;
                $cmd["code"] = $command->code;
                $cmd["item_code"] = $command->item_code;
                $response["command"] = array();
                $response["command"] = $cmd;

                // success
                $response["success"] = 1;
                API::httpResponse($response);
            } else {
                API::httpResponse($response);
            }
        } else {
            API::httpResponse($response);
        }
    }

    public function getAll($apiKey = NULL) {

        // array for JSON response
        $response = array();
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }
        
        $commands = $this->command->getAll();

        if ($commands) {
            $response["commands"] = array();
            foreach ($commands as $c) {

                $cmd = array();
                $cmd["id"] = $c->id;
                $cmd["name"] = $c->name;
                $cmd["command"] = $c->command;
                $cmd["code"] = $c->code;
                $cmd["item_code"] = $c->item_code;
                array_push($response["commands"], $c);
            }

            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados comandos";

            // echo no users JSON
            API::httpResponse($response);
        }
    }

    public function getByUser() {
        
    }

    public function setAsUpdated() {
        $condition = array("_id" => $this->input->post('id'));
        $this->synchronized("package_item", $condition);
    }

}

?>
