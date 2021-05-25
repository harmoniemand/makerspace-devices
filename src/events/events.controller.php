<?php


if (!defined('ABSPATH')) {
    die('-1');
}

require_once(dirname(__FILE__) . "/../entities/event.entity.php");
require_once(dirname(__FILE__) . "/../entities/event-workshop-registration.entity.php");


if (!class_exists('EventsController')) {


    class EventsController
    {

        static public function Get($req)
        {

            $data = EventEntity::get_events();

            if ($req["event_slug"]) {
                foreach ($data as $event) {
                    if ($event->ms_event_slug == $req["event_slug"]) {

                        foreach ($event->workshops as &$workshop) {
                            $workshop->registrationsCount = count($workshop->registrations);
                            $workshop->registrations = null;
                        }

                        $response = new WP_REST_Response($event, 200);
                        $response->set_headers(['Content-Type' => 'application/json']);
                        $response->set_headers(['access-control-allow-origin' => '*']);
                        return $response;
                    }
                }
            }

            $response = new WP_REST_Response($data, 200);
            $response->set_headers(['Content-Type' => 'application/json']);
            $response->set_headers(['access-control-allow-origin' => '*']);

            return $response;
        }

        static public function Post($req)
        {
            $req_data = $req->get_json_params();
            
            $registration = new EventWorkshopRegistrationEntity((object) array(
                "ms_events_workshop_registration_workshop_id" => $req_data["workshop"], 
                "ms_events_workshop_registration_firstname" => $req_data["firstname"],
                "ms_events_workshop_registration_lastname" =>    $req_data["lastname"],
                "ms_events_workshop_registration_birthday" => $req_data["birthday"],
                "ms_events_workshop_registration_email" => $req_data["email"]
            ));


            $guid = EventWorkshopRegistrationEntity::create_registration($registration);

            $response = new WP_REST_Response(array("message" => "registration saved", "guid"=> $guid, "data"=> $req_data), 200);
            // $response = new WP_REST_Response($req_data, 200);
            $response->set_headers(['Content-Type' => 'application/json']);
            $response->set_headers(['access-control-allow-origin' => '*']);
            return $response;
        }
    }
}
