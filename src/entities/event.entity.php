<?php


if (!defined('ABSPATH')) {
    die('-1');
}

require_once dirname(__FILE__) . "/../Helper/GuidHelper.php";
require_once dirname(__FILE__) . "/event-workshop.entity.php";

class EventEntity
{

    public $ms_event_id;
    public $ms_event_title;
    public $ms_event_slug;
    public $workshops;

    function __construct($obj)
    {

        if ($obj) {
            $this->ms_event_id = $obj->ms_event_id;
            $this->ms_event_title = $obj->ms_event_title;
            $this->ms_event_slug = $obj->ms_event_slug;
        }

        $this->workshops = EventWorkshopEntity::get_workshops_by_event($this->ms_event_id);
    }

    /**
     * returns all events from database
     * @return      EventWorkshopEntity[]       list of workshops for this event
     */
    public static function get_events()
    {
        global $wpdb;

        $events = [];

        $events_sql = "SELECT * from makerspace_events";
        $result_events = $wpdb->get_results($events_sql);

        foreach ($result_events as &$item) {

            $event = new EventEntity($item);
            array_push($events, $event);
        }

        return $events;
    }
    public static function get_event_by_id($event_id)
    {
        global $wpdb;

        $events_sql = "SELECT * from makerspace_events WHERE ms_event_id=%d";
        $event = new EventEntity((object) $wpdb->get_row($wpdb->prepare($events_sql, $event_id)));
        return $event;
    }

    public static function create_database_tables()
    {
        global $wpdb;

        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_events (
                ms_event_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                ms_event_title CHAR(200) NOT NULL,
                ms_event_slug CHAR(100) NOT NULL
                )
            ";

        $wpdb->get_results($sql);
    }
}
