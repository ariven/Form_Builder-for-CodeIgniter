/**
 * common functions, load second 
 *  
 */

	function message(the_message) {
		$('#form_messages').html(the_message);
	} // message

	function error_message(the_message) {
		$('#form_messages').html('<div class="error">'+the_message+'</div>');
	}
	function check_var(which, valid) {
		var which_value = $(which).val();
		if (which_value.length == 0) {
			return false;
		} else {
			return valid;
		}
	}// check_var


	function check_error(data, location) {
		if (data) {
			$(location).html(data);
		} else {
			$(location).html('');
		}
	}

	function set_error(location, message) {
		$(location).html('<span class="error">'+message+'</span>')
	} // set_error

	function clear_error(location) {
		$(location).html('')
	} // clear_error

	function form_builder_submit() {
		// array has been built as we go
		// serialize array
		// add serialized data to form into field field_data

		var valid = true; 
		var err_type = '';

		message('<div class="note">Processing...</div>');
		if (field_count < 1) {
			valid = false;
			err_type += '<br />insufficient fields';
		}
		var form_name = $('#form_name').val();
		var form_action = $('#form_action').val();
		
		valid = check_var('#form_name', valid);
		if (valid == false) {
			err_type += '<br />You need a form name.';
		}
		valid = check_var('#form_action', valid);
		if (valid == false) {
			err_type += '<br />You need a form action.';
		}
		if (valid) {
			var serialized_data = array_serialize(fields);
			
			submit_data = {
				form_name: form_name,
				form_action: form_action,
				field_count: field_count,
				template: $('#template').val(),
				field_data: serialized_data,
				ci_csrf_token: $.cookie("ci_csrf_token")
			}
			$.ajax( {
					url: "/tools/form_builder",
					data: submit_data,
					type: "POST",
					dataType: "json",
					error: function(jqXHR, textStatus, errorThrown) {
						var err_mess = 'Error: ';
						err_mess = err_mess + textStatus + ' - ' + errorThrown + "<br />";
						err_mess = err_mess + "response text: " + "<br />" + jqXHR['responseText'] + "<br /><br />";
						// $("#debug_point").html(err_mess);
						
					},
					success: function(data, textStatus, jqXHR) {
						check_error(data.form_name, "#form_name_error");
						check_error(data.form_action, "#form_action_error");
						check_error(data.php_output, "#php_output");
						check_error(data.html_output, "#html_output");
						check_error(data.javascript_output, "#javascript_output");
						check_error(data.message, "#form_messages");
						// check_error(data.debug, "#debug_point");
					}
			});
			
		} else {
			$('#form_messages').html('<div class="error">There was an error with your submission. '+err_type+' </div>');
		}
	}// form_builder_submit

	/**
	 * resets the 4 fields back to default values
	 * 
	 * @author patrick (5/25/2011)
	 */
	function reset_fields() {
		$('#field_name').val('');
		$('#field_label').val('');
		$('#field_type').val('');
		$('#field_validation').val('trim|required');
		$('#field_size').val('');
		$('#field_maxlength').val('');

	}// clear_fields

	/**
	 * adds field info to array
	 * 
	 * @author patrick (5/25/2011)
	 */
	function add_field_info () {
		var valid = true;

		field_info['name'] = $('#field_name').val();
		field_info['label'] = $('#field_label').val();
		field_info['type'] = $('#field_type').val();
		field_info['validation'] = $('#field_validation').val();
		field_info['size'] = $('#field_size').val();
		field_info['maxlength'] = $('#field_maxlength').val();
		
		if (field_info['name'].length == 0) {
			valid = false;
			set_error('#field_name_error', 'Field name can not be empty');
		} else {
			clear_error('#field_name_error');
		}
		if (field_info['label'].length == 0) {
			valid = false;
			set_error('#field_label_error', 'Field label can not be empty');
		} else {
			clear_error('#field_label_error');
		}
		
		if (valid) {
			field_count = field_count + 1;
			fields[field_count] = new Array();
			fields[field_count]['name'] = $('#field_name').val();
			fields[field_count]['label'] = $('#field_label').val();
			fields[field_count]['type'] = $('#field_type').val();
			fields[field_count]['validation'] = $('#field_validation').val();
			fields[field_count]['size'] = $('#field_size').val();
			fields[field_count]['maxlength'] = $('#field_maxlength').val();

			reset_fields();
		} else {
			error_message('There was a problem adding the field');
		}
	}// add_field_info

	/**
	 * used for bulk importing a database table
	 */
	function bulk_add_field_info (name, label, type, validation, size, maxlength) {
		var valid = true;

		field_info['name'] = name;
		field_info['label'] = label;
		field_info['type'] = type;
		field_info['validation'] = validation;
		field_info['size'] = size;
		field_info['maxlength'] = maxlength;

		// no error checking of name and label, since this is a bulk import from database table
		if (valid) {
			field_count = field_count + 1;
			fields[field_count] = new Array();
			fields[field_count]['name'] = name;
			fields[field_count]['label'] = label;
			fields[field_count]['type'] = type;
			fields[field_count]['validation'] = validation;
			fields[field_count]['size'] = size;
			fields[field_count]['maxlength'] = maxlength;

			reset_fields();
		} else {
			error_message('There was a problem adding the field');
		}
	}// bulk_add_field_info