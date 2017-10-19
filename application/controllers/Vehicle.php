<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vehicle
 *
 * @author Voloide
 */
require APPPATH . 'libraries/API.php';
require APPPATH . 'libraries/synchronizeble.php';

class vehicle extends API implements synchronizeble {

    public function __construct() {
        parent::__construct();
        $this->load->model('vehiclemodel', 'vehicle');
        $this->load->model('OwnerModel', 'owner');
        $this->load->model('planmodel', 'plan');
        $this->load->model('contactmodel', 'contact');
        $this->load->helper('array');
    }

    public function getByUser($user_id = 0, $apiKey = NULL) {
        // array for JSON response
        $response = array();
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if ($user_id == 0) {
            $response["success"] = 0;
            $response["message"] = "Utilizador nao definido";
            API::httpResponse($response);
            exit();
        }

        $vehicles = $this->vehicle->getByUser($user_id);

        if ($vehicles) {
            $response["vehicles"] = array();

            foreach ($vehicles as $vehicle) {

                $vehicleArray = array();
                $vehicleArray["id"] = $vehicle->_id;
                $vehicleArray["make"] = $vehicle->make;
                $vehicleArray["model"] = $vehicle->model;
                $vehicleArray["nr_plate"] = $vehicle->nr_plate;
                $vehicleArray["emai"] = $vehicle->emai;
                $vehicleArray["registration_date"] = $vehicle->registration_date;
                $vehicleArray["owner_id"] = $vehicle->owner_id;
                $vehicleArray["call_number"] = $vehicle->call_number;
                $vehicleArray["type"] = $vehicle->type;
                $vehicleArray["imageUri"] = $vehicle->imageUri;
                $vehicleArray["packegeId"] = $vehicle->packegeId;
                $vehicleArray["state"] = $vehicle->state;
                $vehicleArray["sync"] = $vehicle->sync;
                $vehicleArray["sync_type"] = $vehicle->sync_type;
                $vehicleArray["plan"] = NULL;
                $vehicleArray["owner"] = NULL;
                $vehicleArray["extraPlanItem"] = NULL;

                $owner = $this->owner->get($vehicle->owner_id);
                if ($owner) {
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

                    $vehicleArray["owner"] = $ownerArray;
                }

                $extraItem = $this->vehicle->getExtraItem($vehicle->_id);
                if ($extraItem) {
                    $extraItemArray = array();
                    foreach ($extraItem as $vehiclesExtraItem) {
                        $vehiclesExtraItemArray = array();
                        $vehiclesExtraItemArray["id"] = $vehiclesExtraItem->ID;
                        $vehiclesExtraItemArray["vehicle_id"] = $vehiclesExtraItem->VEHICLE_ID;
                        $vehiclesExtraItemArray["item_id"] = $vehiclesExtraItem->ITEM_ID;
                        $vehiclesExtraItemArray["sync"] = $vehiclesExtraItem->sync;
                        $vehiclesExtraItemArray["sync_type"] = $vehiclesExtraItem->sync_type;
                        array_push($extraItemArray, $vehiclesExtraItemArray);
                    }
                    $vehicleArray["extraPlanItem"] = $extraItemArray;
                }

                $package = $this->plan->get($vehicle->packegeId);
                $plan = array();
                $plan["id"] = $package->id;
                $plan["name"] = $package->name;
                $plan["sync"] = $package->sync;
                $plan["sync_type"] = $package->sync_type;

                $vehicleArray["plan"] = $plan;

                array_push($response["vehicles"], $vehicleArray);
            }

            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no vehicle found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados veiculos";

            // echo no users JSON
            API::httpResponse($response);
        }
    }

