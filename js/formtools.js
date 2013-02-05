function FormToolsSetup()
{	
	CheckScripts();
}

function CheckScripts()
{
	if(typeof(jQuery)=='undefined'){
		var loadjQuery = document.createElement("script");
		loadjQuery.setAttribute("type","text/javascript");
		loadjQuery.setAttribute("src","jquery-1.8.2.min.js");
		document.getElementsByTagName("head")[0].appendChild(loadjQuery);
	}
}

function ValidateForm(Form)
{
	var FormID = jQuery(Form).attr('id');
	jQuery('#' + FormID + ' .error').each(function() {jQuery(this).removeClass('error');})
	
	var ErrorFields = new Array();
	var IsValid = true;
	
	ErrorFields = CheckRequiredFields(FormID, ErrorFields);
	
	if (ErrorFields.length == 0)
	{
		ErrorFields = CheckMatchingFields(FormID, ErrorFields);
		
		if (ErrorFields.length == 0)
		{
			ErrorFields = CheckNonZeroFields(FormID, ErrorFields);
			
			if (ErrorFields.length == 0)
			{
				//console.log('Checking email fields');
				ErrorFields = CheckEmailFields(FormID, ErrorFields);
				
				if (ErrorFields.length != 0)
				{
					IsValid = false;
				}
			}
			else
			{
				IsValid = false;
			}
		}
		else
		{
			IsValid = false;
		}
	}
	else {		
		IsValid = false;
	}
	
	if (IsValid == false)
	{
		Handle(Form, ErrorFields);

		jQuery('#mm-contact-dialog-title').html('Oops!');
		jQuery('#mm-contact-dialog-message').html('It looks like some of the fields you entered were incomplete or not formatted properly.  Please fix any fields highlighted in red and try submitting again.');
		ShowModal('mm-contact-dialog');
	}
	
	return IsValid;
}

function CheckRequiredFields(FormID, ErrorFields)
{	
	var fields = jQuery('#' + FormID + ' .req');
	var ErrorCount = ErrorFields.length;
	
	for (var i = 0; i < fields.length; i++)
	{
		var x = jQuery(fields[i]);
		
		if (isEmpty(x.val()) && !hasAttr(x, "disabled"))
		{
			ErrorFields[ErrorCount++] = x;
		}
	}
	
	return ErrorFields;
}

function CheckNonZeroFields(FormID, ErrorFields)
{
	var fields = jQuery('#' + FormID + ' .nonzero');
	var ErrorCount = ErrorFields.length;
	
	var ZeroCount = 0;
	
	for (var i = 0; i < fields.length; i++)
	{
		var x = jQuery(fields[i]);
		
		if (x.val() == '0' && !hasAttr(x, "disabled"))
		{
			ZeroCount++;
		}
	}
	
	if (ZeroCount == fields.length)
	{
		jQuery('#' + FormID + ' .nonzero').each(function() {ErrorFields[ErrorCount++] = jQuery(this);});
	}
	
	return ErrorFields;
}

function CheckEmailFields(FormID, ErrorFields)
{
	var fields = jQuery('#' + FormID + ' .email');
	var ErrorCount = ErrorFields.length;
	
	//console.log('Checking email fields: ' + fields.length);
	
	for (var i = 0; i < fields.length; i++)
	{
		var x = jQuery(fields[i]);
		
		if (!isEmail(x.val()) && !hasAttr(x, "disabled"))
		{
			ErrorFields[ErrorCount++] = x;
		}
	}
	
	return ErrorFields;
}

function CheckMatchingFields(FormID, ErrorFields)
{	
	var fields = jQuery('#' + FormID + ' .match');
	var ErrorCount = ErrorFields.length;
	
	for (var i = 0; i < fields.length; i++)
	{
		var x = jQuery(fields[i]);
		
		for (var j = 0; j < fields.length; j++)
		{
			var y = jQuery(fields[j]);
			
			if (!FieldsMatch(x, y) && !hasAttr(x, "disabled") && !hasAttr(y, "disabled"))
			{
				ErrorFields[ErrorCount++] = x;
				j = fields.length;
			}
		}
	}
	
	return ErrorFields;
}

function FieldsMatch(field1, field2)
{
	var Match = field1.val() == field2.val();
	
	return Match;
}

/* Error Handling */
function AddErrorClass(Field)
{
	if (!Field.hasClass('error'))
	{
		Field.addClass('error');
	}
}

