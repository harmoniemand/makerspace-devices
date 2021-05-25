<?php

use EventEntity as GlobalEventEntity;

if (!defined('ABSPATH')) {
    die('-1');
}

require_once dirname(__FILE__) . "/../Helper/GuidHelper.php";
require_once dirname(__FILE__) . "/event-workshop-registration.entity.php";

class EventWorkshopEntity
{

    public $ms_event_workshop_id = 0;
    public $ms_event_id = 0;
    public $ms_event_workshop_image_url;
    public $ms_event_workshop_title;
    public $ms_event_workshop_description;
    public $ms_event_workshop_additional_info;
    public $ms_event_workshop_max_attendees;
    public $registrations;

    function __construct($obj = null)
    {

        if ($obj) {
            $this->ms_event_workshop_id = $obj->ms_event_workshop_id;
            $this->ms_event_id = $obj->ms_event_id;
            $this->ms_event_workshop_image_url = $obj->ms_event_workshop_image_url;
            $this->ms_event_workshop_title = $obj->ms_event_workshop_title;
            $this->ms_event_workshop_description = $obj->ms_event_workshop_description;
            $this->ms_event_workshop_additional_info = $obj->ms_event_workshop_additional_info;
            $this->ms_event_workshop_max_attendees = $obj->ms_event_workshop_max_attendees;
        }


        $this->registrations = EventWorkshopRegistrationEntity::get_registrations_by_workshop($this->ms_event_workshop_id);
    }

    /*
     * @return      int     id of the inserted workshop
     */
    public static function create_workshop(EventWorkshopEntity $workshop)
    {
        global $wpdb;

        $registration_sql = "INSERT INTO makerspace_events_workshops (
            ms_event_id, 
            ms_event_workshop_image_url, 
            ms_event_workshop_title, 
            ms_event_workshop_description, 
            ms_event_workshop_additional_info, 
            ms_event_workshop_max_attendees) VALUES (%d, %s, %s, %s, %s, %d)";
        $id = $wpdb->get_var($wpdb->prepare(
            $registration_sql,
            $workshop->ms_event_id,
            $workshop->ms_event_workshop_image_url,
            $workshop->ms_event_workshop_title,
            $workshop->ms_event_workshop_description,
            $workshop->ms_event_workshop_additional_info,
            $workshop->ms_event_workshop_max_attendees
        ));

        return true;
    }

    public static function get_workshops_by_event($event_id)
    {
        if ($event_id == null) {
            return [];
        }

        global $wpdb;

        $workshop_sql = "SELECT * from makerspace_events_workshops WHERE ms_event_id = %d";
        $workshops = $wpdb->get_results($wpdb->prepare($workshop_sql, $event_id));

        foreach ($workshops as &$workshop) {
            $workshop->registrations = EventWorkshopRegistrationEntity::get_registrations_by_workshop($workshop->ms_event_workshop_id);
        }

        return $workshops;
    }
    public static function get_workshop_by_id($workshop_id)
    {
        global $wpdb;

        $workshop_sql = "SELECT * from makerspace_events_workshops WHERE ms_event_workshop_id = %d";
        $workshop = new EventWorkshopEntity($wpdb->get_row($wpdb->prepare($workshop_sql, $workshop_id)));
        $workshop->registrations = EventWorkshopRegistrationEntity::get_registrations_by_workshop($workshop->ms_event_workshop_id);

        return $workshop;
    }


    public static function create_database_tables()
    {
        global $wpdb;

        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_events_workshops (
                ms_event_workshop_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                ms_event_id INT NOT NULL,
                ms_event_workshop_image_url CHAR(200),
                ms_event_workshop_title CHAR(200) NOT NULL,
                ms_event_workshop_description TEXT,
                ms_event_workshop_additional_info TEXT,
                ms_event_workshop_max_attendees INT
                )
            ";

        $wpdb->get_results($sql);
    }
}
