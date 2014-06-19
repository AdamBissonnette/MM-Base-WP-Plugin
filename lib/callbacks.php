<?php
	function do_callback($isAdmin)
	{
		if ($isAdmin)
		{
			do_admin_function();
		}

		do_standard_function();
	}

	function do_admin_function()
	{
		switch($_REQUEST['fn']){
			case 'settings':
				$data_back = $_REQUEST['settings'];
				
				$values = array();
				$i = 0;
				foreach ($data_back as $data)
				{
					$values[$data['name']] = $data['value'];
				}
				
				$this->_save_settings_todb($values);
			break;
		}
	}


	function do_standard_function()
	{
		switch($_REQUEST['fn']){
			case 'buy':
				$data_back = $_REQUEST['buy'];
				
				$values = array();
				$i = 0;
				foreach ($data_back as $data)
				{
					$values[$data['name']] = $data['value'];
				}
				
				$this->_save_settings_todb($values);
			break;
		}
	}
?>