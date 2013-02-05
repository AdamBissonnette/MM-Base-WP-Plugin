<?php
class MM_Object
{
	var $_version = "1.0";
	var $intID;

	function MM_Object($intID)
	{
		return $this->__construct(intID);
	}
	
    function __construct($intID)
    {
    	$this->intID = $intID;
    }
    
    function save()
	{
		//determine whether to do an update or insert
	}
	
	function insert()
	{
		//write new record to the db
		global $wpdb;
		
		$array = array(
					'intID' => $this->intID
				);
		$format = array(
					'%d'
				);
		
		return InsertStatement($wpdb->prefix . "mm_object", $array, $format);
	}
	
	function update()
	{
		//update existing record in the db
		$sql = sprintf("UPDATE %s SET intID = '%d',
								WHERE intID = %d",
								$wpdb->prefix . "mm_object",
								$this->intID, $this->intID);
		
		ExecuteStatement($sql);
	}
	
	function delete()
	{
		//remove from db		
		global $wpdb;
		$sql = sprintf("UPDATE %s SET tinDeleted = 1
								WHERE intID = %s",
								$wpdb->prefix . "mm_object",
								$this->intID);
				
		ExecuteStatement($sql);
	}
	
	public function getByID($id)
	{
		//get from db
		global $wpdb;
		$sql = sprintf("SELECT * FROM %s WHERE tinDeleted = 0 AND intID = %s",
				$wpdb->prefix . "mm_object", $pid);
		$result = $wpdb->get_row($sql);
		
		//Populate object
		$object = createFromDBSelect($result);
		
		return $object;
	}
	
	public function getMultiple($limit = -1)
	{
		global $wpdb;
		
		$limitSQL = "";
		
		if ($limit != -1)
		{
			$limitSQL = sprintf(" Limit %s", $limit);
		}
		
		$sql = sprintf("SELECT * FROM %s WHERE tinDeleted = 0 ORDER BY dtmEndDate ASC%s",
				$wpdb->prefix . "mmem_event", $limitSQL);
		$results = $wpdb->get_results($sql);
		
		$resultsArray = array();
		
		foreach ($results as $result)
		{
			$resultsArray[] = createFromDBSelect($result);
		}
		
		return $resultsArray;
	}
    
    public function createFromDBSelect($result)
    {
    	return new MM_Object($result[0]->intID);
    }
    
    function output($type = 1)
    {
    	$output = array(
			"id" => $this->intID
		);
    
    	switch($type)
    	{
    		case 1: // to json
    			return json_encode($output);
    		break;
    		case 2: // to html
    			return sprintf("<p>id: %s</p>", $output->id);
    		break;
    		case 3:
    		default: // to array
    			return $output;
    		break;
    	}
    }
    
    public function outputMultiple($objects, $type = 1)
    {
    	switch($type)
    	{
    		case 1: // to json
    			echo json_encode($objects);
    		break;
    		case 2: // to html
    			$output = "";
    			foreach($objects as $object)
    			{
    				$output .= $object->output($type);
    			}
    		
    			return $output;
    		break;
    		case 3:
    		default: // to array
    			$output = array();
    			
    			foreach($objects as $object)
    			{
    				$output[] = $object->output($type);
    			}
    		
    			return $output;
    		break;
    	}
    }
}

?>