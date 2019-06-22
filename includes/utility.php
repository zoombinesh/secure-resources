<?php
/**
 * Utility functions
 * @author Binesh Shakya
 * @since 17/06/2019
 */
 
class Secure_Resource_Utility {
    /**
     * Send http response headers to force download given file
     */ 
    public static function send_headers( $file, $type = null, $name = null ) {
        if ( empty( $name ) ) {
            $name = basename( $file );
        }
        
        if( empty( $type ) ) {
            $type = mime_content_type( $file );
        }
        
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="'.$name.'";');
        header('Content-Type: ' . $type);
        header('Content-Length: ' . filesize( $file ) );
    }
    
    /**
     * Send file content in chuncks as part of force download
     */ 
    public static function send_file( $file, $chunk_size = 1024 * 1024 ) {
        $handle = fopen( $file, 'rb' );
        while ( ! feof( $handle ) ) {
            $buffer = fread( $handle, $chunk_size );
            echo $buffer;
            ob_flush();
            flush();
        }
        
        fclose( $handle );
        
    }
    
    public function show_error_page( $error ) {
        include_once PTC_SECURE_RESOURCES_PLUGIN_DIR . '/includes/404.php';
        exit;
    }
}