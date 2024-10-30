<?php

/**
 * IntellaSphere Data Base Banners Schema
 *
 * @author    Intellasphere
 * @category 	Core
 * @package 	IntellaShphere
 */
global $wpdb;
$table_name = $wpdb->prefix . "banner";
$intellasphere_db_version = '1.0.0';
$charset_collate = $wpdb->get_charset_collate();
if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {

    $sql = "CREATE TABLE $table_name (
             id mediumint(9) NOT NULL AUTO_INCREMENT,
            `pages` varchar(55) DEFAULT '' NOT NULL,
            `banner` varchar(55) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option('intellasphere_db_version', $intellasphere_db_version);
}

