<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of synchronize
 *
 * @author Voloide
 */
require_once APPPATH . 'libraries/API.php';

class synchronize extends API {

    public function __construct() {
        parent::__construct();

        $this->load->model('vehiclemodel', 'vehicle');
        $this->load->model('ownermodel', 'owner');
        $this->load->model('usermodel', 'user');
        $this->load->model('planmodel', 'package');
        $this->load->model('commandmodel', 'command');
        $this->load->model('itemmodel', 'item');
        $this->load->model('contactmodel', 'contact');


        $this->load->helper('array');
    }

    public function index() {
//        print_r($this->api_key_get());
    }

    public function getUpdates($loginNumber = NULL, $password = NULL, $apiKey = NULL) {
        $response = array();
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if ($loginNumber != NULL && $password != NULL) {
            $condition = Array("loginnumber" => $loginNumber, "password" => $password);
            $user = $this->user->validate($condition);
            if ($user) {
                foreach ($user as $key => $u) {
                    $userArray = array();
                    $userArray["id"] = $u->id;
                }
            } else {
                $response["success"] = 0;
                $response["message"] = "Utilizador inválido";
                API::httpResponse($response);
                exit();
            }

            $user_group = $this->user->getGroup(array("user_id" => $userArray["id"]));
            if ($user_group) {
                $response["update_headers"] = array();

                // get 
                foreach ($user_group as $user) {
                    $vehicleSyncHeaders = $this->sync->getSyncHeaders($user->vehicle_id, $userArray["id"]);
                    if ($vehicleSyncHeaders) {

                        foreach ($vehicleSyncHeaders as $data) {
                            $vehicleSyncHeaders = array();
                            $vehicleSyncHeaders["id"] = $data->id;
                            $vehicleSyncHeaders["record_id"] = $data->record_id;
                            $vehicleSyncHeaders["record_table"] = $data->record_table;
                            $vehicleSyncHeaders["sync_status"] = $data->sync_status;
                            $vehicleSyncHeaders["sync_type"] = $data->sync_type;
                            $vehicleSyncHeaders["target_id"] = $data->target_id;
                            array_push($response["update_headers"], $vehicleSyncHeaders);
                        }
                    }
                }
                // success
                $response["success"] = 1;
                API::httpResponse($response);
            } else {
                $response["success"] = 0;
                $response["message"] = "Nao ha novas actualizacoes";
                API::httpResponse($response);
            }
        } else {
            $response["success"] = 0;
            $response["message"] = "Utilizador inválido";
            API::httpResponse($response);
        }
    }

    public function checkForUpdates($loginNumber = NULL, $password = NULL, $apiKey = NULL) {
        // array for JSON response
        $response = array();
        $updatesAvailable = FALSE;

        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if ($loginNumber != NULL && $password != NULL) {

            $condition = Array("loginnumber" => $loginNumber, "password" => $password);

            $user = $this->user->validate($condition);
            if ($user) {
                foreach ($user as $key => $u) {
                    $userArray = array();
                    $userArray["id"] = $u->id;
                }
            } else {
                $response["success"] = 0;
                $response["message"] = "Utilizador inválido";
                API::httpResponse($response);
                exit();
            }
        }


        $condition = array("user_id" => $userArray["id"]);
        $user_group = $this->user->getGroup($condition);

        if ($user_group) {
            $response["updates"] = array();
            foreach ($user_group as $user) {
                $syncData = $this->sync->getSyncHeaders($user->vehicle_id, $userArray["id"]);
                if ($syncData) {
                    $updatesAvailable = TRUE;
                }
            }
        }

        $response["updates"] = $updatesAvailable;
        $response["success"] = 1;
        API::httpResponse($response);
    }

    public function setAsUpdated() {
        $response = array();
        if (isset($_POST['sync_id']) && isset($_POST['record_id']) && isset($_POST['user_id']) && isset($_POST['api_key'])) {

            if ($this->input->post('api_key') != $this->api_key_get()) {
                $response["success"] = 0;
                $response["message"] = "Invalid API_KEY";
                API::httpResponse($response);
                exit();
            }
            $syncData = array(
                'user_id' => $this->input->post('user_id'),
                'sync_id' => $this->input->post('sync_id')
            );


            $condition = Array('sync_id' => $this->input->post('sync_id'));
            $synced = $this->sync->setAsSync($syncData);

            if ($synced) {
                $response["success"] = 1;
                $response["message"] = "set as synced";

                //check if is the last user that synced
                if ($this->sync->countVehicleUserSyncDone($this->input->post('sync_id')) + 1 == $this->user->countVehicleUser->users($this->input->post('vehicle_id'))) {
                    $syncData1 = Array('sync' => 1);
                    $condition = Array('id' => $this->input->post('sync_id'));
                    if ($this->sync->update($syncData1, $condition))
                        $response["closed"] = 1;
                    API::httpResponse($response);
                }
            }else {
                $response["success"] = 0;
                $response["closed"] = 0;
                $response["message"] = "Failed to set as synced";
                API::httpResponse($response);
            }
        } else {
            $response["success"] = 0;
            $response["closed"] = 0;
            $response["message"] = "Required filds missing";
            API::httpResponse($response);
        }
    }

