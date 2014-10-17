<?php
	/*
		Optional "options" variables
		note - Text displayed below the field
		data - typically a key value array for selects but could be anything
		class - classes to apply to the field
		placeholder - placeholder text
		rows - text area only rows attribute
	*/

	$mmm_class_taxonomies = array(
        array('slug' => 'mm-class',
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