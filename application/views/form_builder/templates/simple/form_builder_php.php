<?php
	$exploded = explode('/', $form_action);
	$function_base = $exploded[count($exploded) - 1];
	$function_name = $function_base .'()';
	$rules = '';
	$vars = '';
	$error_array_contents = '';
	$rules_format = '	$rules[] = array(\'field\' => \'%s\', \'label\' => \'%s\', \'rules\' => \'%s\');'."\n";
	$var_format = '		$variables[\'%s\'] = $this->input->post(\'%s\', TRUE);'."\n";
	$error_array_format = '			\'%s\' => form_error(\'%s\'),'."\n";
		
	foreach ($fields as $field_info) {
		$rules .= sprintf($rules_format, $field_info['name'], $field_info['label'], $field_info['validation']);
		$vars .= sprintf($var_format, $field_info['name'], $field_info['name']);
		$error_array_contents .= sprintf($error_array_format, $field_info['name'], $field_info['name']);
	}
	/*
	for ($loop = 1; $loop <= $field_count; $loop++) {
		$f_name = 'field_'.$loop;
		$l_name = 'label_'.$loop;
		$rules .= sprintf($rules_format, $$f_name, $$l_name);
		$vars .= sprintf($var_format, $$f_name, $$f_name);
		$error_array_contents .= sprintf($error_array_format, $$f_name, $$f_name);
	}
	*/
	
?>
/**
 * <?php echo $function_name; ?> 
 * 
 */
function <?php echo $function_name; ?> {
	$this->load->library('form_validation');
	$is_ajax = $this->input->is_ajax_request();

<?php echo $rules; ?>

	$this->form_validation->set_rules($rules);
	if ($this->form_validation->run() == FALSE) {
		// first load or failed form
		// load any variables to refill the form
<?php echo $vars; ?>
		$error = array(
<?php echo $error_array_contents; ?>
			);
		$variables['error'] = $error;
		if ($is_ajax) {
			$config['content'] = json_encode($error);
		} else {
			// 
			// change the view name to the name of what you save the view file as
			//
			$config['content'] = $this->load->view('<?php echo $function_base; ?>', $variables, TRUE);
		}
	} else {
		// perform action based on submission of form, if you return $message in this manner
		// it defaults to replacing the form in the page with the message that is returned.
		$message = '<p>You just successfully submitted <strong>this</strong> form!</p>';
		if ($is_ajax) {
			$config['content'] = json_encode(array('form_message' => $message));
		} else {
			$config['content'] = $message;
		}
	}
	if ($is_ajax) {
		echo $config['content'];
	} else {
		$config['javascript'] = '<?php echo $form_name; ?>.js';
		$this->load->view('template', $config);
	}

} // <?php echo $function_name; ?>
