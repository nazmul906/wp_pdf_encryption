<?php
/*
Plugin Name: PDF Viewer Plugin
Version: 1.0
*/

// Include Composer's autoloader
require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

// include 'vendor/autoload.php';
// Initialize the Ncrypt class

$ncrypt = new mukto90\Ncrypt;



function custom_shortcode_function() {
    // Check if the 'token' query parameter is present in the URL
    if (isset($_GET['token'])) {
        // Extract the encrypted token from the URL

       
        $encrypted_token = $_GET['token'];

        // Decrypt the token to obtain the JSON data
        $encryption_key = '34234'; 
        $ncrypt = new mukto90\Ncrypt;

    
        // update_option("hit","url is being hit");
        // $decrypted_data = $ncrypt->decrypt($encrypted_token, $encryption_key);
        $decrypted_data = $ncrypt->decrypt($encrypted_token);
        // update_option( 'decrypted data',  $decrypted_data ); 


        $url = get_option( 'restrict-elementor-pro_basic', true );
        update_option("pdf_url",$url);
 

        $pdf_urls = get_option('pdf_url', array()); // Get the array stored in the pdf_url option
        update_option("pdf_urls",$pdf_urls );
        $sample_file2_url = isset($pdf_urls['sample_file2']) ? $pdf_urls['sample_file2'] : '';
        $sample_file_url = isset($pdf_urls['sample_file']) ? $pdf_urls['sample_file'] : '';
      
        $basename2 = basename($sample_file2_url);
        $basename1 = basename($sample_file_url );

        update_option("french",$basename2);
        update_option("english",$basename1);

        // $all_pdf_url = get_option( 'french' );
        
        // echo($all_pdf_url);
// update_option("found",$$all_pdf_url);

// return '<iframe src="' . esc_url($all_pdf_url) . '" width="100%" height="600" frameborder="0"></iframe>';

        // Check if decryption was successful
        if ($decrypted_data !== false) {
            // Parse the JSON data
            $data = json_decode($decrypted_data, true);
            update_option( 'jsondecrypted_data',   $data ); 
            // Check if 'encryptionKey' and 'language' are present in the JSON data
            if (isset($data['encryptionKey']) && isset($data['language'])) {
                // Extract the hash and language
                $hash = $data['encryptionKey']; // Assuming 'encryptionKey' is the hash
                $language = $data['language'];
                update_option( 'language param is got here',   $language ); 
                $all_pdf_url = get_option(  $language );
                
                if (!empty($all_pdf_url )) {
                    return '<iframe src="' . esc_url($all_pdf_url) . '" width="100%" height="600" frameborder="0"></iframe>';
                } else {
                    return 'PDF not available for this language.';
                }
               
                // return "Hash: $hash, Language: $language";
            }
        }
       
    }


    return 'Invalid or missing token in the URL.';

}


add_shortcode('pdf_viewer', 'custom_shortcode_function');






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

    // Add the encrypted key and language as query parameters to the URL
    // $url = $base_url . '?token=' . urlencode($encrypted_key) . '&language=' . urlencode($language);
    return $url;
}