    public function getSigleUserData($logiNumber = NULL, $password = NULL, $apiKey = NULL) {
        // array for JSON response
        $response = array();

        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }

        if ($logiNumber != NULL && $password != NULL) {
            $condition = Array("loginnumber" => $logiNumber, "password" => $password);

            $user = $this->user->validate($condition);
            if ($user) {
                foreach ($user as $key => $u) {
                    $userArray = array();
                    $userArray["id"] = $u->id;
                }
                $vehicles = $this->vehicle->getAllToSync($userArray["id"]);
                if ($vehicles) {
                    $response["vehicles"] = array();
                    $response["owners"] = array();
                    $response["vehicleExtraItem"] = array();


                    foreach ($vehicles as $vehicle) {

                        $vehicleArray = array();
                        $vehicleArray["_id"] = $vehicle->_id;
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

                        array_push($response["vehicles"], $vehicleArray);

                        $owner = $this->owner->get($vehicle->owner_id);
                        if ($owner) {

                            $ownerArray = array();
                            $ownerArray["id"] = $owner->_id;
                            $ownerArray["name"] = $owner->name;
                            $ownerArray["surname"] = $owner->surname;
                            $ownerArray["contact"] = $owner->contact;
                            $ownerArray["email"] = $owner->email;
                            $ownerArray["type"] = $owner->type;
                            $ownerArray["sync"] = $owner->sync;
                            $ownerArray["sync_type"] = $owner->sync_type;

                            array_push($response["owners"], $ownerArray);
                        }

                        //$c = array("VEHICLE_ID" => $vehicle->_id);
                        $vehiclesExtraItems = $this->vehicle->getExtraItem($vehicle->_id);

                        if ($vehiclesExtraItems) {
                            foreach ($vehiclesExtraItems as $vehiclesExtraItem) {
                                $vehiclesExtraItemArray = array();
                                $vehiclesExtraItemArray["id"] = $vehiclesExtraItem->ID;
                                $vehiclesExtraItemArray["vehicle_id"] = $vehiclesExtraItem->VEHICLE_ID;
                                $vehiclesExtraItemArray["item_id"] = $vehiclesExtraItem->ITEM_ID;
                                $vehiclesExtraItemArray["sync"] = $vehiclesExtraItem->sync;
                                $vehiclesExtraItemArray["sync_type"] = $vehiclesExtraItem->sync_type;
                                array_push($response["vehicleExtraItem"], $vehiclesExtraItemArray);
                            }
                        }
                    }
                }
            }
            // success
            $response["success"] = 1;
            API::httpResponse($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Invalid User";
            API::httpResponse($response);
        }
    }

    public function syncAll($loginNumber = NULL, $password = NULL, $apiKey = NULL) {
        // array for JSON response
        $response = array();

        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Invalid API KEY';
            API::httpResponse($response);
            exit();
        }


