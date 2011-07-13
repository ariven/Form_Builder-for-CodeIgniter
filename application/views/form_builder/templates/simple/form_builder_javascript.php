<?php
	$function_name = $form_name.'()';
	$form_full_name = $form_name;

	$errors = '';
	$error_format = '				$("#%s_error").html(data.%s);';

	foreach ($fields as $field_info) {
		$errors .= sprintf($error_format, $field_info['name'], $field_info['name'])."\n";
	}

?>

	// <?php echo $form_full_name; ?> handler
	$("#<?php echo $form_full_name; ?>").submit(function() {
		// add CSRF token
		$('#submit').before('<input type="hidden" name="ci_csrf_token" value="'+$.cookie("ci_csrf_token")+'" />');
		$.post("<?php echo $form_action; ?>", $('#<?php echo $form_full_name; ?>').serialize(), function(data) {
<?php echo $errors; ?>
				if (data.form_message) {
					$('#<?php echo $form_full_name; ?>').html(data.form_message);
				}
			},'json'
		);
		return false;
	});
