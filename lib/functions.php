<?php
	/* References */
	include_once('tools/admin-tools.php');
	include_once('tools/data-tools.php');
	include_once('tools/date-tools.php');
	include_once('tools/email-tools.php');
	include_once('tools/wp-tools.php');

	include_once('plugin-tools/setup.php');
	include_once('plugin-tools/ajax.php');
	include_once('plugin-tools/data-logic.php');
	include_once('plugin-tools/business-logic.php');
	include_once('plugin-tools/reporting-logic.php');
	include_once('plugin-tools/shortcodes.php');

	class CurlHandler
	{
	    var $url = null;

	    function __construct($url)
	    {
	        $this->url = $url;
	    }

	    function DoCurl($data)
	    {
	        $json = json_encode($data);

	        $ch = curl_init($this->url);                                                                      
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                          
	            'Content-Type: application/json',                                                                                
	            'Content-Length: ' . strlen($json))                                                                       
	        );                                                                                                                   
	                                                                                                                             
	        return curl_exec($ch);
	    }
	}
?>