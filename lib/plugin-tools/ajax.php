<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

class AjaxResponse implements \JsonSerializable
{
    var $message = "";
    var $html = "";
    var $refresh = false;
    var $state = true;

    function __construct($message)
    {
        $this->message = $message;
    }

    public function jsonSerialize()
    {
        return array("data" => array(
            'message' => $this->message,
            'html' => $this->html,
            'refresh' => $this->refresh,
            'state' => $this->state
        ));
    }
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
            echo json_encode(0);
            die;
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
    $output = new AjaxResponse("Data received.  All is well.");
    if ( \is_user_logged_in() )  //Update values
    {
        $current_user = \wp_get_current_user();

        //If you're not an authorized user you can only buy products
        switch($_REQUEST['fn']){
            case 'delete':
                $data_back = $_REQUEST['data'];
                $id = $data_back['id'];

                $uid = get_user_meta($current_user->ID, "scav_uid", true);
                $pid = get_user_meta($current_user->ID, "scav_pid", true);

                $party = GetParty($pid);
                $inParty = false;

                //they can only delete party members
                //so here we check to make sure they're deleting a party member
                foreach ($party->users as $user) {
                    if ($user->id == $id)
                    {
                        $inParty = true;
                        break;
                    }
                }

                if ($id != $uid && $inParty) //Make sure they don't delete themselves or someone who isn't in their party
                {
                    KickUser($id);
                    $output->message = "The user was kicked succesfully";
                }
                else
                {
                    $output->message = "That user could not be kicked.";
                    $output->state = false;
                }
            break;
            case 'registration':
                $data_back = $_REQUEST['data'];
                $data = array('id' => -1, 'name' => $data_back[1]["value"], 'email' => $data_back[2]["value"], 'phone' => $data_back[3]["value"], 'party_name' => $data_back[0]["value"]);

                $uid = get_user_meta($current_user->ID, "scav_uid", true);
                $pid = get_user_meta($current_user->ID, "scav_pid", true);

                //Validate and format the phone number
                $validationResult = ValidateUser($data);

                if ($validationResult["valid"])
                {
                    if ($uid != "")
                    {
                        $data["id"] = $uid;

                        if ($pid != "")
                        {
                            $data["party"] = $pid;
                            $json = SaveUser($data);
                        }
                    }
                    else
                    {
                        $json = SaveUser($data);
                        update_user_meta($current_user->ID, "scav_uid", $json->id);
                        update_user_meta($current_user->ID, "scav_pid", $json->party->id);
                        $output->refresh = true;
                    }
                    
                    $output->message = "Your information has been updated!";
                }
                else
                {
                    $output->message = $validationResult["message"];
                    $output->state = false;
                }
            break;
            case 'post': //Add new party member
                $data_back = $_REQUEST['data'];
                $data = array('id' => -1, 'name' => $data_back[0]["value"], 'email' => $data_back[1]["value"], 'phone' => $data_back[2]["value"]);

                $pid = get_user_meta($current_user->ID, "scav_pid", true);
                $party = GetParty($pid);

                if (isset($party))
                {
                    $data["party"] = $pid;

                    $usercount = 0;

                    foreach ($party->users as $user) {
                        $usercount++;
                    }

                    if ($usercount > 4) //allow up to 5 party members
                    {
                        $output->message = "You can only have up to 5 party members.";
                        $output->state = false;                        
                    }
                    else
                    {
                        SaveUser($data);
                        $output->html = $usercount;
                    }
                }

            break;
            case 'get': //Party
                $uid = get_user_meta($current_user->ID, "scav_uid", true);
                $pid = get_user_meta($current_user->ID, "scav_pid", true);
                $output->html = genPartyTable(GetParty($pid), $uid);
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
             
                $validationResult = ValidateUser($data);

                if ($validationResult["valid"])
                {
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
                        $output->message = $user->get_error_message();
                        $output->state = false;
                    }

                    $output->refresh = true;
                }
                else
                {
                    $output->message = $validationResult["message"];
                    $output->state = false;
                }
            break;
        }
    }
 
 echo json_encode($output);
}


?>