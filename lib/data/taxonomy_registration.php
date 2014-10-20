<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
global $mmm_class_taxonomy_registration;

$mmm_class_taxonomy_registration = array(array('slug' => 'mm-class',
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
                'capability_type'     => 'post'))
);

?>