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
        var $username = "";
        var $password = "";

        function __construct($url, $username, $password)
        {
            $this->url = $url;
            $this->username = $username;
            $this->password = $password;
        }

        function DoCurl($data, $method="POST")
        {
            $data["is_curl"] = 1;
            $json = json_encode($data);

            $args = array(
                'method' => $method,
                'body' => $json,
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array()
                );
            $url = "http://" . $this->username . ":" . $this->password . "@" . $this->url;

            $output = wp_remote_post($url, $args);

            return $output["body"];
        }

        /*function DoCurl($data, $method="GET")
        {
            $data["is_curl"] = 1;
            $json = json_encode($data);

            $ch = curl_init($this->url);                                                                      
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);                                                                  
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);                                                                     
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($json))                                                                       
            );

            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $output = curl_exec($ch);

            //var_dump($output. "<br />" . $status_code);

            curl_close($ch);
            return $output;
        }*/

        function toJSON()
        {
            return array('url' => $this->url,
                'username' => $this->username,
                'password' => $this->password);
        }
    }

    class CurlResponse
    {
        var $message = "An error has occured.  Please try this again later or contact an administrator.";
        var $data = null;

        function __construct($entity)
        {
            // var_dump($entity);
            $entity = json_decode($entity);

            if (isset($entity))
            {
                if (isset($entity->data))
                {
                    $data = json_decode($entity->data);

                    if ($data == "")
                    {
                        if (isset($entity->value))
                        {
                            $this->message = $entity->value;
                        }                        
                    }
                    else
                    {
                        $this->data = $data;
                    }
                }
                else
                {
                    if (isset($entity->value))
                    {
                        $this->message = $entity->value;
                    }
                }
            }           
        }
    }
