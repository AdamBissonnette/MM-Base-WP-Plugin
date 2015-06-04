<?php
namespace MmmPluginToolsNamespace;

function getEntity($atts) {

    extract( shortcode_atts( array(
        'url' => '',
        'entity' => '',
        'id' => ''
        ), $atts ) );

    $curly = new CurlHandler($url);
    $data = array("fn" => "get", "entityName" => $entity, "id" => $id);

    $json = json_decode($curly->DoCurl($data));

    return _output_json($json);
}

function _output_json($json)
{
    $output = "";

    foreach ($json as $key => $value) {
        if (is_object($value))
        {
            $output .= $key . " => " . output_json($value) . "<br />";    
        }
        else
        {
            $output .= $key . " => " . $value . "<br />";            
        }

    }

    return $output;
}

?>