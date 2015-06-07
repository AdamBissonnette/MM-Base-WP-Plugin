<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

    function SaveUser($atts)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;

        $atts["entityName"] = "User";
        if ($atts->id == -1)
        {
            $atts["fn"] = "POST";
            $json = json_decode($curly->DoCurl($atts), "POST");
        }
        else
        {
            $atts["fn"] = "PUT";
            $json = json_decode($curly->DoCurl($atts), "PUT");   
        }

        return $json;
    }

    remove_all_filters ('wpcf7_before_send_mail');
    add_action( 'wpcf7_mail_sent', '\MmmPluginToolsNamespace\cf7_posted' );

    function cf7_posted( $contact_form ) {
        $title = $contact_form->title();

        if ( 'Get Started' == $title ) {

            // array(12) {
            //   ["_wpcf7"]=>
            //   string(3) "198"
            //   ["_wpcf7_version"]=>
            //   string(3) "4.2"
            //   ["_wpcf7_locale"]=>
            //   string(5) "en_US"
            //   ["_wpcf7_unit_tag"]=>
            //   string(17) "wpcf7-f198-p64-o1"
            //   ["_wpnonce"]=>
            //   string(10) "3cdd6e6630"
            //   ["party_name"]=>
            //   string(4) "Test"
            //   ["name"]=>
            //   string(4) "test"
            //   ["email"]=>
            //   string(13) "test@test.com"
            //   ["phone"]=>
            //   string(11) "13063704254"
            //   ["_wpcf7_captcha_challenge_captcha-648"]=>
            //   string(10) "3210000978"
            //   ["captcha-648"]=>
            //   string(4) "knmx"
            //   ["_wpcf7_is_ajax_call"]=>
            //   string(1) "1"
            // }

            $data = array('id' => -1, 'name' => $_REQUEST["name"], 'email' => $_REQUEST["email"], 'phone' => $_REQUEST["phone"], 'party_name' => $_REQUEST["party_name"]);

            SaveUser($data);
        }
    }


//     if ( null == username_exists( $email_address ) ) {
 
//     $password = wp_generate_password( 12, false );
//     $user_id = wp_create_user( $email_address, $password, $email_address );
 
//     wp_update_user(
//         array(
//             'ID'          =>    $user_id,
//             'nickname'    =>    $email_address
//         );
//     );
 
// } else {
//     // The username already exists, so handle this accordingly...
// }

    function GetParty($atts)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;
        $atts["entityName"] = "Party";


    }