function Start()
{
	
}

function BindActions()
{
	jQuery('#btnListAdd').click(function() {ResetAdd(); ShowAdd()});
	jQuery('#btnOverviewOptions').click(function() {ShowOptions()});
	jQuery('#btnObjectSave').click(function() {if (ValidateForm(jQuery("#mm_object_form"))){SaveEvent()}});
	jQuery('#btnOptionsSave').click(function() {if (ValidateForm(jQuery("#mm_settings_form"))){SaveOptions()}});
}

function ShowAdd()
{
	jQuery('#addtab').tab('show');
}

function ShowOptions()
{
	jQuery('#optionstab').tab('show');
}

function ShowList()
{
	jQuery('#plisttab').tab('show');
}

function SaveOptions()
{
	/* var vnemail = jQuery('#mm_em_notifyemail').val();
	var vnquant = jQuery('#mm_em_notifyquantity').val();
	var vcname1 = jQuery('#mm_em_contact_name1').val();
	var vcemail1 = jQuery('#mm_em_contact_email1').val();
	var vcname2 = jQuery('#mm_em_contact_name2').val();
	var vcemail2 = jQuery('#mm_em_contact_email2').val();
	var vcname3 = jQuery('#mm_em_contact_name3').val();
	var vcemail3 = jQuery('#mm_em_contact_email3').val();
	var vcname4 = jQuery('#mm_em_contact_name4').val();
	var vcemail4 = jQuery('#mm_em_contact_email4').val();
	var vcname5 = jQuery('#mm_em_contact_name5').val();
	var vcemail5 = jQuery('#mm_em_contact_email5').val();
	
	var info = {nemail: vnemail, nquant: vnquant, cname1: vcname1, cemail1: vcemail1, cname2: vcname2, cemail2: vcemail2, cname3: vcname3, cemail3: vcemail3, cname4: vcname4, cemail4: vcemail4, cname5: vcname5, cemail5: vcemail5};
	
	jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'settings', 'count':10, settings:info }, function(data){FinalizeOptions(data)}, "json"); */
}

function SaveEvent()
{
	/*
	var vpid = jQuery('#mm_em_pid').val();
	var vname = jQuery('#mm_em_pname').val();
	//var desc = jQuery('#mm_em_pdesc').val();
	var vdesc = tinyMCE.activeEditor.getContent();
	//var price = jQuery('#mm_em_pcost').val();
	var vmax = jQuery('#mm_em_pquant').val();
	var vnotify = jQuery('#mm_em_pnquant').val();
	var vstart = jQuery('#mm_em_pstart').val();
	var vend = jQuery('#mm_em_pend').val();
	var vlocation = jQuery('#mm_em_plocation').val();
	
	var vregistration = 0;
	
	if (IsChecked('mm_em_registration'))
	{
		vregistration =	1;
	}
	 
	
	var info = {pid: vpid, name: vname, desc: vdesc, max: vmax, notify: vnotify, start: vstart, end: vend, location: vlocation, registration: vregistration};
	
	//var info = '{"info":[{' + bJSONS("pid", pid) + ', ' + bJSONS("name", name) + ', ' + bJSONS("desc", desc) + ', ' + bJSONS("max", max) + ', ' + 	bJSONS("notify", notify) +
	//', ' + bJSONS("start", start) + ', ' + bJSONS("end", end) + ', ' + bJSONS("location", location) + '}]}';
	
	jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'save', 'count':10, event:info }, function(data){FinalizeAdd(data)}, "json");
	*/
}

function EditEvent(id)
{
	var info = '{"info":[{' + bJSONS("Pid", id) + '}]}';
	jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'get', 'count':10, get:info }, function(data){FinalizeEdit(data)}, "json");
}

function CopyEvent(id)
{
	var info = '{"info":[{' + bJSONS("Pid", id) + '}]}';
	jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'get', 'count':10, get:info }, function(data){FinalizeCopy(data)}, "json");
}

