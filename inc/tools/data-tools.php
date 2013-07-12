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


//Theme Data Functions
function OutputThemeData($tabs, $values=null)
{
	$isFirst = true;

	echo '<div class="span12 tabbable">';


	if (count($tabs) > 1)
	{

		echo '<ul class="nav nav-tabs">';
		
		foreach ($tabs as $tab)
		{			
			OutputTabNav($tab["id"], $tab["name"], $tab["icon"], $isFirst);
			
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
		echo OutputTabContent($tab["id"], $tab["sections"], $isFirst, $values);
		
		if ($isFirst)
		{
			$isFirst = false;
		}
	}
	
	echo '</div>'; //Done with tab content
	
	//return $output;
}

function OutputTabNav($id, $name, $icon, $isFirst)
{
	 $tabTemplate = '<li%s><a href="#%s" data-toggle="tab"><i class="icon-%s"></i> %s</a></li>';
	 
	 $class = "";
	 
	 if ($isFirst)
	 {
	 	$class = ' class="active"';
	 }
	 
	 echo sprintf($tabTemplate, $class, $id, $icon, $name);
}

function OutputTabContent($id, $sections, $isFirst, $values)
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
		OutputSection($section["name"], $section["size"], $section["fields"], $values);
	}

	echo "</div>";
		
	//return $output;
}

function OutputSection($name, $size, $fields, $values)
{
	$sectionTemplate = '<div class="span%s"><legend>%s</legend>';
	echo sprintf($sectionTemplate, $size, $name);

	foreach ($fields as $field)
	{
		$options = isset($field["options"])?$field["options"]:array();
		MMRootsField($field["id"], $field["label"], $field["type"], $options, $values);
	}
	
	echo "</div>";
}

function GetThemeDataFields($tabs)
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

function MMRootsField($id, $label, $type, $options=null, $values=null)
{
	global $MM_Roots;
	
	$formField = "";

	if (isset($values))
	{
		$value = isset($values[$id])?stripslashes($values[$id]):"";
		$formField = createFormField($id, $label, $value, $type, $options);
	}
	else
	{
		$formField = createFormField($id, $label, $MM_Roots->get_setting($id), $type, $options);
	}

	//return $formField;
}
?>