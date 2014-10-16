<?php
global $mmm_class_taxonomy_registration;

$mmm_class_taxonomy_registration = array(array('slug' => 'mm-product',
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
                'capability_type'     => 'post'))
);

?>