        if ($loginNumber != NULL && $password != NULL) {
            $condition = Array("loginnumber" => $loginNumber, "password" => $password);

            $user = $this->user->validate($condition);

            if ($user) {

                foreach ($user as $key => $u) {
                    $userArray = array();
                    $userArray["id"] = $u->id;
                }

                //$vehicleCondition = array("user_id" => $userArray["id"]);

                $vehicles = $this->vehicle->getAllToSync($userArray["id"]);

                $commands = $this->command->getAll();
                $parts = $this->command->getAllParts();
                $cmdResponses = $this->command->getAllCommandResponse();
                $items = $this->item->getAll();

                $packageItem = $this->package->getPlanItemsRelation();

                if ($vehicles) {
                    $response["vehicles"] = array();
                    $response["commands"] = array();
                    $response["parts"] = array();
                    $response["items"] = array();
                    $response["packageItems"] = array();
                    $response["commandResponses"] = array();

                    foreach ($vehicles as $vehicle) {

                        $vehicleArray = array();
                        $vehicleArray["_id"] = $vehicle->_id;
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

                        $package = $this->package->get($vehicleArray["packegeId"]);
                        $plan = array();
                        $plan["id"] = $package->id;
                        $plan["name"] = $package->name;
                        $plan["sync"] = $package->sync;
                        $plan["sync_type"] = $package->sync_type;

                        $vehicleArray["plan"] = $plan;

                        $owner = $this->owner->get($vehicle->owner_id);
                        if ($owner) {
                            $ownerArray = array();
                            $ownerArray["id"] = $owner->_id;
                            $ownerArray["name"] = $owner->name;
                            $ownerArray["surname"] = $owner->surname;
                            $ownerArray["type"] = $owner->type;
                            $ownerArray["sync"] = $owner->sync;
                            $ownerArray["sync_type"] = $owner->sync_type;

                            if ($owner->contacto_id > 0) {
                                $ownerContact = $this->contact->get($owner->contacto_id);

                                $ownerContactArray = array();
                                $ownerContactArray["id"] = $ownerContact->id;
                                $ownerContactArray["email"] = $ownerContact->email;
                                $ownerContactArray["telefone_1"] = $ownerContact->telefone_1;
                                $ownerContactArray["telefone_2"] = $ownerContact->telefone_2;
                            } else {
                                $ownerContactArray = NULL;
                            }

                            $ownerArray["contacto"] = $ownerContactArray;
                        }

                        $vehicleArray["owner"] = $ownerArray;

                        $vehiclesExtraItems = $this->vehicle->getExtraItem($vehicle->_id);

                        if ($vehiclesExtraItems != null) {
                            $ExtraItemsArray = array();
                            foreach ($vehiclesExtraItems as $vehiclesExtraItem) {
                                $vehiclesExtraItemArray = array();
                                $vehiclesExtraItemArray["id"] = $vehiclesExtraItem->ID;
                                $vehiclesExtraItemArray["vehicle_id"] = $vehiclesExtraItem->VEHICLE_ID;
                                $vehiclesExtraItemArray["item_id"] = $vehiclesExtraItem->ITEM_ID;
                                $vehiclesExtraItemArray["sync"] = $vehiclesExtraItem->sync;
                                $vehiclesExtraItemArray["sync_type"] = $vehiclesExtraItem->sync_type;
                                array_push($ExtraItemsArray, $vehiclesExtraItemArray);
                            }
                            $vehicleArray["extraPlanItems"] = $ExtraItemsArray;
                        }




                        array_push($response["vehicles"], $vehicleArray);
                    }

                    foreach ($commands as $c) {

                        $cmd = array();
                        $cmd["id"] = $c->id;
                        $cmd["name"] = $c->name;
                        $cmd["code"] = $c->code;
                        $cmd["icon"] = $c->icon;
                        $cmd["item_code"] = $c->item_code;
                        $cmd["user_type_target"] = $c->user_type_target;
                        $cmd["sync"] = $c->sync;
                        $cmd["sync_type"] = $c->sync_type;

                        array_push($response["commands"], $cmd);
                    }

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



                    foreach ($items as $i) {

                        $it = array();
                        $it["id"] = $i->id;
                        $it["code"] = $i->code;
                        $it["name"] = $i->name;
                        $it["sync"] = $i->sync;
                        $it["sync_type"] = $i->sync_type;
                        array_push($response["items"], $it);
                    }


                    //array_push($response["packages"], $package);
                    foreach ($packageItem as $p) {

                        $pkgItem = array();
                        $pkgItem["id"] = $p->id;
                        $pkgItem["package_id"] = $p->package_id;
                        $pkgItem["item_id"] = $p->item_id;
                        $pkgItem["sync"] = $p->sync;
                        $pkgItem["sync_type"] = $p->sync_type;
                        array_push($response["packageItems"], $pkgItem);
                    }
                    //array_push($response["packageItems"], $packageItem);

                    foreach ($cmdResponses as $key => $value) {
                        $cmdRsp = array();
                        $cmdRsp["id"] = $value->id;
                        $cmdRsp["header"] = $value->header;
                        $cmdRsp["title"] = $value->title;
                        $cmdRsp["description"] = $value->description;
                        array_push($response["commandResponses"], $cmdRsp);
                    }


                    // success
                    $response["success"] = 1;
                    API::httpResponse($response);
                } else {

                    $response["success"] = 0;
                    $response["message"] = "Missing data";
                    API::httpResponse($response);
                }
            } else {
                // required field is missing
                $response["success"] = 0;
                $response["message"] = "invalid user";
                API::httpResponse($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Required field(s) is missing";
            API::httpResponse($response);
        }
    }

    function delete($sync_id = 0) {
        if ($sync_id > 0) {
            $delete = $this->sync->delete(array('id' => $sync_id));
            return $delete;
        }
    }

}

?>
