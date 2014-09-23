<?php
	/*
		Tabs
		Sections
		Fields
	*/

	global $mmm_bingo_options;
	$mmm_bingo_options = array(
		array('name' => 'Bingo Settings',
			'id' => 'mmm_bingo_settings',
			'type' => 0,
			'icon' => 'cog',
			'sections' => array(
				array(
					'name' => 'Default Options',
					'size' => '6',
					'fields' => array(
						array('id' => 'default_title',
							'label' => 'Title',
							'type' => 'text'),
						array('id' => 'default_star',
							'label' => 'Center Icon',
							'type' => 'select',
							'options' => array("class" => 'font-awesome', "data" => MmmToolsNamespace\getFontAwesomeSelectArray())),
						array('id' => 'default_topics',
							'label' => 'Topics',
							'type' => 'textarea',
							'options' => array('rows' => 12, 'class' => 'col-sm-12', 'note' => 'Note: Write each topic on it\'s own line.'))
					)
				)
			)
		)
	);
?>