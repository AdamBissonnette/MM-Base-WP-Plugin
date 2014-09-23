<?php
namespace MmmPluginToolsNamespace;

function non_admin_ajax()
{
    if (!$this->check_user_capability())
    {
        //If you're not an authorized user you can only do the following
        //Derp
    }
}

function admin_ajax()
{
    if ($this->check_user_capability())
    {
        switch($_REQUEST['fn']){
            case 'settings':
                $data_back = $_REQUEST['settings'];
                    
                $values = array();
                $i = 0;
                foreach ($data_back as $data)
                {
                    if (array_key_exists($data['name'], $values))
                    {
                        $values[$data['name']] .= "," . $data['value']; 
                    }
                    else
                    {
                        $values[$data['name']] = $data['value'];
                    }
                }
                
                $this->_save_settings_todb($values);
            break;
            default:
                //Derp
            break;
        }
    }

    die;
}


?>