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

