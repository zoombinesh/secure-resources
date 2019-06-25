<?php
/**
 * Class to handle resource group related functions
 */ 
class Secure_Resource_Group {
    private $data;
    
    private $resources = null;
    
    private $full_path = null;
    
    private $wpdb = null;
    private $table = null;
    
    public function __construct( $data ) {
        global $wpdb;
        $this->wpdb = $wpdb;
        
        $this->table = $this->wpdb->prefix . 'secure_resource_groups';
        
        $this->data = $data;
        
        $this->full_path = dirname( $_SERVER['DOCUMENT_ROOT'] ) . '/' . PTC_SECURE_RESOURCES_FOLDER . $this->get_path();
    }
    
    public static function get_instance( $id ) {
        global $wpdb;
        $row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}secure_resource_groups WHERE id = '{$id}'", ARRAY_A );
        
        if( $row ) {
            return new self( $row );
        }
        
        return null;
    }
    
    public function get_id() {
        return $this->data['id'];
    }
    
    public function set_id( $value ) {
        $this->data['id'] = $value;
        return $this;
    }
    
    public function get_name() {
        return $this->data['name'];
    }
    
    public function set_name( $value ) {
        $this->data['name'] = $value;
        return $this;
    }
    
    public function get_path() {
        return $this->data['path'];
    }
    
    public function set_path( $value ) {
        $this->data['path'] = $value;
        return $this;
    }
    
    public function get_user_role() {
        return $this->data['user_role'];
    }
    
    public function get_user_role_display() {
        $role = $this->get_user_role();
        if( empty( $role ) ) {
            return '';
        }
        global $wp_roles;
        $role = $wp_roles->roles[$role];
        return $role['name'];
    }
    
    public function set_user_role( $value ) {
        $this->data['user_role'] = $value;
        return $this;
    }
    
    public function get_active() {
        return $this->data['active'];
    }
    
    public function is_active() {
        return 1 == $this->get_active();
    }
    
    public function set_active( $value ) {
        $this->data['active'] = 1 == $value ? 1 : 0;
        return $this;
    }
    
    public function set_data( $data ) {
        $this->data = $data;
    }
    
    public function save() {
        $errors = [];
        if( empty( $this->get_name() ) ) {
            $errors['name'] = 'Name is required';
        }
        if( empty( $this->get_path() ) ) {
            $errors['path'] = 'Path is required';
        }
        
        if( count( $errors ) ) {
            $error = new WP_Error();
            foreach( $errors as $code => $msg ) {
                $error->add( $code, $msg );
            }
            return $error;    
        }
        
        $data = $this->data;
        
        if( $this->get_id() ) {
            //update request
            
            unset( $data['id'] );
            
            return $this->wpdb->update( $this->table, $data, [ 'id' => $this->get_id() ] );
        }
        else {
            //new request
            return $this->wpdb->insert( $this->table, $data );
        }
    }
    
    public function get_resources() {
        if( ! is_null( $this->resources ) ) {
            return $this->resources;
        }
        
        $path = dirname( $_SERVER['DOCUMENT_ROOT'] ) . '/' . PTC_SECURE_RESOURCES_FOLDER . $this->get_path();
        
        $files = scandir( $path );
        
        foreach( $files as $file ) {
            if( '.' == $file || '..' == $file ) {
                continue;
            }
            
            $this->resources[] = $file;
        }
        
        return $this->resources;
    }
    
    public function has_resource( $resource ) {
        $resources = $this->get_resources();
        
        if( count( $resources ) <= 0 ) {
            return false;
        }
        
        return in_array( $resource, $resources );
    }
    
    public function get_resource_link( $resource ) {
        $item = base64_encode( $resource );
        $link = "/secure-resource/?rg={$this->get_id()}&item={$item}";
    
        return home_url( $link );
    }
    
    public function is_user_allowed_to_access( $user_id ) {
        
        if( empty( $this->get_user_role() ) ) {
            return false;
        }
        
        $user = get_userdata( $user_id );
        
        if( empty( $user ) || empty( $user->roles ) || ! is_array( $user->roles ) || ! in_array( $this->get_user_role(), $user->roles ) ) {
            return false;
        }
        
        return true;
    }
    
    public function user_downloaded_file_timestamp( $user_id, $file ) {
        $key = 'srd_' . $this->get_id() . '_' . $file;
        return get_user_meta( $user_id, $key );
    }
    
    public function has_user_download_file_limit_reached( $user_id, $file, $limit = 1 ) {
        $result = $this->user_downloaded_file_timestamp( $user_id, $file );
        if( ! is_array( $result ) ) {
            return false;
        }
        
        return count( $result ) >= $limit;
    }
    
    public function mark_file_downloaded( $user_id, $file ) {
        $key = 'srd_' . $this->get_id() . '_' . $file;
        add_user_meta( $user_id, $key, current_time( 'mysql' ) );
    }
    
    public function get_full_file_path( $file ) {
        return $this->full_path . $file;
    }
    
    public static function get_resource_groups() {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}secure_resource_groups ORDER BY id DESC", ARRAY_A );
        
        $list = [];
        
        foreach( $result as $row ) {
            $list[] = new self( $row );
        }
        
        return $list;
    }
    
    public function get_user_downloaded_files( $user_id ) {
        $prefix = "srd_{$this->get_id()}_";
        
        $query = "SELECT * FROM {$this->wpdb->prefix}usermeta WHERE user_id = {$user_id} AND meta_key LIKE '{$prefix}_%'";
        
        $results = $this->wpdb->get_results( $query, ARRAY_A );
        $list = [];
        
        foreach( $results as $row ) {
            $list[] = [
                'file' => str_replace( $prefix, '', $row['meta_key'] ),
                'date' => date( 'j M Y g:i a', strtotime( $row['meta_value'] ) )
            ];
        }
        
        return $list;
    }
    
    public function reset_file_download( $user_id, $file ) {
        $meta_key = "srd_{$this->get_id()}_" . $file;
        $new_meta_key = "off_" . $meta_key;
        $query = "UPDATE {$this->wpdb->prefix}usermeta set meta_key = '{$new_meta_key}' WHERE user_id = {$user_id} AND meta_key = '{$meta_key}'";
        
        return $this->wpdb->query( $query );
    }
}