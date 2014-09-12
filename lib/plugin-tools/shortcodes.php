<?php
namespace MmmPluginToolsNamespace;

function MMProductGroup($atts, $content = null)
{
    //add_action( 'wp_print_scripts', array(&$this, 'plugin_js') );
    extract( shortcode_atts( array(
        'description' => 'null'
    ), $atts ) );
    
    $Products = GetProductsByDescription($description);
    
    $output = "<div class=\"mm_wrapper\"><div class=\"group-header\">Register for Classes</div>";
    
    if ($Products)
    {
        $output .= "<div class=\"mm-faux-table\">";
        $output .= "<div class=\"mm-hrow\"><div class=\"mm-inline mm-small\">Date</div><div class=\"mm-inline mm-large\">Cost</div><div class=\"mm-inline\">Quantity</div><div class=\"mm-inline\">Subtotal</div><div class=\"mm-inline mm-large\">Register via Paypal</div></div>";

        $emptyCount = 0;
        foreach ($Products as $Product)
        {
            $row = $this->GenerateProductRow($Product);
            
            if ($row == "")
            {
                $emptyCount++;
            }
            else
            {
                $output .= $row;
            }
        }
        
        if ($emptyCount == count($Products))
        {
            $output .= "<p>" . urldecode($this->_settings['mm_pm_empty']) . "</p>";
        }
        
        $output .= "</div>";
        $output .= '<p class="note">' . urldecode($this->_settings['mm_pm_footer']) . '</p>';
    }
    else
    {
        $output .= "<p>" . urldecode($this->_settings['mm_pm_empty']) . "</p>";
    }
    
    $output .= "</div>";
    
    return $output;
}

function MMProduct($atts)
{
    //add_action( 'wp_print_scripts', array(&$this, 'plugin_js') );
    extract( shortcode_atts( array(
        'code' => 'null'
    ), $atts ) );
    
    $product = GetProductByName($code);
    
    if (CanSellProduct($product, 1))
    {       
        $url = absolute_to_relative_url(admin_url() . "admin-ajax.php");
        $eDate = strtotime($product->dtmEndDate);
        $EndDate = date("M d", $eDate);
        $Price = $product->decPrice;
        $HiddenPrice = sprintf("<input type=\"hidden\" id=\"mmprice-%s\" value=\"%d\" />", $product->vcrName, $Price);
        $QuantityInput = sprintf("<div class=\"mm-input\"><input onkeyup=\"javascript: updateSubtotal('%s');\" maxlength=\"1\" id=\"mmquant-%s\" type=\"text\" class=\"small nonzero req num\" value=\"0\" /></div>",
                            $product->vcrName, $product->vcrName);
        $Subtotal = sprintf("<input id=\"mmsubtotal-%s\" type\"text\" class=\"small\" disabled=\"disabled\" />", $product->vcrName);
        $Action = sprintf("<a href=\"javascript: void(0);\" class=\"buy btn\" onclick=\"javascript: CheckScripts(); doBuy('%s', '%s');\">Register</a></div><div id=\"mmattr-%s\">",
        $product->vcrName, str_replace ("\/", "\/\/", $url),  $product->vcrName);
        
        $output .= sprintf("<form method=\"post\" id=\"mmform-%s\">", $product->vcrName);
        $output .= sprintf("<div class=\"mm-row\">%s<div class=\"mm-inline mm-small\">%s</div><div class=\"mm-inline mm-large\">$%s</div><div class=\"mm-inline\">%s</div><div class=\"mm-inline\">%s</div><div class=\"mm-inline mm-large\">%s</div></div>",
                            $HiddenPrice, $EndDate, $Price, $QuantityInput, $Subtotal, $Action);
        $output .= "</form>";
    }
    
    return $this->GenerateProductRow($product);
}

function GenerateProductRow($product)
{
    $output = "";

    if (CanSellProduct($product, 1))
    {       
        $url = absolute_to_relative_url(admin_url() . "admin-ajax.php");
        $eDate = strtotime($product->dtmEndDate);
        $EndDate = date("M d", $eDate);
        $Price = $product->decPrice;
        $HiddenPrice = sprintf("<input type=\"hidden\" id=\"mmprice-%s\" value=\"%d\" />", $product->vcrName, $Price);
        $QuantityInput = sprintf("<div class=\"mm-input\"><input onkeyup=\"javascript: updateSubtotal('%s');\" maxlength=\"1\" id=\"mmquant-%s\" type=\"text\" class=\"small nonzero req num\" value=\"0\" /></div>",
                            $product->vcrName, $product->vcrName);
        $Subtotal = sprintf("<input id=\"mmsubtotal-%s\" type\"text\" class=\"small\" disabled=\"disabled\" />", $product->vcrName);
        $Action = sprintf("<a href=\"javascript: void(0);\" class=\"buy btn\" onclick=\"javascript: CheckScripts(); doBuy('%s', '%s');\">Register</a></div><div id=\"mmattr-%s\">",
        $product->vcrName, str_replace ("\/", "\/\/", $url),  $product->vcrName);
        
        $output .= sprintf("<form method=\"post\" id=\"mmform-%s\">", $product->vcrName);
        $output .= sprintf("<div class=\"mm-row\">%s<div class=\"mm-inline mm-small\">%s</div><div class=\"mm-inline mm-large\">$%s</div><div class=\"mm-inline\">%s</div><div class=\"mm-inline\">%s</div><div class=\"mm-inline mm-large\">%s</div></div>",
                            $HiddenPrice, $EndDate, $Price, $QuantityInput, $Subtotal, $Action);
        $output .= "</form>";
    }
    
    return $output;
}

?>