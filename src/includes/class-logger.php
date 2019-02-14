<?php


if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'MS_Device_Management_Logger' ) ) {

	class MSDM_Logger {
        
        private static function writeMessage($msg) {
            //echo "<script> console.log('wp-log: " . $msg . "'); </script>";
            file_put_contents( MS_DM_DIR . "/ms-dm.log", $msg . "\n", FILE_APPEND);
        }
        
        public static function Debug($msg) {
            self::writeMessage( date("Y.m.d h:i:s") . " - DEBUG - " . $msg );
        }
    }
}