<?php
	/*
		Tabs
		Sections
		Fields
	*/

	global $trackable_options;
	$trackable_options = array(
		array('name' => 'Trackable Options',
			'id' => 'theme',
			'icon' => 'cog',
			'sections' => array(
					array(
					'name' => 'General Options',
					'size' => '6',
					'fields' => array(
						array('id' => 'analytics-js-object',
							'label' => 'Analytics JS Object',
							'type' => 'text',
							'options' => array('note' => 'Note: if blank the default _gaq will be used'))
						)
					),
					array(
					'name' => 'Events',
					'size' => '12',
					'fields' => array(
						array('id' => 'events',
							'label' => 'Analytics JS Object',
							'type' => 'textarea',
							'options' => array('class' => 'span8', 'rows' => '8', 'note' => 'Format the following on newlines: Object ID,Event to Bind to (Click, Hover),Analytics Event Name,Event Category', 'placeholder' => 'button1,Click,Button Clicked,Lead'))
						)
					)
				)
			)
		);
?>