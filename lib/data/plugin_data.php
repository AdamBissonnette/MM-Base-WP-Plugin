<?php
    $mmm_plugin_data = array(
        array('name' => 'Classes',
            'id' => 'classes',
            'type' => 1,
            'icon' => 'fa-graduation-cap',
            'sections' => array(
                array(
                    'name' => 'Add Class',
                    'size' => '3',
                    'fields' => array(
                        array('id' => 'class_type',
                            'label' => 'Class Type',
                            'type' => 'select',
                            'options' => array("data" => MmmToolsNamespace\getTaxonomySelectArray('mm-class'), "isMultiple" => false, "addBlank" => true)),
                        array('id' => 'class_price_override',
                            'label' => 'Price (Override)',
                            'type' => 'text'),
                        array('id' => 'class_size',
                            'label' => 'Max Size (Override)',
                            'type' => 'text' ),
                        array('id' => 'class_data',
                            'label' => 'Class Date',
                            'type' => 'datetime' ),
                        array('id' => 'add_class',
                            'label' => 'Add Class',
                            'type' => 'button',
                            'options' => array('class' => 'btn-success', 'icon' => 'fa-plus') )
                    )
                ),
                array(
                    'name' => 'List Classes',
                    'size' => '9',
                    'fields' => array(
                        array('id' => 'class_list',
                            'label' => 'List Classes',
                            'type' => 'html',
                            'options' => array("data" => list_upcoming_classes()))
                    )
                )
            )
        ),
        array('name' => 'Class Templates',
            'id' => 'class-types',
            'type' => 1,
            'icon' => 'fa-file-code-o',
            'sections' => array(
                array(
                    'name' => 'List Class Types',
                    'size' => '12',
                    'fields' => array(
                        array('id' => 'add_class_template',
                            'label' => 'Add Class Template',
                            'type' => 'button',
                            'options' => array('class' => 'btn-success', 'icon' => 'fa-plus', 'href' => "post-new.php?post_type=mm-class") ),
                        array('id' => 'class_type_list',
                            'label' => 'List Class Types',
                            'type' => 'html',
                            'options' => array("data" => list_class_types()))
                    )
                )
            )
        ),
        array('name' => 'Transaction Reports',
            'id' => 'reports',
            'type' => 1,
            'icon' => 'fa-database',
            'sections' => array(
                array(
                    'name' => 'Sales Activity',
                    'size' => '5',
                    'fields' => array(
                        array('id' => 'class-report',
                            'label' => 'Class Report',
                            'type' => 'html',
                            'options' => array("data" => genPurchaseReport()))
                    )
                ),
                array(
                    'name' => 'Pending Transactions',
                    'size' => '5',
                    'fields' => array(
                        array('id' => 'class-report',
                            'label' => 'Class Report',
                            'type' => 'html',
                            'options' => array("data" => genPurchaseReport(2)))
                    )
                ),
                array(
                    'name' => 'Filter',
                    'size' => '2',
                    'fields' => array(
                        array('id' => 'lookup_class_id',
                            'label' => 'Filter By Class ID',
                            'type' => 'text',
                            'options' => array("note" => "note: Leaving this blank will report all of the existing Transactions.")),
                        array('id' => 'lookup_class_type',
                            'label' => 'Filter By Class Type',
                            'type' => 'select',
                            'options' => array("data" => MmmToolsNamespace\getTaxonomySelectArray('mm-class'), "isMultiple" => false, "addBlank" => true)),
                        array('id' => 'do_lookup',
                            'label' => 'Lookup Class',
                            'type' => 'button',
                            'options' => array('class' => 'btn-success', 'icon' => 'fa-search') )
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
    $atts = array('taxonomy' => 'mm-class', 'wrap_template' => 'tr');
    $list_wrapper = '<table class="table table-bordered table-striped"><tr><th>Name</th><th>Price</th><th>Max Size</th><th>Controls</th></tr>%s</table>';
    $item_template = '<td><a href="post.php?post={id}&action=edit">{title}</a></td>
    <td>$ {price}</td>
    <td>{class_size}</td>
    <td>%s %s %s</td>';
    
    $item_template = sprintf($item_template, MmmToolsNamespace\createLink("usetemplate_{id}", "Use Template", array("class" => "btn-success use_template", "icon" => "fa-external-link fa-flip-horizontal")),
        MmmToolsNamespace\createLink("edittemplate_{id}", "Edit", array("class" => "btn-warning", "icon" => "fa-edit", "href" => "post.php?post={id}&action=edit")),
        MmmToolsNamespace\createLink("reporttemplate_{id}", "Use Template", array("class" => "btn-info do_template_report", "icon" => "fa-external-link-square")));

    return sprintf($list_wrapper, MmmToolsNamespace\ListTaxonomy($atts, $item_template));
}

function add_class_template_link()
{
    return sprintf('<a href="post-new.php?post_type=mm-class" class="%s">Add Class Template</a>', "btn btn-primary");
}

function list_upcoming_classes()
{
    return MmmPluginToolsNamespace\OutputProductList();
}