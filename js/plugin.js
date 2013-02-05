function Start()
{
	//If we wanted to do things at the start here is a nice place :)
}

function BindActions()
{
	jQuery('#btnOverviewOptions').click(function() {ShowOptions()});
	jQuery('#btnOptionsSave').click(function() {if (ValidateForm(jQuery("#mm_settings_form"))){SaveOptions()}});
}

function ShowOptions()
{
	jQuery('#optionstab').tab('show');
}

function SaveOptions()
{
	var vlist_format = jQuery('#mm_fl_list_format').val();
	var vitem_format = jQuery('#mm_fl_item_format').val();
	
	var info = {list_format: vlist_format, item_format: vitem_format};
	
	jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'settings', 'count':10, settings:info }, function(data){FinalizeOptions(data)}, "json"); */
}

function FinalizeOptions(data)
{
	alert("Settings have been saved!");
}

jQuery(document).ready(function($) {
	Start();
});