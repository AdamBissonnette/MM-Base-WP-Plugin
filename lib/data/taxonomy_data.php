<?php
	global $mmm_bingo_taxonomies;

	/*
		Optional "options" variables
		note - Text displayed below the field
		data - typically a key value array for selects but could be anything
		class - classes to apply to the field
		placeholder - placeholder text
		rows - text area only rows attribute
	*/

	$mmm_bingo_taxonomies = array(
		array('slug' => 'bingo-card',
			  'registration-args' => array(
				'label' => 'bingo-card',
				'description'         => 'Bingo Cards',
				'labels'              => array(
											'name'                => 'Bingo Cards',
											'singular_name'       => 'Bingo Card',
											'menu_name'           => 'Bingo Card',
											'parent_item_colon'   => 'Parent Card:',
											'all_items'           => 'All Cards',
											'view_item'           => 'View Card',
											'add_new_item'        => 'Add New Card',
											'add_new'             => 'New Card',
											'edit_item'           => 'Edit Card',
											'update_item'         => 'Update Card',
											'search_items'        => 'Search cards',
											'not_found'           => 'No cards found',
											'not_found_in_trash'  => 'No cards found in Trash',
										),
				'supports'            => array( 'title', 'editor' ),
				//'taxonomies'          => array( 'category', 'post_tag' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 99,
				'menu_icon'           => '',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',),
			'options' => array(	array('name' => 'Bingo Card Options',
						'id' => 'bingo-card',
						'icon' => 'cog',
						'sections' => array(
							array(
								'name' => 'Bingo Card Options',
								'size' => '10',
								'fields' => array(
									array('id' => 'tagline',
										'label' => 'Tagline',
										'type' => 'text',
										'options' => array('note' => 'Note: This is display after / alongside the title of the section')),
									array('id' => 'sectionID',
										'label' => 'Section ID',
										'type' => 'text',
										'options' => array('note' => 'Note: This is the ID to use in conjunction with the navigation hashtag #SectionID')),
									array('id' => 'inline-styles',
										'label' => 'Inline Styles',
										'type' => 'textarea',
										'options' => array('note' => 'Note: This allows you to directly modify the inline css styles on the Bingo Card for a background or anything',
											'class' => 'span6')),
									)
						)
					)
				)
			)
		)
	);
?>