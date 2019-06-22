<?php
/*
Plugin Name: PTC Secure Resource
Plugin URI:
Description: Secure Resources.
Author: Binesh Shakya
Version: 1.0
Author URI:
License: GPLv2
Text Domain: ptc_secure_resources
*/

define( 'PTC_SECURE_RESOURCES_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'PTC_SECURE_RESOURCES_FOLDER', 'secure_resources' );

require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/classes/class_resource_group.php';
require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/utility.php';
require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/admin/admin.php';

/**
 * Register shortcode to generate download link for secure resource
 */ 
add_shortcode( 'secure_resource', function( $atts, $content = '' ) {
    if( empty( $atts['resource_group'] ) || empty( $atts['file'] ) ) {
        return '';
    }
    
    $item = base64_encode( $atts['file'] );
    
    $html = "<a href='/secure-resource/?rg={$atts['resource_group']}&item={$item}' target='_blank'>{$content}</a>";
    return $html;
}, 10, 2 );

/**
 * Handle resource download request.
 * Allows download only if user is logged in, has necessary user role and the download limit has not reached
 */ 
add_action( 'init', function() {
    if( 0 !== strpos( $_SERVER['REQUEST_URI'], '/secure-resource/' ) ) {
        return;
    }
    
    if( empty( $_GET['item'] ) || empty( $_GET['rg'] ) ) {
        Secure_Resource_Utility::show_error_page( 'Invalid link' );
    }
    
    if( ! is_user_logged_in() ) {
        Secure_Resource_Utility::show_error_page( "You need to login to access this page. Please <a href='/wp-login.php'>click here to login</a>." );
    }
    
    $resource_group_id = $_GET['rg'];
    $item = base64_decode( $_GET['item'] );
    
    //check if item is valid
    $rg = Secure_Resource_Group::get_instance( $resource_group_id );
    
    if( empty( $rg ) ) {
        Secure_Resource_Utility::show_error_page( 'Invalid link' );
    }
    
    if( ! $rg->has_resource( $item ) ) {
       Secure_Resource_Utility::show_error_page( 'Invalid link' );
    }
    
    global $current_user;
    
    //check permission
    if( ! $rg->is_user_allowed_to_access( $current_user->ID ) ) {
        Secure_Resource_Utility::show_error_page( 'You do not have permission to access this page. Please contact NAFT for further information.' );
    }
    
    if( $rg->has_user_download_file_limit_reached( $current_user->ID, $item ) ) {
        Secure_Resource_Utility::show_error_page( 'Our record shows you have already downloaded the resource. You are no longer allowed to access the resource.' );
    }
    
    $file = $rg->get_full_file_path( $item );
    
    if( ! file_exists( $file ) || ! is_file( $file ) ) {
        return;    
    }
    
    Secure_Resource_Utility::send_headers( $file );
    
    Secure_Resource_Utility::send_file( $file );
    
    $rg->mark_file_downloaded( $current_user->ID, $item );
    
    exit;
} );

register_activation_hook( __FILE__, function() {
    global $wpdb;
	
	$table_name = $wpdb->prefix . 'secure_resource_groups';
	
	$sql = "CREATE TABLE $table_name ( 
    	    `id` INT(9) NOT NULL AUTO_INCREMENT , 
    	    `name` VARCHAR(100) NOT NULL , 
    	    `path` VARCHAR(100) NOT NULL , 
    	    `user_role` VARCHAR(40) NOT NULL , 
    	    PRIMARY KEY (`id`), 
    	    UNIQUE `secure_resource_group_path` (`path`)
	    ) ENGINE = MyISAM;
	)";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
} );