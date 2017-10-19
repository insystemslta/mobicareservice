<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of owner
 *
 * @author Voloide
 */
require APPPATH . 'libraries/API.php';
require APPPATH . 'libraries/synchronizeble.php';

class owner extends API implements synchronizeble {

    public function __construct() {
        parent::__construct();
        $this->load->model('OwnerModel', 'owner');
        $this->load->model('contactmodel', 'contact');
    }

    public function create() {

        // array for JSON response
        $response = array();

        if (isset($_POST['_id']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['contact']) && isset($_POST['email']) && isset($_POST['type'])) {
            $ownerData = array(
                '_id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'surname' => $this->input->post('surname'),
                'type' => $this->input->post('type')
            );

            $insert = $this->owner->insert($ownerData);

            if ($insert) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Owner successfully created.";

                // echoing JSON response
                API::httpResponse($response);
            } else {
                // failed to insert row
                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";

                // echoing JSON response
                API::httpResponse($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Required field(s) missing";

            // echoing JSON response
            API::httpResponse($response);
        }
    }

    public function update() {
        // array for JSON response
        $response = array();

        if (isset($_POST['_id']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['contact']) && isset($_POST['email']) && isset($_POST['type'])) {
            $ownerData = array(
                '_id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'surname' => $this->input->post('surname'),
                'type' => $this->input->post('type')
            );

            $insert = $this->owner->insert($ownerData);

            if ($insert) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Owner successfully created.";

                // echoing JSON response
                API::httpResponse($response);
            } else {
                // failed to insert row
                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";

                // echoing JSON response
                API::httpResponse($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Required field(s) missing";

            // echoing JSON response
            API::httpResponse($response);
        }
    }

    public function delete() {
        
    }

    public function get($id = 0, $apiKey = NULL) {
        // array for JSON response
        $response = array();

        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }
        if (isset($id) && $id != 0) {
            $owner = $this->owner->get($id);
            if ($owner) {
                $response["owner"] = array();

                $ownerArray = array();
                $ownerArray["id"] = $owner->_id;
                $ownerArray["name"] = $owner->name;
                $ownerArray["surname"] = $owner->surname;
                $ownerArray["type"] = $owner->type;
                $ownerArray["sync"] = $owner->sync;
                $ownerArray["sync_type"] = $owner->sync_type;
                $ownerArray["contacto"] = NULL;

                if ($owner->contacto_id > 0) {
                    $ownerArray["contacto"] = $this->contact->get($owner->contacto_id);
                }

                $response["owner"] = $ownerArray;

                // success
                $response["success"] = 1;
                API::httpResponse($response);
            } else {
                $response["success"] = 0;
                API::httpResponse($response);
            }
        } else {
            $response["success"] = 0;
            API::httpResponse($response);
        }
    }

    public function getbyUser($user_id = 0, $apiKey = NULL) {
        // array for JSON response
        $response = array();

        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if (isset($user_id) && $user_id != 0) {

            $owners = $this->owner->getByUser($user_id);

            if ($owners) {
                $response["owner"] = array();

                foreach ($owners as $owner) {
                    // user node

                    $ownerArray = array();
                    $ownerArray["id"] = $owner->_id;
                    $ownerArray["name"] = $owner->name;
                    $ownerArray["surname"] = $owner->surname;
                    $ownerArray["type"] = $owner->type;
                    $ownerArray["sync"] = $owner->sync;
                    $ownerArray["sync_type"] = $owner->sync_type;
                    $ownerArray["contacto"] = NULL;

                    if ($owner->contacto_id > 0) {
                        $ownerArray["contacto"] = $this->contact->get($owner->contacto_id);
                    }
                    array_push($response["owner"], $ownerArray);
                }


                // success
                $response["success"] = 1;
                API::httpResponse($response);
            } else {
                $response["success"] = 0;
                API::httpResponse($response);
            }
        } else {
            $response["success"] = 0;
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

        $owners = $this->owner->getAll();
        if ($owners) {
            $response["owners"] = array();
            foreach ($owners as $owner) {
                $ownerArray = array();
                $ownerArray["id"] = $owner->_id;
                $ownerArray["name"] = $owner->name;
                $ownerArray["surname"] = $owner->surname;
                $ownerArray["type"] = $owner->type;
                $ownerArray["sync"] = $owner->sync;
                $ownerArray["sync_type"] = $owner->sync_type;
                $ownerArray["contacto"] = NULL;

                if ($owner->contacto_id > 0) {
                    $ownerArray["contacto"] = $this->contact->get($owner->contacto_id);
                }
                array_push($response["owners"], $ownerArray);
            }

            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        }
    }

    public function setAsUpdated() {
        $condition = array("_id" => $this->input->post('id'));
        $this->synchronized(__CLASS__, $condition);
    }

}

?>
