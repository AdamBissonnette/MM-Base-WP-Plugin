<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

    function SaveUser($atts)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;

        $json = "{}";

        $atts["entityName"] = "User";
        if ($atts["id"] == -1)
        {
            $atts["fn"] = "POST";
            $json = $curly->DoCurl($atts);
        }
        else
        {
            $atts["fn"] = "POST";
            $json = $curly->DoCurl($atts); 
        }

        return json_decode($json);
    }

    function GetUser($uid)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;

        $json = "{}";

        $atts["entityName"] = "User";

        $atts["fn"] = "GET";
        $atts["id"] = $uid;
        $json = $curly->DoCurl($atts); 

        return json_decode($json);
    }

    function GetParty($pid)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;

        $json = "{}";

        $atts["entityName"] = "Party";

        $atts["fn"] = "GET";
        $atts["id"] = $pid;
        $json = $curly->DoCurl($atts); 

        return json_decode($json);
    }

    function getEntity($atts) {

        extract( shortcode_atts( array(
            'entity' => '',
            'id' => ''
            ), $atts ) );

        global $MMM_Curl_Manager;

        $curly = $MMM_Curl_Manager->curlHandler;

        $data = array("fn" => "GET", "entityName" => $entity, "id" => $id);

        $json = json_decode($curly->DoCurl($data));

        return _output_json($json);
    }

    function KickUser($id)
    {
        $atts["entityName"] = "User";

        $atts["fn"] = "DELETE";
        $atts["id"] = $id;

        global $MMM_Curl_Manager;

        $curly = $MMM_Curl_Manager->curlHandler;
        $json = $curly->DoCurl($atts); 
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