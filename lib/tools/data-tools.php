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

if (!function_exists('OutputMMData')) {
	//MM Data Functions
	function OutputMMData($tabs, $values=null)
	{
		$isFirst = true;

		echo '<div class="col-sm-12 tabbable">';


		if (count($tabs) > 1)
		{

			echo '<ul class="nav nav-tabs">';
			
			foreach ($tabs as $tab)
			{
				OutputMMTabNav($tab["id"], $tab["name"], $tab["icon"], $isFirst);
				
				if ($isFirst)
				{
					$isFirst = false;
				}
			}

			
			echo '</ul>'; //Done with nav
		
		}

		echo '<div class="row tab-content">';
		
		$isFirst = true;
		
		foreach ($tabs as $tab)
		{
			echo OutputMMTabContent($tab["id"], $tab["sections"], $isFirst, $values);
			
			if ($isFirst)
			{
				$isFirst = false;
			}
		}
		
		echo '</div></div>'; //Done with tab content and tabbable
		
		//return $output;
	}
}

if (!function_exists('OutputMMTabNav')) {
	function OutputMMTabNav($id, $name, $icon, $isFirst)
	{
		 $tabTemplate = '<li%s><a href="#%s" data-toggle="tab"><i class="icon-%s"></i> %s</a></li>';
		 
		 $class = "";
		 
		 if ($isFirst)
		 {
		 	$class = ' class="active"';
		 }
		 
		 echo sprintf($tabTemplate, $class, $id, $icon, $name);
	}
}

if (!function_exists('OutputMMTabContent')) {
	function OutputMMTabContent($id, $sections, $isFirst, $values)
	{
		$tabContentTemplate = '<div class="tab-pane%s" id="%s">';

		$class = "";
		 
		if ($isFirst)
		{
		 	$class = ' active';
		}

		echo sprintf($tabContentTemplate, $class, $id);
		
		foreach ($sections as $section)
		{
			OutputMMSection($section["name"], $section["size"], $section["fields"], $values);
		}

		echo "</div>";
			
		//return $output;
	}
}

if (!function_exists('OutputMMSection')) {
	function OutputMMSection($name, $size, $fields, $values)
	{
		$sectionTemplate = '<div class="col-sm-%s"><legend>%s</legend>';
		echo sprintf($sectionTemplate, $size, $name);

		foreach ($fields as $field)
		{
			$options = isset($field["options"])?$field["options"]:array();
			MMField($field["id"], $field["label"], $field["type"], $options, $values);
		}
		
		echo "</div>";
	}
}

if (!function_exists('GetMMDataFields')) {
	function GetMMDataFields($tabs)
	{
		$fields = array();

		foreach ($tabs as $tab)
		{

			foreach ($tab["sections"] as $section)
			{
				$fields = array_merge($fields, $section["fields"]);
			}
		}

		return $fields;
	}
}

if (!function_exists('MMField')) {
	function MMField($id, $label, $type, $options=null, $values=null)
	{
		global $Mmm_Class_Manager;
		
		$formField = "";

		if (isset($values))
		{
			$value = isset($values[$id])?stripslashes($values[$id]):"";
			$formField = createMMFormField($id, $label, $value, $type, $options);
		}
		else
		{
			$formField = createMMFormField($id, $label, $Mmm_Class_Manager->get_setting($id), $type, $options);
		}

		//return $formField;
	}
}
?>