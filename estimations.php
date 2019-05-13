<?php

/*
Plugin Name: Estimates
Description: A Vue.js and PHP-Vendor easy estimations handler.
Version: 1.0
Author: Sergio Barbosa
License: GPLv2 or later
*/

use App\Controller\EstimationController;
use App\Services\EstimationService;

require_once 'vendor/autoload.php';

define( 'ESTIMATES_VERSION', '2.0' );
define( 'BASE_WEBSITE_URL',  get_site_url(null, '/'));
define( 'ROOT_PUBLIC_PLUGIN_URL',  get_site_url(null, '/estimates/'));
define( 'ESTIMATES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ESTIMATES_PLUGIN_BUILD_PUBLIC', plugins_url( 'estimator/public/dist'));

add_action('parse_request', 'plugin_route_handler');
add_action( 'admin_menu', 'estimation_create_menu' );

register_activation_hook( __FILE__, 'plugin_activation');
//register_deactivation_hook( __FILE__, 'plugin_deactivation');

/**
 * This function create all required database tables.
 * @author Sergio Barbosa <sbarbosa115@gmail.com>
 */
function plugin_activation() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $tableEstimates = $wpdb->prefix . 'estimates';

    $sql = "CREATE TABLE {$tableEstimates} (
                id int(10) NOT NULL AUTO_INCREMENT,
                created_at datetime COLLATE utf8mb4_unicode_ci,
                detail text COLLATE utf8mb4_unicode_ci,
                PRIMARY KEY (`id`)
            ) $charset_collate";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

/**
 * This function remove all database tables.
 * @author Sergio Barbosa <sbarbosa115@gmail.com>
 */
function plugin_deactivation() {
    global $wpdb;

    $tableEstimates = $wpdb->prefix . 'estimates';
    $sql = "DROP TABLE {$tableEstimates};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

function plugin_route_handler() {
    global $wpdb;

    if($_SERVER['REQUEST_URI'] === '/estimates/new') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data =  json_decode(file_get_contents('php://input'), true);
            echo (new EstimationController())->save($data);
        } else {
            echo (new EstimationController())->new();
        }
        die;
    }

    if(strpos($_SERVER['REQUEST_URI'], 'estimates/pdf')) {
        $id = $_GET['id'];
        $lastEstimation = (new EstimationService($wpdb))->getEstimateById($id);
        (new EstimationController())->pdf($lastEstimation['detail']);
    }
}


/**
 * This function send the email with attached pdf.
 * @author Sergio Barbosa <sbarbosa115@gmail.com>
 */
function sendmail(){

    global $wpdb;
    if(get_option( 'estimates_from_email' )) {
        $domain = get_option( 'estimates_from_email' );
        $headers = array("From: Notifications <{$domain}>;");
    } else {
        $headers = array();
    }

    if(get_option( 'estimates_to_email' )){
        wp_mail(array(get_option( 'estimates_to_email')), 'Estimates Created', 'Someone has created a new estimate.',
            $headers,
            array(BASE_WEBSITE_URL  . 'estimator/pdf?id=' . $wpdb->insert_id));
    }
}

/**
 * Enable the main menu option
 */
function estimates_create_menu() {
    add_options_page( 'Estimates', 'Create Estimate', 'manage_options', 'create-new-estimate', 'estimate_options' );
    register_setting( 'estimates-settings', 'estimates_from_email' );
    register_setting( 'estimates-settings', 'estimates_to_email' );
    register_setting( 'estimates-settings', 'estimates_signature_space' );
}

/**
 * Enable the main menu option
 */
function estimates_options() {
    if (!current_user_can('manage_options'))  {
        wp_die(__( 'You do not have sufficient permissions to access this page.'));
    }

    include 'templates/configuration.php';
}
