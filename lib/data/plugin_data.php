<?php

    global $mmm_plugin_data;
    $mmm_plugin_data = array(
        array('name' => 'Reports',
            'id' => 'reports',
            'type' => 1,
            'icon' => 'info',
            'sections' => array(
                array(
                    'name' => 'Sales Activity',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'class-report',
                            'label' => 'Class Report',
                            'type' => 'custom',
                            'options' => array("function_ref" => "report_success"))
                    )
                ),
                array(
                    'name' => 'Failed Transactions',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'class-report',
                            'label' => 'Class Report',
                            'type' => 'custom',
                            'options' => array("function_ref" => "report_failure"))
                    )
                )
            )
        ),
        array('name' => 'Class Types',
            'id' => 'class-types',
            'type' => 1,
            'icon' => 'coffee',
            'sections' => array(
                array(
                    'name' => 'Add Class Type',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'type_name',
                            'label' => 'Class Name',
                            'type' => 'text'),
                        array('id' => 'type_code',
                            'label' => 'Class Code',
                            'type' => 'text'),
                        array('id' => 'type_price',
                            'label' => 'Price',
                            'type' => 'text'),
                        array('id' => 'type_size',
                            'label' => 'Max Size',
                            'type' => 'text' ),
                        array('id' => 'type_post',
                            'label' => 'Class Page',
                            'type' => 'select',
                            'options' => array("data" => MmmToolsNamespace\getPostsSelectArray(), "isMultiple" => false) ),
                    )
                ),
                array(
                    'name' => 'List Class Types',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'class_type_list',
                            'label' => 'List Class Types',
                            'type' => 'custom',
                            'options' => array("function_ref" => "list_class_types"))
                    )
                )
            )
        ),
        array('name' => 'Classes',
            'id' => 'classes',
            'type' => 1,
            'icon' => 'beer',
            'sections' => array(
                array(
                    'name' => 'Add Class',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'class_type',
                            'label' => 'Class Type',
                            'type' => 'select',
                            'options' => array("data" => MmmToolsNamespace\getPostsSelectArray(), "isMultiple" => false)),
                        array('id' => 'class_price_override',
                            'label' => 'Price (Override)',
                            'type' => 'text'),
                        array('id' => 'class_size',
                            'label' => 'Max Size (Override)',
                            'type' => 'text' ),
                        array('id' => 'class_data',
                            'label' => 'Class Date',
                            'type' => 'text' )
                    )
                ),
                array(
                    'name' => 'List Classes',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'class_list',
                            'label' => 'List Classes',
                            'type' => 'custom',
                            'options' => array("function_ref" => "list_active_classes"))
                    )
                )
            )
        )
    );