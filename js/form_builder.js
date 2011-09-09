/**
 * startup code and global variables.  Load first
 */

	var field_count = 0;
	var fields = new Array(); // array to hold field info
	var field_info = new Array(); // temporary holder to insert into fields

	$("#form_builder").submit(function() {
		form_builder_submit();
		return false;
	});


	/**
	 * activate the tabs
	 */
	$(function() {
		$( "#tabs" ).tabs();
	});



	$('#add_field').click(function() {
		post_data = {
			current_count: field_count,
			ci_csrf_token: $.cookie("ci_csrf_token"),
			name : $('#field_name').val(),
			label : $('#field_label').val(),
			type : $('#field_type').val(),
			size : $('#field_size').val(),
			maxlength : $('#field_maxlength').val(),
			validation : $('#field_validation').val()
		}
		$.post("/tools/ajax_form_builder_new_field", post_data, function(data) {
			$('#insert_point').before(data);
			add_field_info();
		});
		// prevent click from doing anything but what we programmed
		return false;
	});

	/**
	 * checks to see if a value exists in a variable, used in import_database()
	 */
	function check_error2(check_me, location, error, valid) {
		if (check_me.length == 0) {
			valid = false;
			set_error(location, 'Error with importing, invalid '+error);
		} else {
			clear_error(location);
		}
		return valid;
	}// check_error


	/**
	 * imports a database table
	 */
	$('#import_table').click(function() {
		import_database();
		return false;
	});
	
	/**
	 * imports a database table
	 */
	function import_database() {
		var db_table = '';
		var db_name = '';
		var db_user = '';
		var db_password = '';
		var db_hostname = '';
		var valid = true;
		var field_type = 'text';
		
		db_table = $('#db_table').val();
		db_name = $('#db_name').val();
		db_user = $('#db_user').val();
		db_password = $('#db_password').val();
		db_hostname = $('#db_hostname').val();
		
		
		valid = check_error2(db_table, '#db_table_error', 'table name', valid);
		valid = check_error2(db_name , '#db_name_error', 'database name', valid);
		valid = check_error2(db_user , '#db_user_error', 'user name', valid);
		valid = check_error2(db_password, '#db_password_error', 'password', valid);
		valid = check_error2(db_hostname, '#db_hostname_error', 'hostname', valid);

		if (valid) {
			$.ajax({
				url: "/tools/ajax_database_structure",
				async: false,
				type: "POST",
				dataType: 'json',
				data: {
					table: db_table,
					database: db_name,
					user: db_user,
					password: db_password,
					hostname: db_hostname
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$("#db_status").html(errorThrown);
					$("#db_status").addClass('error');
				},
				success: function(msg) {
					$("#db_status").removeClass('error');
					$("#db_status").addClass('info');
					$("#db_status").html('Importing table...');
					
					for (field in msg) {
						switch (msg[field]['type'])
						{
							case 'int':
							case 'smallint':
							case 'mediumint':
							case 'bigint':
							case 'float':
							case 'double':
							case 'decimal':
							case 'integer':
							case 'numeric':
							case 'dec':
							case 'fixed':
							case 'real':
								field_type = 'number';
								break;
							case 'text':
							case 'blob':
							case 'tinyblob':
							case 'mediumblob':
							case 'longblob':
							case 'tinytext':
							case 'mediumtext':
							case 'longtext':
								field_type = 'textarea';
								break;
							case 'date':
							case 'datetime':
								case 'timestamp':
								break;
							default:
								field_type = 'text';
								break;
						}
						if (msg[field]['key'] == 'PRI') {
							// primary index is keyfield, not editable
							field_type = 'hidden';
						}
						post_data = {
							ci_csrf_token: $.cookie("ci_csrf_token"),
							name : msg[field]['column'],
							label : msg[field]['column'],
							type : field_type,
							validation : 'trim',
							maxlength: null,
							size: null
						}
						if (msg[field]['max_length'] != null) {
							post_data['maxlength'] = msg[field]['max_length'];
						}
						if (field_type == 'text') {
							if (msg[field]['max_length'] != null) {
								post_data['size'] = field['max_length'];
							}
						}
						if (msg[field]['extra'] != 'auto_increment') {
							// AI fields are not added, they are auto increment
							// insert
							$.ajax({
								url: "/tools/ajax_form_builder_new_field",
								async: false,
								type: "POST",
								data: post_data,
								error: function(jqXHR, textStatus, errorThrown) {
									$("#db_status").html(errorThrown);
									$("#db_status").addClass('error');
								},
								success: function(msg) {
									$('#insert_point').before(msg);
									bulk_add_field_info(
										post_data['name'],
										post_data['label'],
										post_data['type'],
										post_data['validation'],
										post_data['size'],
										post_data['max_length']
									);
								}
							});
						} // endif
						
					} // for
					$("#db_status").html('Import completed...');
				}
			});			
		}
	} // import_database
	
	