function DeleteEvent(id)
{
	if (confirm('Are you sure you want to delete this event?'))
	{
		var info = '{"info":[{' + bJSONS("Pid", id) + '}]}';
		
		jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'delete', 'count':10, delete:info }, function(data){FinalizeDelete(data, id)});
	}
}

function FillClass(id)
{
	if (confirm('Are you sure you want to fill this class?'))
	{
		var info = '{"info":[{' + bJSONS("Pid", id) + '}]}';
		
		jQuery.post ('admin-ajax.php', { 'action':'do_ajax', 'fn':'fill', 'count':10, fill:info }, function(data){FinalizeFill(data, id)});
	}
}

function FinalizeAdd(data)
{
	var pid = data.pid;
	var row = jQuery('#row-' + pid);
	
	if (row.length == 0)
	{
		jQuery('#mm_em_eventlist tbody tr:first').before('<tr id="row-' + pid + '" class="success"></tr>');
		row = jQuery('#row-' + pid);
	}
	
	var icon = "icon-off";
	
	if (data.active)
	{
		icon = "icon-ok";
	}
	
	var eventBody = //'<td><a href="#" title="Active"><i class="' + icon + '"></i></a></td>' +
	'<td>' + data.pname + '</td><td>' + data.pdesc.substring(0, 25) + '...</td>' +
	//'<td>' + data.pquant + '</td>' + '<td>' + data.psales + '</td>' +
	'<td>' + data.pend + '</td><td>' + '<a href=\"#\" class=\"btn btnEventEdit\" onclick=\"javascript: EditEvent(' + pid +
	');\" title=\"Edit\">Edit</a> ' +
	'<a href=\"#\" class=\"btn btnEventCopy\" onclick=\"javascript: CopyEvent(' + pid +
	');\" title=\"Copy\">Copy</a> ' +
	'<a href=\"#\" class=\"btn btnEventDelete\" onclick=\"javascript: DeleteEvent(' + pid +
	');\" title=\"Delete\">Delete</a>';// +
	//'<a href=\"#\" class=\"btnFillEvent btn btn-warning\" onclick=\"javascript: FillEvent(' + pid +
	//');\" title=\"Fill Event\">Fill Event</a></td>';
	
	row.html(eventBody);

   	ShowList();
   	ResetAdd();
}

function ResetAdd()
{
	jQuery('#mm_em_event_add_form')[0].reset();
	jQuery('#mm_em_pid').val(-1);
}

function FinalizeFill(data, id)
{	
	jQuery('#row-' + id).hide();
}

function FinalizeDelete(data, id)
{	
	jQuery('#row-' + id).hide();
}

function FinalizeEdit(data)
{
	PopulateEvent(data);
	jQuery('#mm_em_pid').val(data.pid);
	ShowAdd();
}

function FinalizeCopy(data)
{
	PopulateEvent(data);
	jQuery('#mm_em_pid').val(-1);
	ShowAdd();
}

function FinalizeOptions(data)
{
	alert("Settings have been saved!");
}

function PopulateEvent(data)
{
	jQuery('#mm_em_pname').val(data.pname);
	
	//jQuery('#mm_em_pdesc').val(data.pdesc);
	//jQuery('#mm_em_pcost').val(data.pcost);
	jQuery('#mm_em_pquant').val(data.pquant);
	jQuery('#mm_em_pnquant').val(data.pnquant);
	jQuery('#mm_em_pstart').val(data.pstart);
	jQuery('#mm_em_pend').val(data.pend);	
	jQuery('#mm_em_plocation').val(data.location);
	
	if (data.registration == 1)
	{
		jQuery('#mm_em_registration').prop('checked', true);
	}
		
	tinyMCE.activeEditor.setContent(data.pdesc);
}

function bJSONS(key, value)
{
	return "\"" + key + "\": \"" + value + "\"";
}

jQuery(document).ready(function($) {
	Start();
});