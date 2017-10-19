<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of command
 *
 * @author Voloide
 */
require APPPATH . 'libraries/API.php';
require APPPATH . 'libraries/synchronizeble.php';

class command extends API implements synchronizeble {

    public function __construct() {
        parent::__construct();
        $this->load->model('commandmodel', 'command');
    }

    public function create() {

        // array for JSON response
        $response = array();

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['command']) && isset($_POST['code']) && isset($_POST['item_code'])) {
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'command' => $this->input->post('command'),
                'code' => $this->input->post('code'),
                'item_code' => $this->input->post('item_code'),
                'user_type_target' => $this->input->post('user_type_target')
            );

            $insert = $this->command->insert($data);

            if ($insert) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Comando sincronizado com sucesso.";

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

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['command']) && isset($_POST['code']) && isset($_POST['item_code'])) {
            $data = array(
                'name' => $this->input->post('name'),
                'command' => $this->input->post('command'),
                'code' => $this->input->post('code'),
                'item_code' => $this->input->post('item_code'),
                'user_type_target' => $this->input->post('user_type_target')
            );

            $condition = array(
                'id' => $this->input->post('id')
            );

            $update = $this->command->update($data, $condition);

            if ($update) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "comando sincronizado com sucesso.";

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

    public function get($command_id, $apiKey) {
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
                $cmd["id"]          = $command->id;
                $cmd["name"]        = $command->name;
                $cmd["code"]        = $command->code;
                $cmd["item_code"]   = $command->item_code;
                $cmd["icon"]        = $command->icon;
                $cmd["user_type_target"] = $command->user_type_target;
                $cmd["sync"]        = $command->sync;
                $cmd["sync_type"]   = $command->sync_type;
                
                $commandParts = $this->command->getCommandParts(array("COMMAND_ID"=>$command->id));
                
                
                $commandPartsArray = array();
                foreach ($commandParts as $p) {
                    $part = array();
                    $part["id"] = $p->ID;
                    $part["part"] = $p->PART;
                    $part["command_id"] = $p->COMMAND_ID;
                    $part["position"] = $p->POSITION;
                    $part["type"] = $p->type;
                    $part["sync"] = $p->sync;
                    $part["sync_type"] = $p->sync_type;
                    array_push($commandPartsArray, $p);
                }
                $cmd["parts"] = $commandPartsArray;
                
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
            $response["message"] = 'Access Denied!';
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
                $cmd["code"] = $c->code;
                $cmd["item_code"] = $c->item_code;
                $cmd["icon"] = $c->icon;
                $cmd["user_type_target"] = $c->user_type_target;
                $cmd["sync"] = $c->sync;
                $cmd["sync_type"] = $c->sync_type;
                
                $commandParts = $this->command->getCommandParts(array("COMMAND_ID"=>$c->id));
                $commandPartsArray = array();
                foreach ($commandParts as $p) {
                    $part = array();
                    $part["id"] = $p->ID;
                    $part["part"] = $p->PART;
                    $part["command_id"] = $p->COMMAND_ID;
                    $part["position"] = $p->POSITION;
                    $part["type"] = $p->type;
                    $part["sync"] = $p->sync;
                    $part["sync_type"] = $p->sync_type;
                    array_push($commandPartsArray, $p);
                }
                $cmd["parts"] = $commandPartsArray;
                
                array_push($response["commands"], $cmd);
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

   public function getCommandPart($commandPart_id, $apiKey) {
        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Access Denied!';
            API::httpResponse($response);
            exit();
        }

        if (isset($commandPart_id) && $commandPart_id != NULL) {
            $commandPart = $this->command->getCommandParts(array("id"=>$commandPart_id));

            if ($commandPart) {
                $response["commandPart"] = array();
                foreach ($commandPart as $p) {
                    $part = array();
                    $part["id"]         = $p->ID;
                    $part["part"]       = $p->PART;
                    $part["command_id"] = $p->COMMAND_ID;
                    $part["position"]   = $p->POSITION;
                    $part["type"]       = $p->type;
                    $part["sync"]       = $p->sync;
                    $part["sync_type"]  = $p->sync_type;                
                }
                
                $response["commandPart"] = $part;

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

    public function getAllCommandParts($apiKey = NULL) {

        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Access Denied!';
            API::httpResponse($response);
            exit();
        }

        $parts = $this->command->getAllParts();

        if ($parts) {
                $response["parts"] = array();
                foreach ($parts as $p) {

                    $part = array();
                    $part["id"] = $p->ID;
                    $part["part"] = $p->PART;
                    $part["command_id"] = $p->COMMAND_ID;
                    $part["position"] = $p->POSITION;
                    $part["type"] = $p->type;
                    $part["sync"] = $p->sync;
                    $part["sync_type"] = $p->sync_type;
                    array_push($response["parts"], $part);
                }
            
            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados parts";

            // echo no users JSON
            API::httpResponse($response);
        }
    }

    public function getByUser() {
        
    }

    public function setAsUpdated() {
        $condition = array("id" => $this->input->post('id'));
        $this->synchronized(__CLASS__, $condition);
    }

}

?>
