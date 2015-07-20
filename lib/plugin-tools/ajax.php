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
                    $output->message = "That user could not be kicked.  Try refreshing?";
                    $output->state = false;
                }
            break;
            case 'registration':
                $output = _register($output, true);
            break;
            case 'post': //Add new party member
                $data_back = $_REQUEST['data'];
                $data = array('id' => -1, 'name' => $data_back[0]["value"], 'email' => $data_back[1]["value"], 'phone' => $data_back[2]["value"]);

                $pid = get_user_meta($current_user->ID, "scav_pid", true);
                $party = GetParty($pid);

                if (isset($party))
                {
                    if (isset($party->data))
                    {
                        $party = ($party->data);
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
                            $json = SaveUser($data);

                            if (isset($json))
                            {
                                //var_dump($json);
                                if ($json->data == "")
                                {
                                    $output->message = $json->value;
                                    $output->state = false;
                                }                        
                            }
                            else
                            {
                                $output->html = $usercount;
                            }
                        }
                    }
                    else
                    {
                        $output->message = "An error has occured.  Please try this again later or contact an administrator.";
                        $output->state = false;
                    }
                }

            break;
            case 'get': //Party
                $uid = get_user_meta($current_user->ID, "scav_uid", true);
                $pid = get_user_meta($current_user->ID, "scav_pid", true);
                $party = GetParty($pid);
                $output->html = genPartyTable(($party->data), $uid);
            break;
        }

    }
    else //Add new values
    {
        switch($_REQUEST['fn']){
            case 'registration':
                $output = _register($output, false);
            break;
        }
    }
 
 echo json_encode($output);
 die(); 
}

function _register($output, $is_logged_in)
{
    $data_back = $_REQUEST['data'];
    //create new user
    $data = array('id' => -1, 'name' => $data_back[1]["value"], 'email' => $data_back[2]["value"], 'phone' => $data_back[3]["value"], 'party_name' => $data_back[0]["value"]);

    $validationResult = ValidateUser($data);

    if ($validationResult["valid"])
    {
        $output->message = "Your information has been updated!";

        if ($is_logged_in)
        {
            $output = _register_existing($output, $data_back, $data);
        }
        else
        {
            $output = _register_new($output, $data_back, $data);
        }
    }
    else
    {
        $output->message = $validationResult["message"];
        $output->state = false;
    }

    return $output;
}

function _register_new($output, $data_back, $data)
{
    $json = SaveUser($data);

    if (isset($json->data))
    {
        $data = ($json->data);

        //Ensure the data is in the correct format                            
        $email_address = $data_back[2]["value"];
        $password = wp_generate_password( 12, false );
        $user_id = wp_create_user( $email_address, $password, $email_address );
     
        wp_update_user(
            array(
                'ID'          =>    $user_id,
                'nickname'    =>    $email_address
            )
        );

        $user = wp_signon(array("user_login"=>$email_address, "user_password"=>$password), false);

        if (is_wp_error($user))
        {
            $output->message = $user->get_error_message();
            $output->state = false;
        }
        else
        {
            update_user_meta($user_id, "scav_uid", $data->id);
            update_user_meta($user_id, "scav_pid", $data->party->id);
        }

        $output->refresh = true;

    }
    else
    {
        $output->message = $json->message;
        $output->state = false;
    }

    return $output;
}

function _register_existing($output, $data_back, $data)
{
    $current_user = \wp_get_current_user();
    $uid = get_user_meta($current_user->ID, "scav_uid", true);
    $pid = get_user_meta($current_user->ID, "scav_pid", true);
    $has_meta = true;

    if ($uid == "" && $pid == "")
    {
        $has_meta = false;
    }
    else
    {
        $data["id"] = $uid;
        $data["party"] = $pid;
    }

    $json = SaveUser($data);
    
    if (isset($json))
    {
        if (isset($json->data))
        {
            $data = ($json->data);

            if (!$has_meta)
            {
                update_user_meta($user_id, "scav_uid", $data->id);
                update_user_meta($user_id, "scav_pid", $data->party->id);
            }
        }
        else
        {
            $output->message = $json->message;
            $output->state = false;
        }
    }
    else
    {
        $output->message = $json->message;
        $output->state = false;
    }

    return $output;
}

?>