<?php

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

    function genPurchaseReport($pid = 0)
    {
        global $wpdb;

        $sql = "SELECT pu.vcrInvoiceNumber, po.vcrName, li.intQuantity,  pur.vcrJSON, pu.intValid, pu.dtmDate, pur.vcrIP
                FROM wp_mmpm_lineitem li
                JOIN wp_mmpm_purchase pu ON li.intPurchaseID = pu.intID
                JOIN wp_mmpm_purchaser pur ON pu.intPurchaserID = pur.intID
                JOIN wp_mmpm_product po ON po.intID = li.intProductID
                WHERE po.tinDeleted = 0
                ORDER BY pu.dtmDate DESC LIMIT 100";
        
        if ($pid != 0)
        {
            $sql = "SELECT pu.vcrInvoiceNumber, po.vcrName, li.intQuantity,  pur.vcrJSON, pu.intValid, pu.dtmDate, pur.vcrIP
                FROM wp_mmpm_lineitem li
                JOIN wp_mmpm_purchase pu ON li.intPurchaseID = pu.intID
                JOIN wp_mmpm_purchaser pur ON pu.intPurchaserID = pur.intID
                JOIN wp_mmpm_product po ON po.intID = li.intProductID
                WHERE po.tinDeleted = 0 AND li.intProductID = " . $pid .
                " ORDER BY pu.dtmDate DESC LIMIT 20";
        }

        $result = $wpdb->get_results($sql);
    
        $message = "";
    
        if (!$result) {
        //die("Query to show fields from table failed " . $pid);
        //Not sure why we kill this here...
        }
        else
        {
            //echo "Query Run <br />";
        }
        $fields_num = $wpdb->num_rows;
        
        $count = 0;
        
        if ($fields_num > 0)
        {   
            $message .= "<table class='table table-bordered table-striped' style=\"max-width: none !important;\"><tr style=\"font-weight: bold;\">";
            $message .= '<tr><th>Invoice</th><th>State</th><th>Name</th><th>Quant</th><th>Value</th><th>Email</th><th>Date</th><th>IP</th></tr>';
            
            setlocale(LC_MONETARY, 'en_CA');
            
            // printing table rows
            foreach ($result as $row)
            {   
                $count++;
            
                $invoice = $row->vcrInvoiceNumber;
                $name = $row->vcrName;
                $quant = $row->intQuantity;
                $state = $row->intValid;
                $data = json_decode ($row->vcrJSON, true);
                $gross = $data['mc_gross'];
                $email = $data['payer_email'];
                $date = $row->dtmDate;
                $ipaddress = $row->vcrIP;
                
                if ($email == "")
                {
                    $email = "Data Missing";
                }
                else
                {
                    $email = sprintf('<a href="mailto:%s">%s</a>', $email, $email);
                }
                
                $message .= sprintf('<tr><td>%s</td><td>%s</td></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
                                $invoice,
                                $state,
                                $name,
                                $quant,
                                money_format('%i',$gross),
                                $email,
                                $date,
                                $ipaddress);    
                $message .= "</tr>\n";
            }
            
            $message .= "</table>";
            
            if ($count == 0)
            {
                $message = "There are no results to display.";
            }
        }
        
        return $message;
    }