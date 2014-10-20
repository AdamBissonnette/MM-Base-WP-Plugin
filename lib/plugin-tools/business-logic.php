<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


    function HandleIPN($invoiceid, $value, $json, $pemail)
    {
        $purchase = SelectPurchaseByInvoiceID($invoiceid); 

        if ($purchase->intID > 0)
        {
            UpdatePurchase($purchase->intID, $value); //Should be adding the json to purchase here
        }
        
        $purchaserid = $purchase->intPurchaserID;

        switch($value){
            case 1:
                UpdatePurchaser($purchaserid, $json); //JSON here should be for addtional buyer info
                
                $_settings = get_option('mm_pm_settings') ? get_option('mm_pm_settings') : array();
                $message = "Thank you for your purchase.<br /><br />
                    I will send you a reminder and further information nearer to the class if anything comes up.<br /><br />
                    Check out more information on our cancellation policy and what to bring to your class <a href=\"http://www.simonsfinefoods.com/cooking-classes/\">here</a>.<br /><br />
                    If you have a question regarding the class please e-mail me at: simon@simonsfinefoods.com<br /><br />
                    - Simon";
                    
                SendEmail($pemail,
                    "Registration Confirmation",
                    $_settings['mm_pm_notifyemail'],
                    $message);
                    
                //Added Send Reminders to notify admins of sold out or almost sold out content
                $Product = GetProductByPurchaseID($purchase->intID);
                SendReminders($Product);
            break;
            case 4: //Refund
                UpdatePurchaser($purchaserid, $json);
            break;
            default:
                //Derp
            break;
        }
    }

    function Buy($ProductName, $Quantity)
    {
        $Product = GetProductByName($ProductName);
        $sold = GetQuantitySold($Product->intID) + $Quantity;
        $remaining = $Product->intQuantity - $sold;
        //Check if the Quantity selected can be bought
        //echo $remaining;
        
        if ($remaining >= 0 && IsActive($Product))
        {
            $_settings = get_option('mm_pm_settings') ? get_option('mm_pm_settings') : array();
        
            $Account = $_settings['mm_pm_paypalaccount'];
            $Currency = $_settings['mm_pm_currency'];
            $InvoicePrefix = $_settings['mm_pm_invoice'] . "-";
            $TaxPercent = $_settings['mm_pm_tax'] / 100;
        
            //Removed Code for sending Reminders
            
            $InvoiceNumber = InsertPurchase($Product->intID, $Quantity, $InvoicePrefix);
            $Total = $Product->decPrice * $Quantity;
            $Tax = round($Total * $TaxPercent, 2);
            
            echo OutputPurchaseJSON($Total, $Tax, $InvoiceID, $ProductName, $Account, $Currency, $InvoiceNumber, $Quantity);
        }
        else
        {
            OutputFailureJSON();
        }
    }
    
    function SendReminders($Product)
    {
        $sold = GetQuantitySold($Product->intID);
        $remaining = $Product->intQuantity - $sold;
    
        if ($remaining <= $Product->intNotifyQuantity)
        {
            if ($remaining == 0)
            {
                //Change calendar name
                UpdateCalendarName($Product);
                
                $message = sprintf("Hey there!<br /><br /> Product: '%s' is sold out.  Here is the list of sales:<br /><br />
                %s<br /><br />
                Sincerely,<br />The Media Manifesto Team",
                $Product->vcrName,
                genPurchaseReport($Product->intID));
            }
            else
            {               
                //Send Notification Email
                $message = sprintf("Hey there!<br /><br/> This is just a friendly reminder that this product: '%s' is selling out.
                There are only %d left of %d which means you've sold %d.  Here is the list of sales:<br />%s<br /><br />
                Have a Great Day !!<br /><br /> Sincerely,<br />The Media Manifesto Team",
                        $Product->vcrName,
                        $remaining,
                        $Product->intQuantity,
                        $sold,
                        genPurchaseReport($Product->intID));
            }
            
            SendEmail($_settings['mm_pm_notifyemail'] . ', adam@mediamanifesto.com',
                "Product Notification",
                "info@mediamanifesto.com",
                $message);
        }
    }

    function OutputFailureJSON()
    {
        $g = "false";
        $gm = "The selected quantity is unavailable.";
        
        $output = sprintf('{"g" : %s, "gm" : "%s"}',
        $g, $gm);
        
        echo $output;
    }
    
    function OutputPurchaseJSON($Total, $Tax, $Invoice, $Name, $Account, $Currency, $Invoice, $Quantity)
    {
        $IPNUrl =  get_bloginfo( 'url' ) . '/mm_ipn/paypal';
        
        $html = sprintf("<input type='hidden' name='amount_1' value='%s' />", $Total);
        $html .= sprintf("<input type='hidden' name='cmd' value='_cart' />");       
        $html .= sprintf("<input type='hidden' name='upload' value='1' />");
        $html .= sprintf("<input type='hidden' name='business' value='%s' />", $Account);
        $html .= sprintf("<input type='hidden' name='item_name_1' value='%s x %s' />", $Quantity, $Name);
        //$html .= sprintf("<input type='hidden' name='amount_2' value='%s' />", $Tax);
        $html .= sprintf("<input type='hidden' name='tax_1' value='%s' />", $Tax);  //override account tax settings to 0
        //$html .= sprintf("<input type='hidden' name='item_name_2' value='Tax' />");
        $html .= sprintf("<input type='hidden' name='invoice' value='%s' />", $Invoice);
        $html .= sprintf("<input type='hidden' name='notify_url' value='%s' />", $IPNUrl);
        $html .= sprintf("<input type='hidden' name='currency_code' value='%s' />", $Currency);
        $html .= sprintf("<input id='mmsubmit-%s' type='submit' style='display: none;' />", $Name);
        
        $action = "https://www.paypal.com/cgi-bin/webscr";
        $form = "#mmform-" . $Name;
        $attr = "#mmattr-" . $Name;
        $submit = "#mmsubmit-" . $Name;
        $g = "true";
        
        $output = sprintf('{"html" : "%s", "form" : "%s", "action" : "%s", "attr" : "%s", "submit" : "%s", "g" : %s}',
        $html, $form, $action, $attr, $submit, $g);
        
        return $output;
    }