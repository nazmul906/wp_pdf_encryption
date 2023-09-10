<?php
/*
Plugin Name: PDF Viewer Plugin
Version: 1.0
*/

// Include Composer's autoloader
require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

// include 'vendor/autoload.php';
// Initialize the Ncrypt class
// $ncrypt = new mukto90\Ncrypt\Ncrypt();
$ncrypt = new mukto90\Ncrypt;





// Function to generate the PDF viewer URL with encrypted key and language

add_action('rest_api_init', 'register_pdf_viewer_api_endpoint');

function register_pdf_viewer_api_endpoint() {
    register_rest_route('pdf-viewer/v1', '/generate-url', array(
        'methods' => 'POST',
        'callback' => function (WP_REST_Request $request) {
            // Retrieve the encryptionKey and language from the request
            $encryptionKey = $request->get_param('encryptionKey');
            $language = $request->get_param('language');

            // Call the generate_pdf_viewer_url function with the extracted parameters
            $url = generate_pdf_viewer_url($encryptionKey, $language);

            // Return the URL as the response
            return rest_ensure_response($url);
        },
    ));
}

function generate_pdf_viewer_url($encryptionKey, $language) {
    global $ncrypt;
    // update_option('restapi','res');
    // update_option( 'the_key', $encryptionKey ); 
    // update_option( 'lan', $language ); 
    // Replace with your URL generation logic

    $base_url = 'http://localhost/wordpress/page-pdf/';
    // $base_url = 'http://localhost/wordpress/pdf/';
    




    // Combine encryptionKey and language into an array and encrypt the result
    $combined_data = json_encode(array('encryptionKey' => $encryptionKey, 'language' => $language));
    // update_option( 'combine', $combined_data); 
    $encrypted_key = $ncrypt->encrypt($combined_data, '34234');
    // update_option( 'encrypt',  $encrypted_key);

    // Add the encrypted key as a query parameter to the URL
    $url = $base_url . '?token=' . urlencode($encrypted_key);

    
    return $url;
}








