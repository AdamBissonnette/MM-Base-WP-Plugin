<?php
namespace MmmPluginToolsNamespace;

function non_admin_ajax()
{
    if (!$this->check_user_capability())
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
}

function admin_ajax()
{
    if ($this->check_user_capability())
    {
        switch($_REQUEST['fn']){
            case 'buy':
                $data_back = json_decode (stripslashes($_REQUEST['buy']), true);
                
                $name = $data_back['info'][0]['code'];
                $quantity = $data_back['info'][0]['quant'];
                
                Buy($name, $quantity); //Outputs JSON
            break;
            case 'product':
                
                $data_back = json_decode (stripslashes($_REQUEST['product']), true);
                $pid =  $data_back['info'][0]['pid'];
                $name = $data_back['info'][0]['name'];
                $desc = $data_back['info'][0]['desc'];
                $price = $data_back['info'][0]['price'];
                $max = $data_back['info'][0]['max'];
                $notify = $data_back['info'][0]['notify'];
                $start = $data_back['info'][0]['start'];
                $end = $data_back['info'][0]['end'];
                $url = $data_back['info'][0]['url'];
                
                if ($pid != -1)
                {
                    //do update
                    UpdateProduct($pid, $name, $desc, $price, $max, $notify, $start, $end, $url);
                }
                else
                {
                    $pid = InsertProduct($name, $desc, $price, $max, $notify, $start, $end, $url);
                }
                
                OutputProductJSON($pid);
            break;
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
                $data_back = json_decode (stripslashes($_REQUEST['settings']), true);
                
                $values = array(
                    'mm_pm_paypalaccount' => $data_back['info'][0]['paypal'],
                    'mm_pm_notifyemail' => $data_back['info'][0]['nemail'],
                    'mm_pm_notifyquantity' => $data_back['info'][0]['nquant'],
                    'mm_pm_invoice' => $data_back['info'][0]['invoice'],
                    'mm_pm_tax' => $data_back['info'][0]['tax'],
                    'mm_pm_currency' => $data_back['info'][0]['currency'],
                    'mm_pm_empty' => $data_back['info'][0]['empty'],
                    'mm_pm_footer' => $data_back['info'][0]['footer'],
                );
                
                $this->_save_settings_todb($values);
            break;
            case 'calid':
                try
                {
                    $data_back = json_decode (stripslashes($_REQUEST['calid']), true);
                    echo GetCalendarUrl($_REQUEST['calid']['id']); //Outputs string url
                } catch(Exception $e)
                {
                    echo "NotImplemented.jpg";
                }
            break;
            /* case 'testvalidate':
                $invoiceid = $_REQUEST['testbuy']['id'];
                $value = 1;
                $json = "";
                $pemail = "adam@mediamanifesto.com";
                
                HandleIPN($invoiceid, $value, $json, $pemail);
            break; */
            default:
                //Derp
            break;
        }
    }

    die;
}


?>