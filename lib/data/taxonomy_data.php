<?php
	global $mmm_class_taxonomies;

	/*
		Optional "options" variables
		note - Text displayed below the field
		data - typically a key value array for selects but could be anything
		class - classes to apply to the field
		placeholder - placeholder text
		rows - text area only rows attribute
	*/

	$mmm_class_taxonomies = array(
        array('slug' => 'mm-product',
              'registration-args' => array(
                'label' => 'mm-product',
                'description'         => 'MM Products',
                'labels'              => array(
                                            'name'                => 'MM Products',
                                            'singular_name'       => 'MM Product',
                                            'menu_name'           => 'MM Product',
                                            'parent_item_colon'   => 'Parent Product:',
                                            'all_items'           => 'All Products',
                                            'view_item'           => 'View Product',
                                            'add_new_item'        => 'Add New Product',
                                            'add_new'             => 'New Product',
                                            'edit_item'           => 'Edit Product',
                                            'update_item'         => 'Update Product',
                                            'search_items'        => 'Search Products',
                                            'not_found'           => 'No products found',
                                            'not_found_in_trash'  => 'No products found in Trash',
                                        ),
                'supports'            => array( 'title' ),
                //'taxonomies'          => array( 'category', 'post_tag' ),
                'hierarchical'        => false,
                'public'              => false,
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
            'options' => array(    array('name' => 'MM Product Options',
                        'id' => 'mm-product',
                        'icon' => 'cog',
                        'sections' => array(
                            array(
                                'name' => 'MM Product Options',
                                'size' => '10',
                                'fields' => array(
                                    array('id' => 'usage',
                                        'label' => 'Shortcode',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: Copy this shortcode to the post / page you want to display it on.', 'disabled' => true, 'class' => 'large', 'default_value' => _genShortcode())),
                                    array('id' => 'invoice-code',
                                        'label' => 'Invoice Code',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: Used along with Date to create an invoice # within paypal', 'class' => 'large')),
                                    array('id' => 'code',
                                        'label' => 'Code',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: Plaintext alternative to using the "id" in the shortcode.', 'class' => 'large')),
                                    array('id' => 'price',
                                        'label' => 'Price',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: Cost per purchase.', 'class' => 'large')),
                                    array('id' => 'max_attendees',
                                        'label' => 'Max Attendees',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: ', 'class' => 'large')),
                                    array('id' => 'notify_attendees',
                                        'label' => 'Notify at X Sales',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: When X instances of this class are sold you\'ll receive a notification via email.', 'class' => 'large')),
                                    array('id' => 'description',
                                        'label' => 'Description',
                                        'type' => 'editor',
                                        'options' => array('note' => 'Note: Each topic is delimited by the newline character.', 'class' => 'col-sm-12', 'rows' => '16'))
                            )
                        )
                    )
                )
            )
        )
    );

    function _genShortcode()
    {
        $id = MmmToolsNamespace\getKeyValueFromArray($_REQUEST, "post", 0);

        return htmlspecialchars(sprintf('[MMProductGroup id="%s" /]', $id));
    }
?>