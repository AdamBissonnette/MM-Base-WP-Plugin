<?php
	/*
		Tabs
		Sections
		Fields
	*/

	global $mmm_bingo_options;
	$mmm_bingo_options = array(
		array('name' => 'Class Manager Settings',
			'id' => 'mmm_class_manager_settings',
			'type' => 0,
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
						array('id' => 'flat_fee',
							'label' => 'Flat Fee',
							'type' => 'text',
							'options' => array("note" => 'note: a flat transaction fee on all purchases.')),
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
							'options' => array("note" => "note: This will be the email the site will use when sending sales notifications - if left empty emails will be sent to the current sitewide admin." )),
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
							'options' => array("placeholder" => 'Here is a [link] to important information you should read before purchasing a class.', "class" => "col-sm-12")),
						array('id' => 'enable_admin_menu',
							'label' => 'Add Shortcut to Admin Bar',
							'type' => 'checkbox',
							'options' => array("note" => "note: When enabled this will add a link to this settings page into your admin bar."))
					)
				)
			)
		)
	);
?>