    public function get($vehicle_id, $apiKey = NULL) {
        // array for JSON response
        $response = array();

        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if ($vehicle_id == 0) {
            $response["success"] = 0;
            $response["message"] = "Veiculo nao definido";
            API::httpResponse($response);
            exit();
        }


        $vehicle = $this->vehicle->get($vehicle_id);




        if ($vehicle) {
            
            $response["vehicles"] = array();
            $vehicleArray = array();
            
            $vehicleArray["id"] = $vehicle->_id;
            $vehicleArray["make"] = $vehicle->make;
            $vehicleArray["model"] = $vehicle->model;
            $vehicleArray["nr_plate"] = $vehicle->nr_plate;
            $vehicleArray["emai"] = $vehicle->emai;
            $vehicleArray["registration_date"] = $vehicle->registration_date;
            $vehicleArray["owner_id"] = $vehicle->owner_id;
            $vehicleArray["call_number"] = $vehicle->call_number;
            $vehicleArray["type"] = $vehicle->type;
            $vehicleArray["imageUri"] = $vehicle->imageUri;
            $vehicleArray["packegeId"] = $vehicle->packegeId;
            $vehicleArray["state"] = $vehicle->state;
            $vehicleArray["sync"] = $vehicle->sync;
            $vehicleArray["sync_type"] = $vehicle->sync_type;
            $vehicleArray["plan"] = NULL;
            $vehicleArray["owner"] = NULL;
            $vehicleArray["extraPlanItem"] = NULL;



            $owner = $this->owner->get($vehicle->owner_id);
            if ($owner) {
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

                $vehicleArray["owner"] = $ownerArray;
            }

            $extraItem = $this->vehicle->getExtraItem($vehicle->_id);
            
            if ($extraItem) {
                $extraItemArray = array();
                foreach ($extraItem as $vehiclesExtraItem) {
                    $vehiclesExtraItemArray = array();
                    $vehiclesExtraItemArray["id"] = $vehiclesExtraItem->ID;
                    $vehiclesExtraItemArray["vehicle_id"] = $vehiclesExtraItem->VEHICLE_ID;
                    $vehiclesExtraItemArray["item_id"] = $vehiclesExtraItem->ITEM_ID;
                    $vehiclesExtraItemArray["sync"] = $vehiclesExtraItem->sync;
                    $vehiclesExtraItemArray["sync_type"] = $vehiclesExtraItem->sync_type;
                    array_push($extraItemArray, $vehiclesExtraItemArray);
                }
                $vehicleArray["extraPlanItem"] = $extraItemArray;
            }

            $package = $this->plan->get($vehicle->packegeId);
            $plan = array();
            $plan["id"] = $package->id;
            $plan["name"] = $package->name;
            $plan["sync"] = $package->sync;
            $plan["sync_type"] = $package->sync_type;

            $vehicleArray["plan"] = $plan;

            //$response["vehicles"] = $vehicleArray;
            array_push($response["vehicles"], $vehicleArray);


            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no vehicle found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados veiculos";

            // echo no users JSON
            API::httpResponse($response);
        }
    }

    public function getextraPlanItems($vehicle_id = NULL, $apiKey = NULL) {

        // array for JSON response
        $response = array();
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if ($vehicle_id == NULL) {
            $response["success"] = 0;
            $response["message"] = "Veiculo nao definido";
            API::httpResponse($response);
            exit();
        }
        if ($vehicle_id != NULL) {
            $vehicleExtraItems = $this->vehicle->getExtraItem($vehicle_id);
            if ($vehicleExtraItems) {
                $response["extraPlanItems"] = array();
                foreach ($vehicleExtraItems as $vehiclesExtraItem) {
                    $vehiclesExtraItemArray = array();
                    $vehiclesExtraItemArray["id"] = $vehiclesExtraItem->ID;
                    $vehiclesExtraItemArray["vehicle_id"] = $vehiclesExtraItem->VEHICLE_ID;
                    $vehiclesExtraItemArray["item_id"] = $vehiclesExtraItem->ITEM_ID;
                    $vehiclesExtraItemArray["sync"] = $vehiclesExtraItem->sync;
                    $vehiclesExtraItemArray["sync_type"] = $vehiclesExtraItem->sync_type;
                    array_push($response["extraPlanItems"], $vehiclesExtraItemArray);
                }

                // success
                $response["success"] = 1;
                API::httpResponse($response);
            } else {
                // no vehicle found
                $response["success"] = 0;
                $response["message"] = "Nao foram encontrados items extras";
                API::httpResponse($response);
            }
        }
    }

    public function setAsUpdated() {
        $condition = array("_id" => $this->input->post('id'));
        $this->synchronized(__CLASS__, $condition);
    }
}
?>
