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
    $data = [ 'active' => 0 ];
    
    $data = array_merge( $data, $_POST );
    
    if( empty( $data['id'] ) ) {
        $rg = new Secure_Resource_Group( $data );
    }
    else {
        $rg = Secure_Resource_Group::get_instance( $data['id'] );
        if( ! ( $rg instanceof Secure_Resource_Group ) ) {
            wp_send_json_error( ['error' => ['Invalid Resource Group'] ] );
        }
        
        $rg->set_data( $data );
    }
    $result = $rg->save();
    
    if($result instanceof WP_Error){
        $errors = $result->get_error_messages();
        wp_send_json_error( [ 'error' => $errors ] );
    }
    wp_send_json_success();
    exit;
} );

add_action( 'wp_ajax_secure_resources_downloads', function() {
    require_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/admin/resource_group_downloads.php';
    exit;
} );

add_action( 'wp_ajax_secure_resource_search_user', function() {
    $users = [];
    if( ! empty( $_POST['user'] ) ) {
        $search_term = $_POST['user'];
        $query = new WP_User_Query( array(
            'search'         => '*'.esc_attr( $search_term ).'*',
            'search_columns' => array(
                'user_login',
                'user_nicename',
                'user_email',
            ),
            // 'meta_query' => array(
            //     'relation' => 'OR',
            //     array(
            //         'key'     => 'first_name',
            //         'value'   => $search_term,
            //         'compare' => 'LIKE'
            //     ),
            //     array(
            //         'key'     => 'last_name',
            //         'value'   => $search_term,
            //         'compare' => 'LIKE'
            //     )
            // )
        ) );
        $users_found = $query->get_results();
        
        foreach( $users_found as $user ) {
            $user_info = get_userdata( $user->ID );
            $users[] = [
                'id' => $user->ID,
                'name' => $user_info->first_name . ' ' . $user_info->last_name,
                'email' => $user->user_email
            ];
        }
        
    }
    
    $data = ['users' => $users ];
    wp_send_json_success( $data );
    exit;
} );

add_action( 'wp_ajax_secure_resource_show_user_downloads', function() {
    $resource_group_id = $_GET['rg'];
    $user_id = $_GET['user'];
    
    // $downloads = [
    //     ['file' => 'abc.mp3', 'date' => '2019-03-21'],
    //     ['file' => 'xyz.zip', 'date' => '2019-03-22'],
    // ];
    $rg = Secure_Resource_Group::get_instance( $resource_group_id );
    
    $downloads = $rg->get_user_downloaded_files( $user_id );
    
    wp_send_json_success( $downloads );
    exit;
} );

add_action( 'wp_ajax_secure_resource_reset_user_downloads',function(){
    $resource_group_id = $_GET['rg'];
    $user_id = $_GET['user'];
    $file = $_GET['file'];
    
    $rg = Secure_Resource_Group::get_instance( $resource_group_id );
    
    $rg->reset_file_download( $user_id, $file );
    
    wp_send_json_success();
    exit;
});