<?php


if (!defined('ABSPATH')) {
    die('-1');
}


if (!class_exists('EventsController')) {


    class EventsController
    {

        static public function Get($data)
        {

            $the_slug = 'my_slug';
            $args = array(
                'name'        => $data["event_slug"],
                'post_type'   => 'mse_events',
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $events = get_posts($args);
            if (!$events) :
                $response = new WP_REST_Response(array("message" => "no event found"), 404);
                $response->set_headers(['Content-Type' => 'application/json']);
                $response->set_headers(['access-control-allow-origin' => '*']);
                return $response;
            endif;

            $event = $events[0];
            $event->custom_data_raw = get_post_meta($event->ID, "event_custom_data", true);
            $event->custom_data = json_decode(get_post_meta($event->ID, "event_custom_data", true));




            // $spaceApiConfig = get_option("space_api_config");

            // if ($spaceApiConfig == false) {
            //     $response = new WP_REST_Response((object)array(
            //         "message" => "Space API is not configured. Please go to admin interface, switch to settings and Space API Settings to set the required fields."
            //     ), 500);
            //     $response->set_headers(['Content-Type' => 'application/json']);

            //     return $response;
            // }


            $response = new WP_REST_Response($event, 200);
            $response->set_headers(['Content-Type' => 'application/json']);
            $response->set_headers(['access-control-allow-origin' => '*']);

            return $response;
        }
    }
}
