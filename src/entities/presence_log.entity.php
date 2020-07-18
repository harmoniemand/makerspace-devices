<?php


if (!defined('ABSPATH')) {
    die('-1');
}

class PresenceLogEntity
{

    // returns
    public static function get_visitors_between($from, $to) {
        global $wpdb;

        $sql = "
SELECT COUNT(*) FROM (
    SELECT 
        mpl_user_id,
        count(mpl_user_id) as COUNT
    FROM `makerspace_presence_logs`
    WHERE mpl_datetime BETWEEN %s AND %s
    GROUP BY mpl_user_id
) as tmp
WHERE MOD(count, 2) > 0";

        $count = $wpdb->get_var($wpdb->prepare(
            $sql,
            $from->format("Y-m-d H:i:s"),
            $to->format("Y-m-d H:i:s")
        ));

        return $count;
    }

    
    public static function get_visitors_present_at($datetime) {
        $start =  (new DateTime($datetime->format("Y-m-d H:i:s")))->setTime(0,01);

        return (object) array(
            "start" => $start->format("Y-m-d H:i:s"),
            "end" => $datetime->format("Y-m-d H:i:s"),
            "count" => PresenceLogEntity::get_visitors_between($start, $datetime)
        );
    }
    
    // returns log-count per visitor for a given date
    public static function get_visitors_by_day($date)  {
        $day_start = ($date->setTime(0, 0, 0));
        $day_end = ($date->setTime(23, 59, 59));

        return $this->get_visitors_between($day_start, $day_end);
    }

    // returns count of present visitors 
    public static function shortcode_visitor_count($atts)
    {
        $entries = $this->get_visitors_by_day(get_datetime());
        return $entries;

        $count = 0;
        foreach ($entries as $e) {
            if ($e->count % 2 == 1) {
                $count++;
            }
        }

        return $count;
    }

    public static function create_database_tables() {
        global $wpdb;

        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_advance_registrations (
                mar_registration_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                mar_user_id  bigint(20) NOT NULL,
                mar_from INT NOT NULL,
                mar_to INT NOT NULL,
                mar_approved_by INT,
                mar_deleted INT,
                mar_term_id bigint(20),
                mse_device_message TEXT
                )
            ";

        $wpdb->get_results($sql);


        $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_presence_logs (
                mpl_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                mpl_user_id  bigint(20) NOT NULL,
                mpl_datetime datetime NOT NULL
                )
            ";

        $wpdb->get_results($sql);
    }


}