function bJSONS(key, value)
{
	return "\"" + key + "\": \"" + value + "\"";
}

function isEmpty(value)
{
	return value == '';
}

function isEmail(value)
{
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(value);
}

function hasAttr(Field, attrName)
{
	var hasAttr = false;
	var attr = jQuery(Field).attr(attrName);

	if (typeof attr !== 'undefined' && attr !== false) {
    	hasAttr = true;
	}
	
	return hasAttr;
}

jQuery.fn.hasAttr = function(name) {  
   return this.attr(name) !== 'undefined' && this.attr !== false;
};

function Handle(Form, ErrorFields)
{
	var Fields = '';
	for (var i = 0; i < ErrorFields.length; i++)
	{
		var x =	jQuery(ErrorFields[i]).parent().parent();
		
		AddErrorClass(x);
	}
}

function GetCalEventUrl(calid)
{
	var info = {id : calid};

	jQuery.post ('../wp-admin/admin-ajax.php', { 'action':'do_ajax', 'fn':'calid', 'count':10, calid : info }, function(data){GotCalEventUrl(data)});
}

function GotCalEventUrl(data)
{
	if (data)
	{
		window.location.href = data;
	}
}

Number.prototype.formatMoney = function(c, d, t){
var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "jQuery1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };

/* Form Tools Setup */
jQuery(document).ready(function() {
	FormToolsSetup();
});

/* Registration Form Logic */
function IsChecked(id)
{
	return jQuery('#' + id).attr('checked')?true:false;
}

function SetGuestListDisplay()
{
	if (IsChecked('txtGuests'))
	{
		jQuery('#guest-list input').removeAttr('disabled');
	}
	else
	{
		jQuery('#guest-list input').attr('disabled', true);
	}
}

/* Conflict Fixes */
function HideModal(id)
{
	jQuery('#' + id).css('display', 'none');
	jQuery('#simplemodal-overlay').css('display', 'none');
}

function ShowModal(id)
{
	jQuery('#simplemodal-overlay').css('display', 'block');
	jQuery('#' + id).css('display', 'block');
}

function SetupContactForm()
{
	//console.log('Form Setup');
	jQuery('#mm-contact-reset').click(function(e) {e.preventDefault(); document.getElementById('mm-contact-form').reset()});
	jQuery('#mm-contact-send').click(function(e) {e.preventDefault(); SendMessage();});
	//console.log('Form Setup Complete');
}

function SendMessage()
{
	if (ValidateForm('#mm-contact-form'))
	{
		var url = jQuery('#mm-contact-ajaxurl').val();
		var selTo = jQuery('#selNames').val();
		var txtName = jQuery('#txtName').val();
		var txtEmail = jQuery('#txtEmail').val();
		var txtMessage = jQuery('#txtMessage').val();
		
		var info = {to: selTo, name: txtName, email: txtEmail, message: txtMessage};
	
		jQuery.post (url, { 'action':'do_ajax', 'fn':'email', 'count':10, email:info }, function(data){SentMessage(data)});
	}
}

function SentMessage(data)
{
	if (data)
	{
		jQuery('#mm-contact-dialog-title').html('Message Sent!');
		jQuery('#mm-contact-dialog-message').html(data);
		ShowModal('mm-contact-dialog');
	}
	
	document.getElementById('mm-contact-form').reset();
}

function Register()
{
	if (ValidateForm('#mm-register-form'))
	{	
		var url = jQuery('#mm-register-ajaxurl').val();
		var txtName = jQuery('#txtName').val();
		var txtEmail = jQuery('#txtEmail').val();
		var bllGuests = IsChecked('txtGuests');
		var txtNumGuests = jQuery('#txtGuestCount').val();
		var txtGuestNames = jQuery('#txtGuestList').val();
		var txtEventID = jQuery('#mm-event-id').val();
		
		var info = {name: txtName, email: txtEmail, guests : bllGuests, numGuests : txtNumGuests, guestNames : txtGuestNames, eventID : txtEventID};
	
		jQuery.post (url, { 'action':'do_ajax', 'fn':'register', 'count':10, register:info }, function(data){Registered(data)});
	}
}

function Registered(data)
{
	HideModal('eventRegister');

	if (data)
	{
		jQuery('#mm-contact-dialog-title').html('Registration Sent!');
		jQuery('#mm-contact-dialog-message').html(data);
		ShowModal('mm-contact-dialog');
	}
	
	document.getElementById('mm-register-form').reset();
	SetGuestListDisplay();
}