<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


function non_admin_ajax($manager)
{
    //If you're not an authorized user you can only buy products
    switch($_REQUEST['fn']){
        case 'delete': //they can only delete party members
            $data_back = $_REQUEST['data'];
            $id = $data_back['info'][0]['Pid'];
        break;
        case 'post':
            if (isset($_REQUEST['registration']))
            {
                $data_back = $_REQUEST['registration'];
            }
            elseif ($_REQUEST['data']) {
                $data_back = $_REQUEST['data'];
            }

            $id = $data_back['info'][0]['Pid'];
        break;
        case 'get':
            $data_back = $_REQUEST['data'];
            $pid = $data_back['info'][0]['Pid'];
        break;
    }
}

function admin_ajax($manager)
{
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

    die;
}


?>