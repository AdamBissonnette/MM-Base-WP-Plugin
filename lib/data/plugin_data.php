<?php
    $mmm_plugin_data = array(
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
                            'options' => array("data" => MmmToolsNamespace\getTaxonomySelectArray('mm-product'), "isMultiple" => false)),
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
                            'type' => 'html',
                            'options' => array("data" => list_upcoming_classes()))
                    )
                )
            )
        ),
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
                            'type' => 'html',
                            'options' => array("data" => genPurchaseReport()))
                    )
                ),
                array(
                    'name' => 'Pending Transactions',
                    'size' => '6',
                    'fields' => array(
                        array('id' => 'class-report',
                            'label' => 'Class Report',
                            'type' => 'html',
                            'options' => array("data" => genPurchaseReport(2)))
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
        )        
    );

function genPurchaseReport($state = 1)
{
    return MmmPluginToolsNamespace\genPurchaseReport($state);
}

function list_class_types()
{
    $atts = array('taxonomy' => 'mm-product', 'wrap_template' => 'tr');
    $list_wrapper = '<table class="table table-bordered table-striped"><tr><th>Name</th><th>Price</th><th>Max Size</th><th>Controls</th></tr>%s</table>';
    $item_template = '<td><a href="post.php?post={id}&action=edit">{title}</a></td><td>$ {price}</td><td>{class_size}</td><td><a href="javascript: use_class_template({id});">Use Template</a> | <a href="post.php?post={id}&action=edit">Edit</a></td>';

    return sprintf($list_wrapper, MmmToolsNamespace\ListTaxonomy($atts, $item_template));
}

function list_upcoming_classes()
{
    return MmmPluginToolsNamespace\OutputProductList();
}