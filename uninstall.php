<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// drop a custom database table
global $wpdb;

delete_option('estimates-settings');
delete_site_option('estimates-settings');

$tableEstimates = $wpdb->prefix . 'estimates';

$wpdb->query("DROP TABLE IF EXISTS {$tableEstimates}");
