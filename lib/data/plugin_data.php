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
                    'name' => 'List Class Types',
                    'size' => '12',
                    'fields' => array(
                        array('id' => 'class_type_list',
                            'label' => 'List Class Types',
                            'type' => 'html',
                            'options' => array("data" => list_class_types()))
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

function list_class_types()
{
    return "Classes~~~";
}