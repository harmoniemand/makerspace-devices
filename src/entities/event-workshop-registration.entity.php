<?php

use EventEntity as GlobalEventEntity;

if (!defined('ABSPATH')) {
    die('-1');
}

require_once dirname(__FILE__) . "/../Helper/GuidHelper.php";

class EventWorkshopRegistrationEntity
{
    public $ms_events_workshop_registration_id;
    public $ms_events_workshop_registration_guid;
    public $ms_events_workshop_registration_firstname;
    public $ms_events_workshop_registration_lastname;
    public $ms_events_workshop_registration_birthday;
    public $ms_events_workshop_registration_email;
    public $ms_events_workshop_registration_workshop_id;


    function __construct($obj)
    {
        if ($obj) {
            $this->ms_events_workshop_registration_id = $obj->ms_events_workshop_registration_id;
            $this->ms_events_workshop_registration_guid = $obj->ms_events_workshop_registration_guid;
            $this->ms_events_workshop_registration_firstname = $obj->ms_events_workshop_registration_firstname;
            $this->ms_events_workshop_registration_lastname = $obj->ms_events_workshop_registration_lastname;
            $this->ms_events_workshop_registration_birthday = $obj->ms_events_workshop_registration_birthday;
            $this->ms_events_workshop_registration_email = $obj->ms_events_workshop_registration_email;
            $this->ms_events_workshop_registration_workshop_id = $obj->ms_events_workshop_registration_workshop_id;
        }
    }

    public static function get_registration_by_guid($registration_guid)
    {
        if ($registration_guid == null) {
            return null;
        }

        global $wpdb;

        $registration_sql = "SELECT * from makerspace_events_workshop_registrations WHERE ms_events_workshop_registration_guid = %s";
        $registration = $wpdb->get_row($wpdb->prepare($registration_sql, $registration_guid));

        return $registration;
    }

    public static function get_registrations_by_workshop($event_workshop_id)
    {
        if ($event_workshop_id == null) {
            return [];
        }

        global $wpdb;

        $registration_sql = "SELECT * from makerspace_events_workshop_registrations WHERE ms_events_workshop_registration_workshop_id = %d";
        $workshops = $wpdb->get_results($wpdb->prepare($registration_sql, $event_workshop_id));

        return $workshops;
    }


    /*
     * @param   EventWorkshopRegistrationEntity   $registration
     */
    public static function create_registration(EventWorkshopRegistrationEntity $registration)
    {
        global $wpdb;

        if ($registration->ms_events_workshop_registration_guid == null) {

            $registration->ms_events_workshop_registration_guid = GuidHelper::GUID();
        }

        $registration_sql = "INSERT INTO makerspace_events_workshop_registrations (
            ms_events_workshop_registration_guid, 
            ms_events_workshop_registration_firstname,
            ms_events_workshop_registration_lastname,
            ms_events_workshop_registration_birthday,
            ms_events_workshop_registration_email,
            ms_events_workshop_registration_workshop_id) VALUES (%s, %s, %s, %s, %s, %d)";
        $wpdb->get_var($wpdb->prepare(
            $registration_sql,
            $registration->ms_events_workshop_registration_guid,
            $registration->ms_events_workshop_registration_firstname,
            $registration->ms_events_workshop_registration_lastname,
            $registration->ms_events_workshop_registration_birthday,
            $registration->ms_events_workshop_registration_email,
            $registration->ms_events_workshop_registration_workshop_id
        ));

        return $registration->ms_events_workshop_registration_guid;
    }



    public static function create_database_tables()
    {
        global $wpdb;

        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_events_workshop_registrations (
                    ms_events_workshop_registration_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    ms_events_workshop_registration_guid CHAR(40) NOT NULL,
                    ms_events_workshop_registration_firstname CHAR(200) NOT NULL,
                    ms_events_workshop_registration_lastname CHAR(200) NOT NULL,
                    ms_events_workshop_registration_birthday CHAR(200) NOT NULL,
                    ms_events_workshop_registration_email CHAR(200) NOT NULL,
                    ms_events_workshop_registration_workshop_id INT NOT NULL,
                )
            ";

        $wpdb->get_results($sql);
    }
}
