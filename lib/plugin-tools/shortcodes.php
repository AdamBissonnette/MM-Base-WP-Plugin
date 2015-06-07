<?php
namespace MmmPluginToolsNamespace;

function getEntity($atts) {

    extract( shortcode_atts( array(
        'entity' => '',
        'id' => ''
        ), $atts ) );

    global $MMM_Curl_Manager;

    $curly = $MMM_Curl_Manager->curlHandler;

    $data = array("fn" => "GET", "entityName" => $entity, "id" => $id);

    $json = json_decode($curly->DoCurl($data));

    return _output_json($json);
}

?>