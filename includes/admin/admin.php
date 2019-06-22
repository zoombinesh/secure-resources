<?php
/**
 * Handles admin page functionalities
 */
 
/**
 * Add Secure Resource admin menu
 */ 
add_action( 'admin_menu', function() {
    add_menu_page( 
        'Secure Resources', 
        'Secure Resources', 
        'administrator', 
        'secure-resources', 
        'secure_resources_admin_page', 
        'dashicons-admin-network', 
        10 
    );
} );

/**
 * Renders secure resources admin page
 */ 
function secure_resources_admin_page() {
    require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/admin/resource_group.php';
}

add_action( "wp_ajax_secure_resources", function() {
    if( empty( $_REQUEST['rg'] ) ) {
        echo 'Invalid resource group';
        exit;
    }
    
    $rg = Secure_Resource_Group::get_instance( $_REQUEST['rg'] );
    
    if( ! ( $rg instanceof Secure_Resource_Group ) ) {
        echo 'Invalid resource group';
        exit;
    }
    
    $resources = $rg->get_resources();
    
    require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/admin/resource_group_resources.php';
    exit;
} );

add_action( "wp_ajax_secure_resources_edit", function() {
    if( empty( $_REQUEST['rg'] ) ) {
        echo 'Invalid resource group';
        exit;
    }
    
    $rg = Secure_Resource_Group::get_instance( $_REQUEST['rg'] );
    
    if( ! ( $rg instanceof Secure_Resource_Group ) ) {
        echo 'Invalid resource group';
        exit;
    }
    
    global $wp_roles;
    
    $roles = [];
        foreach( $wp_roles->roles as $role => $role_detail ) {
        $roles[ $role ] = $role_detail['name'];
    }
    
    require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/admin/resource_group_edit.php';
    exit;
} );

add_action( "wp_ajax_secure_resources_add", function() {
    global $wp_roles;
    
    $roles = [];
        foreach( $wp_roles->roles as $role => $role_detail ) {
        $roles[ $role ] = $role_detail['name'];
    }
    
    require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/admin/resource_group_edit.php';
    exit;
} );

add_action( 'wp_ajax_secure_resources_save', function() {
    if( empty( $_POST['id'] ) ) {
        $rg = new Secure_Resource_Group( $_POST );
    }
    else {
        $rg = Secure_Resource_Group::get_instance( $_POST['id'] );
        if( ! ( $rg instanceof Secure_Resource_Group ) ) {
            wp_send_json_error( ['error' => ['Invalid Resource Group'] ] );
        }
    }
    $result = $rg->save();
    
    if($result instanceof WP_Error){
        $errors = $result->get_error_messages();
        wp_send_json_error( [ 'error' => $errors ] );
    }
    wp_send_json_success();
    exit;
} );