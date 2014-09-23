<?php

    global $mmm_plugin_data;
    $mmm_plugin_data = array(
        array('name' => 'Bingo Cards',
            'id' => 'bingo-cards',
            'type' => 1,
            'icon' => 'beer',
            'sections' => array(
                array(
                    'name' => 'Add Bingo Card',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'name',
                            'label' => 'Name',
                            'type' => 'text'),
                        array('id' => 'topics',
                            'label' => 'Topics',
                            'type' => 'textarea'),
                        array('id' => 'icon',
                            'label' => 'Center Icon',
                            'type' => 'select',
                            'options' => array("class" => 'font-awesome', "data" => MmmToolsNamespace\getFontAwesomeSelectArray())),
                    )
                ),
                array(
                    'name' => 'List Bingo Cards',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'card_list',
                            'label' => '{List of Bingo Cards}',
                            'type' => 'custom',
                            'options' => array("function_ref" => "list_cards"))
                    )
                )
            )
        )
    );