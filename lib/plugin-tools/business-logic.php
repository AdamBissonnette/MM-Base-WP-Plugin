<?php
namespace MmmPluginToolsNamespace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

    function SaveUser($atts)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;

        $atts["entityName"] = "User";
        if ($id == -1)
        {
            $atts["fn"] = "POST";
            $json = json_decode($curly->DoCurl($atts), "POST");
        }
        else
        {
            $atts["fn"] = "PUT";
            $json = json_decode($curly->DoCurl($atts), "PUT");   
        }

        return $json;
    }


    //--- Returns ---
    // {  
    //    "2":{  
    //       "id":2,
    //       "name":"Test",
    //       "users":[  
    //          {  
    //             "id":1,
    //             "name":"Test",
    //             "email":"info@mediamanifesto.com",
    //             "phone":"+13063704254",
    //             "date":1433089285000
    //          },
    //          {  
    //             "id":2,
    //             "name":"Test 2",
    //             "email":"",
    //             "phone":"+13069999999",
    //             "date":1433091758000
    //          }
    //       ]
    //    }
    // }
    function GetParty($atts)
    {
        global $MMM_Curl_Manager;
        $curly = $MMM_Curl_Manager->curlHandler;
        $atts["entityName"] = "Party";


    }