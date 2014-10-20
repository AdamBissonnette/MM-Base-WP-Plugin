<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


    function GetQuantitySold($ProductID)
    {
        global $wpdb;
        
        $prefix = $wpdb->prefix;
        $lineitem = $prefix . "mmpm_lineitem";
        $product = $prefix . "mmpm_product";
        $purchase = $prefix . "mmpm_purchase";
        
        $query = sprintf("SELECT coalesce(sum(li.intQuantity), 0) as 'sold' FROM %s li
        JOIN %s p1 ON li.intProductID = p1.intID
        JOIN %s p2 ON li.intPurchaseID = p2.intID
        WHERE intProductID = %d AND p2.intValid = 1",
        $lineitem, $product, $purchase, $ProductID);
        
        $result = $wpdb->get_row($query);
        
        //echo $query . "<br /><br />";
        
        return $result->sold;
    }
    
    function CanSellProduct($product, $Quantity)
    {
        $CanSell = true;
        $sold = GetQuantitySold($product->intID) + $Quantity;
        
        //echo $sold . "aaa";
        
        $CanSell = IsActive($product);
        
        if (($product->intQuantity - $sold) < 0) // removed <= because we want to be able to go to 0 but not less than 0
        {
            $CanSell = false;
        }
        
        //echo $CanSell . "aaa";
        
        return $CanSell;
    }
    
    function CanSell($ProductID, $Quantity)
    {
        $product = GetProductById($ProductID);
        
        return CanSellProduct($product, $Quantity);
    }
    
    function GetProductById($pid)
    {
        global $wpdb;
        $sql = sprintf("SELECT * FROM %s WHERE tinDeleted = 0 AND intID = %s",
                $wpdb->prefix . "mmpm_product", $pid);
                
        return $wpdb->get_row($sql);
    }
    
    //Technically this gets the product from the first line item in that purchase
    //if carts are added this should get a list of products
    function GetProductByPurchaseID($pid)
    {
        global $wpdb;
        $sql = sprintf("SELECT p.* FROM %s as li JOIN %s as p ON li.intProductID = p.intID WHERE li.intPurchaseID = %s",
            $wpdb->prefix . "mmpm_lineitem", $wpdb->prefix . "mmpm_product", $pid, $curdate);
    
        return $wpdb->get_row($sql);
    }
    
    function GetProductByName($pname)
    {
        $curdate = MmmPluginToolsNamespace\getCurDate();
        //$curdate = date('Y-m-d H:i');
        
        global $wpdb;
        $sql = sprintf("SELECT * FROM %s WHERE vcrName = '%s' AND tinDeleted = 0 AND dtmEndDate > '%s' ORDER BY dtmEndDate",
                $wpdb->prefix . "mmpm_product", $pname, $curdate);
        
        return $wpdb->get_row($sql);
    }
    
    function GetProductsByDescription($pdesc)
    {
        $curdate = MmmPluginToolsNamespace\getCurDate();
        //$curdate = date('Y-m-d H:i');
    
        global $wpdb;
        $sql = sprintf("SELECT * FROM %s WHERE vcrDescription = '%s' AND tinDeleted = 0 AND dtmEndDate > '%s' ORDER BY dtmEndDate",
                $wpdb->prefix . "mmpm_product", $pdesc, $curdate);

        return $wpdb->get_results($sql);
    }
    
    function GetProducts()
    {
        global $wpdb;
        $sql = sprintf("SELECT * FROM %s WHERE tinDeleted = 0 ORDER BY dtmEndDate ASC",
                $wpdb->prefix . "mmpm_product");
        
        return $wpdb->get_results($sql);
    }
    
    function GetActiveProducts()
    {
        //$curdate = date('Y-m-d H:i');
        $curdate = \MmmToolsNamespace\DateTools::getCurDate();
        
        global $wpdb;
        $sql = sprintf("SELECT * FROM %s WHERE tinDeleted = 0 AND dtmEndDate > '%s' ORDER BY dtmEndDate ASC LIMIT 100",
                $wpdb->prefix . "mmpm_product", $curdate);
        
        //echo $sql;
        
        return $wpdb->get_results($sql);
    }
    
    function IsActive($Product)
    {
        $active = true;
        
        $curdate = \MmmToolsNamespace\DateTools::getCurDate();
        $active = \MmmToolsNamespace\DateTools::IsWithinRange($Product->dtmStartDate, $Product->dtmEndDate, $curdate);
        
        return $active;
    }
    
    function OutputProductJSON($pid)
    {
        $Product = GetProductById($pid);
        
        $active = "false";
        
        if (IsActive($Product))
        {
            $active = "true";
        }
    
        $json = sprintf('{"pid" : %s, "pname" : "%s", "pdesc" : "%s", "pquant" : %s, "psales" : %s, "pend" : "%s", "pstart" : "%s", "deleted": %s, "pnquant" : %s, "pcost" : %s, "purl" : "%s", "active" : %s}',
                 $Product->intID,
                 $Product->vcrName,
                 $Product->vcrDescription,
                 $Product->intQuantity,
                 GetQuantitySold($Product->intID),
                 $Product->dtmEndDate,
                 $Product->dtmStartDate,
                 $Product->tinDeleted,
                 $Product->intNotifyQuantity,
                 $Product->decPrice,
                 $Product->vcrUrl,
                 $active);
        
        echo $json;
    }
    
    function OutputProductList()
    {
        $Products = GetActiveProducts();
        $output = "";

            ob_start(); //Since there isn't a nice way to get this content we have to use the output buffer

        if ($Products)
        {
        
    ?>
        
        <table id="mm_pm_productlist" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Sold</th>
                <th>End Date</th>
                <th>Controls</th>
            </tr>
        </thead>
        <tbody>
                                
    <?php
            foreach ($Products as $Product)
            {
                $output = "";
                
                if (IsActive($Product))
                {
                    $output .= sprintf("<tr id=\"row-%s\" class=\"active\">
                        <td>
                            <a href=\"#\" title=\"Active\"><i class=\"icon-ok\"></i></a>
                        </td>", $Product->intID);
                }
                else
                {
                    $output .= sprintf("<tr id=\"row-%s\" class=\"inactive\" style=\"display: none;\">
                        <td>
                            <a href=\"#\" title=\"Inactive\"><i class=\"icon-remove\"></i></a>
                        </td>", $Product->intID);
                }
                $output .= sprintf("<td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>
                        <a href=\"#\" class=\"btnProductEdit\" id=\"product-edit-1\" onclick=\"javascript: EditProduct(%s);\" title=\"Edit\"><i class=\"icon-edit\"></i>Edit</a>
                        <a href=\"#\" class=\"btnProductCopy\" id=\"product-copy-1\" onclick=\"javascript: CopyProduct(%s);\" title=\"Copy\"><i class=\"icon-file\"></i>Copy</a>
                        <a href=\"#\" class=\"btnProductDelete\" id=\"product-delete-1\" onclick=\"javascript: DeleteProduct(%s);\" title=\"Delete\"><i class=\"icon-trash\"></i>Delete</a>
                        <a href=\"#\" class=\"btnFillClass\" onclick=\"javascript: FillClass(%s);\" title=\"Fill\"><i class=\"icon-tint\"></i>Fill</a>
                    </td>
                </tr>",
                $Product->vcrName,
                $Product->vcrDescription,
                $Product->intQuantity,
                GetQuantitySold($Product->intID),
                $Product->dtmEndDate,
                $Product->intID,
                $Product->intID,
                $Product->intID,
                $Product->intID);
                
                echo $output;
            }
            
            echo "</tbody></table>";
        }
        else
        {
            echo "Looks like you haven't added any Classes yet.";
        }

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
    
    function InsertProduct($Name, $Desc, $Price, $Max, $Notify, $Start, $End, $Url)
    {
        global $wpdb;
        $defaultDeleted = 0;
        
        $CalDate = date("Y-m-d", strtotime($End));
        
        $input = array('user_id'        => 1,
                  'title'           => $Desc,
                  'start'           => $CalDate,
                  'end'             => $CalDate,
                  'category_id'     => 1,
                  'description'     => $Desc,
                  'link'            => $Url
                );

        $eid = Ajax_Calendar_Insert(array_to_object($input));
        
        $array = array(
                    'vcrName' => $Name,
                    'vcrDescription' => $Desc,
                    'decPrice' => $Price,
                    'intQuantity' => $Max,
                    'intNotifyQuantity' => $Notify,
                    'dtmStartDate' => $Start,
                    'dtmEndDate' => $End,
                    'vcrUrl' => $Url,
                    'intExternalID' => $eid//,
                    //'bitDeleted' => $defaultDeleted
                );
        $format = array(
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s'//,
                    //'%d'
                );

        return WPInsertStatement($wpdb->prefix . "mmpm_product", $array, $format);
    }
    
    function UpdateProduct($id, $Name, $Desc, $Price, $Max, $Notify, $Start, $End, $Url)
    {
        global $wpdb;
        $sql = sprintf("UPDATE %s SET vcrName = '%s',
                                vcrDescription = '%s',
                                decPrice = %d,
                                intQuantity = %d,
                                intNotifyQuantity = %d,
                                dtmStartDate = '%s',
                                dtmEndDate = '%s',
                                vcrUrl = '%s'
                                WHERE intID = %d",
                                $wpdb->prefix . "mmpm_product",
                                $Name, $Desc, $Price, $Max, $Notify, $Start, $End, $Url, $id);
        
        $CalDate = date("Y-m-d", strtotime($End));
        
        $input = array('id'         => $id,
                  'user_id'         => 1,
                  'title'           => $Desc,
                  'start'           => $CalDate,
                  'end'             => $CalDate,
                  'category_id'     => 1,
                  'description'     => $Desc,
                  'link'            => $Url
                );

        Ajax_Calendar_Update(array_to_object($input));
                                
        WPExecuteStatement($sql);
    }
    
    function DeleteProduct($id)
    {
        $product = GetProductById($id);
        
        Ajax_Calendar_Delete($product->intExternalID);
        
        global $wpdb;
        $sql = sprintf("UPDATE %s SET tinDeleted = 1
                                WHERE intID = %s",
                                $wpdb->prefix . "mmpm_product",
                                $id);
        WPExecuteStatement($sql);
    }
    
    function FinishProduct($id)
    {
        $sold = GetQuantitySold($id);
    
        global $wpdb;
        $sql = sprintf("UPDATE %s SET intQuantity = %s
                                WHERE intID = %s",
                                $wpdb->prefix . "mmpm_product",
                                $sold,
                                $id);
        
        $Product = GetProductById($id);
        UpdateCalendarName($Product);
        
        
        WPExecuteStatement($sql);
    }

    function SelectPurchaseByInvoiceID($invoiceid)
    {
        global $wpdb;
        $sql = sprintf("SELECT * FROM %s WHERE vcrInvoiceNumber = '%s'",
                $wpdb->prefix . "mmpm_purchase", $invoiceid);
        
        return $wpdb->get_row($sql);
    }
    
    function UpdatePurchase($intID, $value)
    {
        global $wpdb;
        $statement = sprintf("UPDATE %s SET intValid = %d WHERE intID = %d",
                        $wpdb->prefix . "mmpm_purchase", $value, $intID);
    
        //echo $statement;
    
        WPExecuteStatement($statement);
    }
    
    function UpdatePurchaser($intID, $json)
    {
        global $wpdb;
        $statement = sprintf("UPDATE %s SET vcrJSON = '%s' WHERE intID = %d",
                                $wpdb->prefix . "mmpm_purchaser", $json, $intID);
    
        WPExecuteStatement($statement);
    }

    function InsertPurchase($ProductID, $Quantity, $InvoicePrefix)
    {
        global $wpdb;
        
        $PurchaserID = InsertPurchaser();
        
        $InvoiceID = $InvoicePrefix . (10000 + $PurchaserID);
        
        //echo $InvoiceID . " " . $PurchaserID;
        
        $curdate = MmmPluginToolsNamespace\DateTools::getCurDate();

        //$curdate = date('Y-m-d H:i');
        
        $array = array(
                    'intPurchaserID' => $PurchaserID,
                    'vcrInvoiceNumber' => $InvoiceID,
                    'dtmDate' => $curdate
                );
        
        $format = array(
                    '%d',
                    '%s',
                    '%s'
                );
        
        $PurchaseID = WPInsertStatement($wpdb->prefix . "mmpm_purchase", $array, $format);
        
        $array = array(
                    'intPurchaseID' => $PurchaseID,
                    'intProductID' => $ProductID,
                    'intQuantity' => $Quantity
                );
        
        $format = array(
                    '%d',
                    '%d',
                    '%d'
                );
        
        WPInsertStatement($wpdb->prefix . "mmpm_lineitem", $array, $format);
        
        return $InvoiceID;
    }
    
    function InsertPurchaser()
    {
        global $wpdb;
        
        $array = array(
                    'vcrIP' => $_SERVER['REMOTE_ADDR'],
                    'vcrAgent' => $_SERVER['HTTP_USER_AGENT']
                );
        
        $format = array(
                    '%s',
                    '%s'
                );
        
        return WPInsertStatement($wpdb->prefix . "mmpm_purchaser", $array, $format);
    }