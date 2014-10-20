<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
	/*
		Optional "options" variables
		note - Text displayed below the field
		data - typically a key value array for selects but could be anything
		class - classes to apply to the field
		placeholder - placeholder text
		rows - text area only rows attribute
	*/
    global $mmm_class_taxonomies;

	$mmm_class_taxonomies = array(array('slug' => 'mm-class',
              'registration-args' => array(
                'label' => 'mm-class',
                'description'         => 'Class Templates',
                'labels'              => array(
                                            'name'                => 'Class Templates',
                                            'singular_name'       => 'Class Template',
                                            'menu_name'           => 'Class Template',
                                            'parent_item_colon'   => 'Parent Class Template:',
                                            'all_items'           => 'All Class Templates',
                                            'view_item'           => 'View Class Template',
                                            'add_new_item'        => 'Add New Class Template',
                                            'add_new'             => 'New Class Template',
                                            'edit_item'           => 'Edit Class Template',
                                            'update_item'         => 'Update Class Template',
                                            'search_items'        => 'Search Class Templates',
                                            'not_found'           => 'No Class Templates found',
                                            'not_found_in_trash'  => 'No Class Templates found in Trash',
                                        ),
                'supports'            => array( 'title' ),
                'taxonomies'          => array( 'category'/*, 'post_tag'*/ ),
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
                'capability_type'     => 'post'),
            'options' => array(
                array('name' => 'Class Template Meta',
                        'id' => 'mm-class',
                        'icon' => 'cog',
                        'sections' => array(
                            array(
                                'name' => 'Class Template Meta',
                                'size' => '10',
                                'fields' => array(
                                    array('id' => 'usage',
                                        'label' => 'Shortcode',
                                        'type' => 'text',
                                        'options' => array('note' => 'note: Copy this shortcode to the post / page you want to display it on.', 'disabled' => true, 'class' => 'large', 'default_value' => _genShortcode())),
                                    array('id' => 'invoice-code',
                                        'label' => 'Invoice Code',
                                        'type' => 'text',
                                        'options' => array('note' => 'note: Used along with Date to create an invoice # within paypal', 'class' => 'large')),
                                    array('id' => 'code',
                                        'label' => 'Code',
                                        'type' => 'text',
                                        'options' => array('note' => 'note: Plaintext alternative to using the "id" in the shortcode.', 'class' => 'large')),
                                    array('id' => 'price',
                                        'label' => 'Price',
                                        'type' => 'text',
                                        'options' => array('note' => 'note: Cost per purchase.', 'class' => 'large')),
                                    array('id' => 'class_size',
                                        'label' => 'Max Attendees',
                                        'type' => 'text',
                                        'options' => array('note' => 'note: This is the maximum number of sales that can be made on a class for a given date.', 'class' => 'large')),
                                    array('id' => 'notify_attendees',
                                        'label' => 'Notify at X Sales',
                                        'type' => 'text',
                                        'options' => array('note' => 'note: When X instances of this class are sold you\'ll receive a notification via email.', 'class' => 'large')),
                                    array('id' => 'description',
                                        'label' => 'Description',
                                        'type' => 'editor',
                                        'options' => array('note' => 'note: Each topic is delimited by the newline character.', 'class' => 'col-sm-12', 'rows' => '16'))
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

        return htmlspecialchars(sprintf('[MMClassGroup id="%s" /]', $id));
    }
?>