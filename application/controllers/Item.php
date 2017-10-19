<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of item
 *
 * @author Voloide
 */
require APPPATH . 'libraries/API.php';
require APPPATH . 'libraries/synchronizeble.php';

class item extends API implements synchronizeble {

    public function __construct() {
        parent::__construct();
        $this->load->model('itemmodel', 'item');
    }

    public function create() {

        // array for JSON response
        $response = array();

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['code'])) {
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code')
            );

            $insert = $this->item->insert($data);

            if ($insert) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Item sincronizado com sucesso.";

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

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['code'])) {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code')
            );

            $condition = array(
                'id' => $this->input->post('id')
            );

            $update = $this->item->update($data, $condition);

            if ($update) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Item sincronizado com sucesso.";

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

    public function get($item_id, $apiKey) {
        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }
        
        if (isset($item_id) && $item_id != NULL) {
            $item = $this->item->get($item_id);

            if ($item) {
                $it = array();
                $it["id"] = $item->id;
                $it["code"] = $item->code;
                $it["name"] = $item->name;
                $it["sync"] = $item->sync;
                $it["sync_type"] = $item->sync_type;
                $response["item"] = array();
                $response["item"] = $it;

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

    public function getAll($apiKey) {

        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }
        
        $items = $this->item->getAll();

        if ($items) {
            $response["items"] = array();
            foreach ($items as $i) {

                $it = array();
                $it["id"] = $i->id;
                $it["code"] = $i->code;
                $it["name"] = $i->name;
                $it["sync"] = $i->sync;
                $it["sync_type"] = $i->sync_type;
                array_push($response["items"], $it);
            }

            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados items";

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
