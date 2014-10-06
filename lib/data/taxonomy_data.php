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
                'supports'            => array( 'title' ),
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
            'options' => array(    array('name' => 'Bingo Card Options',
                        'id' => 'bingo-card',
                        'icon' => 'cog',
                        'sections' => array(
                            array(
                                'name' => 'Bingo Card Options',
                                'size' => '10',
                                'fields' => array(
                                    array('id' => 'usage',
                                        'label' => 'Shortcode',
                                        'type' => 'text',
                                        'options' => array('note' => 'Note: Copy this shortcode to the post / page you want to display it on.', 'disabled' => true, 'class' => 'large', 'default_value' => _genBingoCardShortcode())),
                                    array('id' => 'topics',
                                        'label' => 'Topics',
                                        'type' => 'textarea',
                                        'options' => array('note' => 'Note: Each topic is delimited by the newline character.', 'class' => 'col-sm-12', 'rows' => '16')),
                                    array('id' => 'icon',
                                        'label' => 'Icon',
                                        'type' => 'select',
                                        'options' => array("class" => 'font-awesome', "data" => MmmToolsNamespace\getFontAwesomeSelectArray())),
                                    array('id' => 'heading-color',
                                        'label' => 'Heading Color',
                                        'type' => 'color',
                                        'options' => array("updateRegion" => true, 'default_value' => "#000000")),
                                    array('id' => 'text-color',
                                        'label' => 'Text Color',
                                        'type' => 'color',
                                        'options' => array("updateRegion" => true, 'default_value' => "#000000")),
                                    array('id' => 'border-color',
                                        'label' => 'Border Color',
                                        'type' => 'color',
                                        'options' => array("updateRegion" => true, 'default_value' => "#000000")),
                                    array('id' => 'background-color',
                                        'label' => 'Background Color',
                                        'type' => 'color',
                                        'options' => array("updateRegion" => true, 'default_value' => "#ffffff")),
                                    array('id' => 'heading-font',
                                        'label' => 'Heading Font',
                                        'type' => 'select',
                                        'options' => array("data" => MmmToolsNamespace\getGoogleWebFontSelectArray())),
                                    array('id' => 'text-font',
                                        'label' => 'Text Font',
                                        'type' => 'select',
                                        'options' => array("data" => MmmToolsNamespace\getGoogleWebFontSelectArray()))
                            )
                        )
                    )
                )
            )
        )
    );

    function _genBingoCardShortcode()
    {
        $id = MmmToolsNamespace\getKeyValueFromArray($_REQUEST, "post", 0);

        return htmlspecialchars(sprintf('[MMBingoCard id="%s" /]', $id));
    }
?>