<?php
    namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


    function genPurchaseReport($transactionType = 0, $limit = 100, $pid = 0)
    {
        global $wpdb;

        $sql = "SELECT pu.vcrInvoiceNumber, po.vcrName, li.intQuantity,  pur.vcrJSON, pu.intValid, pu.dtmDate, pur.vcrIP
                FROM wp_mmpm_lineitem li
                JOIN wp_mmpm_purchase pu ON li.intPurchaseID = pu.intID
                JOIN wp_mmpm_purchaser pur ON pu.intPurchaserID = pur.intID
                JOIN wp_mmpm_product po ON po.intID = li.intProductID
                WHERE po.tinDeleted = 0";

        if ($transactionType != 0) //1 == Valid, 2 == Pending, 3 == Invalid
        {
            $sql .= sprintf(" AND pu.intValid = %s", $transactionType);
        }

        if ($pid != 0)
        {
            $sql .= sprintf(" AND li.intProductID = %s", $pid);
        }

        $sql .= sprintf(" ORDER BY pu.dtmDate DESC LIMIT %s", $limit);

        $result = $wpdb->get_results($sql);
    
        $message = "";
    
        if (!$result) {
        //die("Query to show fields from table failed " . $pid);
        //Not sure why we kill this here...
            $message = "There are no purchases in the system at this point to display.";
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
?>