<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plan
 *
 * @author Voloide
 */
require APPPATH . 'libraries/API.php';
require APPPATH . 'libraries/synchronizeble.php';

class plan extends API implements synchronizeble {

    public function __construct() {
        parent::__construct();
        $this->load->model('planmodel', 'plan');
    }

    public function create() {

        // array for JSON response
        $response = array();

        if (isset($_POST['name'])) {
            $packageData = array(
                'name' => $this->input->post('name')
            );

            $insert = $this->plan->insert($packageData);

            if ($insert) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Plano sincronizado com sucesso.";

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

        if (isset($_POST['id']) && isset($_POST['name'])) {
            $packageData = array(
                'name' => $this->input->post('name')
            );

            $condition = array(
                'id' => $this->input->post('id')
            );

            $update = $this->plan->update($packageData, $condition);

            if ($update) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Plano sincronizado com sucesso.";

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

    public function get($plan_id, $apiKey = NULL) {
        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Access Denied!';
            API::httpResponse($response);
            exit();
        }
        
        if (isset($plan_id) && $plan_id != NULL) {
            $plan = $this->plan->get($plan_id);

            if ($plan) {
                $pln = array();
                $pln["id"]          = $plan->id;
                $pln["name"]        = $plan->name;
                $pln["sync"]        = $plan->sync;
                $pln["sync_type"]   = $plan->sync_type;
                
                $response["plan"]   = array();
                $response["plan"] = $pln;

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

        $plans = $this->plan->getAll();

        if ($plans) {
            $response["plans"] = array();
            foreach ($plans as $p) {

                $pln = array();
                $pln["id"]          = $p->id;
                $pln["name"]        = $p->name;
                $pln["sync"]        = $p->sync;
                $pln["sync_type"]   = $p->sync_type;
                array_push($response["plans"], $pln);
            }

            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados Planos";

            // echo no users JSON
            API::httpResponse($response);
        }
    }

    public function getPlanItemsRelation($apiKey = NULL) {
        // array for JSON response
        $response = array();
        
        if ($apiKey != $this->api_key_get()) {
            $response["success"] = 0;
            $response["message"] = 'Access Denied!';
            API::httpResponse($response);
            exit();
        }

        $planItemRelation = $this->plan->getPlanItemsRelation();

        if ($planItemRelation) {
            $response["planItemRelation"] = array();
            foreach ($planItemRelation as $p) {

                $plnItemRelarion = array();
                $plnItemRelarion["id"]          = $p->id;
                $plnItemRelarion["package_id"]  = $p->package_id;
                $plnItemRelarion["item_id"]     = $p->item_id;
                $plnItemRelarion["sync"]        = $p->sync;
                $plnItemRelarion["sync_type"]   = $p->sync_type;
                array_push($response["planItemRelation"], $plnItemRelarion);
            }

            // success
            $response["success"] = 1;

            // echoing JSON response
            API::httpResponse($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "Não foram encontrados dados";

            // echo no users JSON
            API::httpResponse($response);
        }
    }
    
    public function getByUser() {
        
    }

    public function setAsUpdated() {
        $condition = array("_id" => $this->input->post('id'));
        $this->synchronized(__CLASS__, $condition);
    }
}

?>
