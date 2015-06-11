<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

    function AddHuntToCompletePayments() {
        global $woocommerce;
        global $MMM_Curl_Manager;
        $productid = $this->get_setting("hunt_product");
        $curly = $MMM_Curl_Manager->curlHandler;

        $current_user_id = get_current_user_id();

        $uid = get_user_meta($current_user_id, "scav_uid", true);
        $pid = get_user_meta($current_user_id, "scav_pid", true);

        if (isset($uid) && isset($pid))
        {
            foreach( $woocommerce->cart->cart_contents as $id => $cart_item)
            {
                if( $cart_item['product_id'] == $productid ) {
                    AddHunt($pid, 1);
                    break;
                }
            }
        }
        else
        {
            //Derp
        }

    }

    add_action('woocommerce_payment_complete', 'AddHuntToCompletePayments');

    function AddHunt($party_id, $story_id)
    {
        $atts = array();
        $atts["entityName"] = "Hunt";
        $atts["fn"] = "POST";

        $atts["party"] = $party_id;
        $atts["story"] = $story_id;
        $atts["start"] = "";
        $atts["end"] = "";
        $atts["hintsUsed"] = 0;
        $atts["clue"] = null;

        $json = $curly->DoCurl($atts); 
    }

    function ValidateUser($user)
    {
        $phoneRegex = "/\+?1?\W*([2-9][0-8][0-9])\W*([2-9][0-9]{2})\W*([0-9]{4})(\se?x?t?(\d*))?/";
        $emailRegex = "/(.+@.+)/";

        $output = array("valid" => false, "message" => "Valid User");

        if ($user["name"] == "")
        {
            $output["message"] = "Have you disabled javascript?  The user's name was left blank.";
        }
        elseif (!preg_match($emailRegex, $user["email"], $emailMatches))
        {
            $output["message"] = "Have you disabled javascript?  The user's email wasn't in the right format.";
        }
        elseif (!preg_match($phoneRegex, $user["phone"], $phoneMatches))
        {
            $output["message"] = "Have you disabled javascript?  The user's phone number wasn't in the right format.";
        }
        else
        {
            $output["valid"] = true;
        }

        return $output;
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