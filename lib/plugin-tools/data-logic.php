<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

    function _output_json($json)
    {
        $output = "";

        foreach ($json as $key => $value) {
            if (is_object($value) || is_array($value))
            {
                $output .= $key . " => " . _output_json($value) . "<br />";    
            }
            else
            {
                $output .= $key . " => " . $value . "<br />";            
            }

        }

        return $output;
    }

    class CurlHandler
    {
        var $url = "";
        var $key = "";

        function __construct($url, $key)
        {
            $this->url = $url;
            $this->key = $key;
        }

        function DoCurl($data, $method="GET")
        {
            $data["key"] = $this->key;
            $json = json_encode($data);

            $ch = curl_init($this->url);                                                                      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($json))                                                                       
            );                                                                                                                   
                                                                                                                                 
            return curl_exec($ch);
        }
    }