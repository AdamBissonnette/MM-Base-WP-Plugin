<?php
if (!function_exists('WPInsertStatement')) {
	function WPInsertStatement($table, $array, $format)
	{
		global $wpdb;
		$wpdb->insert($table, $array, $format);
		
		return $wpdb->insert_id;
	}
}
if (!function_exists('WPExecuteStatement')) {
	function WPExecuteStatement($statement)
	{
		global $wpdb;
		$wpdb->query($statement);
	}
}
if (!function_exists('WPExecuteQuery')) {
	function WPExecuteQuery($query)
	{
		global $wpdb;
		$result = $wpdb->get_results($query);
		
		return $result;
	}
}
if (!function_exists('arr_to_obj')) {	
	function arr_to_obj($array = array()) {
		$return = new stdClass();
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$return->$key = $this->convert_array_to_object($val);
			} else {
				$return->{$key} = $val;
			}
		}
		return $return;
	}
}
?>