<?php
	/*
		Tabs
		Sections
		Fields
	*/

	global $mmm_class_options;
	$mmm_class_options = array(
		array('name' => 'Reports',
			'id' => 'reports',
			'icon' => 'info',
			'sections' => array(
				array(
					'name' => 'Sales Activity',
					'size' => '6',
					'fields' => array(
						array('id' => 'class-report',
							'label' => 'Class Report',
							'type' => 'text')
					)
				),
				array(
					'name' => 'Failed Transactions',
					'size' => '6',
					'fields' => array(
						array('id' => 'class-report',
							'label' => 'Class Report',
							'type' => 'text')
					)
				)
			)
		),
		array('name' => 'Class Types',
			'id' => 'class-types',
			'icon' => 'coffee',
			'sections' => array(
				array(
					'name' => 'Add Class Type',
					'size' => '6',
					'fields' => array(
						array('id' => 'class_name',
							'label' => 'Class Name',
							'type' => 'text'),
						array('id' => 'class_code',
							'label' => 'Class Code',
							'type' => 'text'),
						array('id' => 'class_price',
							'label' => 'Price',
							'type' => 'text'),
						array('id' => 'class_size',
							'label' => 'Max Size',
							'type' => 'text' ),
						array('id' => 'class_post',
							'label' => 'Class Page',
							'type' => 'select',
							'options' => array("data" => getMMPostsSelectArray(), "isMultiple" => false) ),
							
					)
				),
				array(
					'name' => 'List Class Types',
					'size' => '6',
					'fields' => array(
						array('id' => 'class_type_list',
							'label' => 'List Class Types',
							'type' => 'text')
					)
				)
			)
		),
		array('name' => 'Classes',
			'id' => 'classes',
			'icon' => 'beer',
			'sections' => array(
				array(
					'name' => 'Add Class',
					'size' => '6',
					'fields' => array(
						array('id' => 'class_type',
							'label' => 'Class Type',
							'type' => 'select',
							'options' => array("data" => getMMPostsSelectArray(), "isMultiple" => false)),
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
							'type' => 'text')
					)
				)
			)
		),
		array('name' => 'Class Manager Settings',
			'id' => 'mmm_class_manager_settings',
			'icon' => 'cog',
			'sections' => array(
				array(
					'name' => 'Paypal Settings',
					'size' => '6',
					'fields' => array(
						array('id' => 'paypal_account',
							'label' => 'Paypal Account',
							'type' => 'text'),
						array('id' => 'tax_percent',
							'label' => 'Tax Percent',
							'type' => 'text',
							'options' => array("note" => 'note: a percentage amount to charge in tax (e.g. for 10% enter 10)')),
						array('id' => 'invoice_prefix',
							'label' => 'Invoice Prefix',
							'type' => 'text',
							'options' => array("note" => 'note: [YourInvoiceTextHere-]1XXXX, this appears in your paypal account')),
						array('id' => 'currency_code',
							'label' => 'Currency Code',
							'type' => 'text',
							'options' => array( "placeholder" => 'CAD', "note" => "e.g. CAD, USD, GBP??" ))
					)
				),
				array(
					'name' => 'Class Manager Settings',
					'size' => '6',
					'fields' => array(
						array('id' => 'admin_email',
							'label' => 'Admin Email',
							'type' => 'text',
							'options' => array("note" => "note: This will be the email the site will use when sending sales notifications" )),
						array('id' => 'notify_quantity',
							'label' => 'Notify Quanity',
							'type' => 'text',
							'options' => array("note" => "note: When this amount of classes remains you will receive a notification you're running low")),
						array('id' => 'no_classes_message',
							'label' => 'No Classes Message',
							'type' => 'textarea',
							'options' => array("placeholder" => 'There are no classes of this type at this point in time.', "class" => "col-sm-12")),
						array('id' => 'class_footer_message',
							'label' => 'Class Footer Message',
							'type' => 'textarea',
							'options' => array("placeholder" => 'Here is a [link] to important information you should read before purchasing a class.', "class" => "col-sm-12"))
					)
				)
			)
		)
	);
?>