<?php
namespace MmmPluginToolsNamespace;

function MMBingoCard($atts)
{
    global $Mmm_Bingo;

    wp_enqueue_style('bingoapp', $Mmm_Bingo->location_folder . '/assets/css/app.css', false, null);
    \MmmToolsNamespace\load_font_awesome();

    extract( shortcode_atts( array(
            'id' => '0',
            'title' => '',
            'count' => '1',
            'class' => 'mmm-bingo-card'
        ), $atts));

    if (isset($_GET["count"]))
    {
        if (is_numeric($_GET["count"]))
        {
            if ($_GET["count"] < 25)
            {
                $count = $_GET["count"];
            }
        }
    }

    //Todo: Add text / heading font settings in here
    wp_enqueue_style('OpenSans', 'http://fonts.googleapis.com/css?family=Open+Sans', false, null);
    wp_enqueue_style('Ewert', 'http://fonts.googleapis.com/css?family=Ewert', false, null);

    //Todo: Add block of style output for mmbc-$id color styles

    if ($id != 0)
    {
        $card = $Mmm_Bingo->get_post_meta($id);
        $post = get_post($id);
    
        if (isset($post))
        {
            if ($title == '')
            {
                $title = $post->post_title;
            }

            $Topics = _formatTopics($card["topics"]);
            $CenterIcon = $card["icon"];
        }
        else
        {
            if ($title == '')
            {
                $title = $Mmm_Bingo->get_setting("default_title");
            }

            $Topics = _sortTopics($Mmm_Bingo->get_setting("default_topics"));
            $CenterIcon = $Mmm_Bingo->get_setting("default_star");
        }
    }

    $output = "";

    if (count($Topics) < 25)
    {
        $output = "This bingo card has less than the required number of topics to be generated.";
    }
    else
    {   
        for ($i = 0; $i < $count; $i++) {
            $output .= _genBingoCard($id, $title, $Topics, $CenterIcon, $class);
        }
    }

    return $output;
}

function _genBingoCard($id, $title, $Topics, $CenterIcon, $class)
{
    $Topics = _sortTopics($Topics);
    $cardTemplate = '<div class="mmbc_wrapper"><h4>%s</h4><table id="mmbc-%s" class="BingoCard %s">%s</table></div>';
    
    $content = "";
    
    for ($i = 0; $i < 25; $i++)
    {
        if ($i == 0)
        {
            $content .= "<tr class=\"row-1\">";
        }
        else if ($i % 5 == 0)
        {
            $content .= sprintf("</tr>\n<tr class=\"row-%s\">\n", ($i / 5 + 1));
        }
    
        $content .= _genCardSquare($Topics[$i], ($i % 5 == 0));
    
        if ($i == 11)
        {
            $content .= _genCenterSquare($CenterIcon);
            $i++;
        }
    }

    return sprintf($cardTemplate, $title, $id, $class, $content);
}

function _formatTopics($Topics) {
    return explode("\n", $Topics);
}

function _sortTopics($Topics) {
    shuffle($Topics);
    
    return array_slice($Topics, 0, 25);
}

function _genCardSquare($Topic = "Star", $isFirstInRow = false)
{
    $TopicTemplate = "<td class=\"%s\"><div>%s</div></td>\n";
    $SquareClass = "topic";
    
    if ($isFirstInRow)
    {
        $SquareClass .= " first";
    }

    $Square = sprintf($TopicTemplate, $SquareClass, $Topic);
        
    if ($Topic == "Star")
    {
        $Square = sprintf('<td><div class="fa fa-5x %s"></div></td>', $Icon);
    }
    
    return $Square;
}

function _genCenterSquare($Icon = "fa-star")
{
    return sprintf('<td><div class="fa fa-5x fa-%s"></div></td>', $Icon);
}

?>