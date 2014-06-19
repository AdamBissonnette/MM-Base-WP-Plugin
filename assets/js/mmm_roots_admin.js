var activeUpload = null;
var storeSendToEditor = null;
var newSendToEditor = null;

function SaveOptions(form)
{
	var formdata = jQuery(form).serializeArray()
	jQuery.post ('admin-ajax.php',
		 { 'action':'do_ajax', 'fn':'settings', 'count':10, settings:formdata },
		  function(data){FinalizeOptions(data)},
		   "json");
}

function FinalizeOptions(data)
{
	if (data == 0)
	{
		ShowModal('Settings Saved!', '<p>The settings have been saved!</p>');
	}
	else
	{
		ShowModal('Oops!', '<p>Something unexpected happened... settings might have been saved... open this page in a second window and see?</p>');
	}
}

function SetupUploadControls()
{
	storeSendToEditor = window.send_to_editor;

	newSendToEditor = function(html) {
	 imgurl = jQuery('img',html).attr('src');
	 jQuery(activeUpload).val(imgurl);
	 tb_remove();
	 window.send_to_editor = storeSendToEditor;
	}

	jQuery('.image_uploader a').click(function(event) {
		window.send_to_editor = newSendToEditor;
		activeUpload = jQuery(event.target).prev();

		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
}

function SetupSelects()
{
	jQuery(".mmm-select").select2();
	jQuery(".mmm-select-multi").select2Sortable();
}


function SetupSaveEvents()
{
	jQuery(document.forms).bind("submit", function() {
		UpdateTextFieldFromSelect();
	});
}

function UpdateTextFieldFromSelect()
{
	var selects = jQuery('.mmm-select-multi');

    jQuery.each(selects, function() {
    	var curSelect = jQuery(this);

 		var curTextID = curSelect.prop("id").replace('mmm-select-','');;
    	var curText = jQuery('#' + curTextID);

    	var selectValue = curSelect.select2SortableOrder().val();

    	if (selectValue != null)
    	{
    		var joinedSelectValue = selectValue.join(",");
    	}
    	
		curText.val(joinedSelectValue);
    });
}


jQuery(document).ready(function($) {
	$('#btnOptionsSave').click(function(e) {
		e.preventDefault();

		UpdateTextFieldFromSelect();

		if (ValidateForm("form#theme_settings"))
		{
			SaveOptions(jQuery("form#theme_settings"));
		}
	});

	SetupUploadControls();
	SetupSelects();
	SetupSaveEvents();
});