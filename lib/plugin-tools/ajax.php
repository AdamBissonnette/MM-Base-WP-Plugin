<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


function non_admin_ajax($manager)
{
    //If you're not an authorized user you can only buy products
    switch($_REQUEST['fn']){
        case 'buy':
            $data_back = json_decode (stripslashes($_REQUEST['buy']), true);
            
            $name = $data_back['info'][0]['code'];
            $quantity = $data_back['info'][0]['quant'];
            
            Buy($name, $quantity); //Outputs JSON
        break;
        case 'calid':
            try {
                $data_back = json_decode (stripslashes($_REQUEST['calid']), true);
                echo GetCalendarUrl($_REQUEST['calid']['id']); //Outputs string url
            } catch(Exception $e)
            {
                echo "NotImplemented.jpg";
            }
        break;
    }
}

function admin_ajax($manager)
{
    switch($_REQUEST['fn']){
        case 'delete':
            $data_back = json_decode (stripslashes($_REQUEST['delete']), true);
            $id = $data_back['info'][0]['Pid'];
            DeleteProduct($id);
        break;
        case 'fill':
            $data_back = json_decode (stripslashes($_REQUEST['fill']), true);
            $id = $data_back['info'][0]['Pid'];
            FinishProduct($id);
        break;
        case 'get':
            $data_back = json_decode (stripslashes($_REQUEST['get']), true);
            $pid = $data_back['info'][0]['Pid'];
            OutputProductJSON($pid);
        break;
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