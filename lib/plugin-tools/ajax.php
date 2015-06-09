<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


function non_admin_ajax($manager)
{
    scavajax();
}

function admin_ajax($manager)
{
    //var_dump($_REQUEST);

    switch($_REQUEST['fn']){
        case 'settings':
            $data_back = $_REQUEST['settings'];
            
            $values = array();

            foreach ($data_back as $item) {
                $values[$item["name"]] = $item["value"];
            }
            
            $manager->_save_settings_todb($values);
            echo 0;
        break;
        default:
            //Derp
        break;
    }

    scavajax();

    die;
}

function scavajax()
{
    header('Content-type: application/json');
    $output = array('data' => array('message' => "Things happened!" ) );
    if ( \is_user_logged_in() )  //Update values
    {
        $current_user = \wp_get_current_user();

        //If you're not an authorized user you can only buy products
        switch($_REQUEST['fn']){
            case 'delete': //they can only delete party members
                $data_back = $_REQUEST['data'];
                $id = $data_back['id'];

                KickUser($id);
                $output = "";
            break;
            case 'registration':
                $data_back = $_REQUEST['data'];
                $data = array('id' => -1, 'name' => $data_back[1]["value"], 'email' => $data_back[2]["value"], 'phone' => $data_back[3]["value"], 'party_name' => $data_back[0]["value"]);

                $uid = get_user_meta($current_user->ID, "scav_uid", true);
                $pid = get_user_meta($current_user->ID, "scav_pid", true);

                if (isset($uid))
                {
                    $data["id"] = $uid;
                }

                if (isset($pid))
                {
                    $data["party"] = $pid;
                }
// object(stdClass)#113 (6) {
//   ["id"]=>
//   int(19)
//   ["name"]=>
//   string(10) "Derpletter"
//   ["email"]=>
//   string(15) "adasd!@ADSasdas"
//   ["phone"]=>
//   string(12) "+13069921212"
//   ["date"]=>
//   int(1433883310000)
//   ["party"]=>
//   object(stdClass)#114 (3) {
//     ["id"]=>
//     int(24)
//     ["name"]=>
//     string(11) "Taco Shells"
//     ["users"]=>
//     string(0) ""
//   }
// }
                $json = SaveUser($data);
                // update_user_meta($current_user->ID, "scav_uid", $json->id);
                // update_user_meta($current_user->ID, "scav_pid", $json->party->id);
                $output["data"]["message"] = "Your information has been updated!";
                $output["data"]["refresh"] = false;
            break;
            case 'post': //Add new party member
                $data_back = $_REQUEST['data'];
                $data = array('id' => -1, 'name' => $data_back[0]["value"], 'email' => $data_back[1]["value"], 'phone' => $data_back[2]["value"]);

                $pid = get_user_meta($current_user->ID, "scav_pid", true);
                $data["party"] = $pid;

                $output = SaveUser($data);
            break;
            case 'get': //Party
                $uid = get_user_meta($current_user->ID, "scav_uid", true);
                $pid = get_user_meta($current_user->ID, "scav_pid", true);
                $output = array("html" => genPartyTable(GetParty($pid), $uid));
            break;
        }

    }
    else //Add new values
    {
        //If you're not an authorized user you can only buy products
        switch($_REQUEST['fn']){
            case 'registration':
                $data_back = $_REQUEST['data'];
                //create new user
                $data = array('id' => -1, 'name' => $data_back[1]["value"], 'email' => $data_back[2]["value"], 'phone' => $data_back[3]["value"], 'party_name' => $data_back[0]["value"]);
                $json = SaveUser($data);

                $email_address = $data_back[2]["value"];
                $password = wp_generate_password( 12, false );
                $user_id = wp_create_user( $email_address, $password, $email_address );
             
                wp_update_user(
                    array(
                        'ID'          =>    $user_id,
                        'nickname'    =>    $email_address
                    )
                );

                update_user_meta($user_id, "scav_uid", $json->id);
                update_user_meta($user_id, "scav_pid", $json->party->id);

                $user = wp_signon(array("user_login"=>$email_address, "user_password"=>$password), false);

                if (is_wp_error($user))
                {
                    $output["data"]["message"] = $user->get_error_message();
                }

                $output["data"]["refresh"] = true;
            break;
        }
    }
 
 echo json_encode($output);
}


?>