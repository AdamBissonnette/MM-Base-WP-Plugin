<?php
	global $taxonomies;

	/*
		Optional "options" variables
		note - Text displayed below the field
		data - typically a key value array for selects but could be anything
		class - classes to apply to the field
		placeholder - placeholder text
		rows - text area only rows attribute
	*/

	$taxonomies = array(
		/*array('slug' => 'post',
			  'options' => array(
				array('name' => 'Post Options',
					'id' => 'post',
					'icon' => 'cog',
					'sections' => array(
						array(
							'name' => 'General Options',
							'size' => '6',
							'fields' => array(
								array('id' => 'tagline',
									'label' => 'Tagline',
									'type' => 'text'),
								array('id' => 'icon',
									'label' => 'Icon',
									'type' => 'text'),
								array('id' => 'image',
									'label' => 'Image',
									'type' => 'text'),
								array('id' => 'blurb',
									'label' => 'Blurb',
									'type' => 'textarea',
									'options' => array( "note" => 'Note: Used instead of the excerpt in some cases', "class" => "span5" )),
								array('id' => 'readmoretext',
									'label' => 'Read More Text',
									'type' => 'text')
							)
						)
					)
				)
			)
		)*/
	);
?>