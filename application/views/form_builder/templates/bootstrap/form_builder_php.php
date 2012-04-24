<?php
	$exploded = explode('/', $form_action);
	$function_base = $exploded[count($exploded) - 1];
	$function_name = $function_base .'()';
	$rules = '';
	$vars = '';
	$error_array_contents = '';
	$rules_format = "\t\t".'$rules[] = array(\'field\' => \'%s\', \'label\' => \'%s\', \'rules\' => \'%s\');'."\n";
	$var_format = "\t\t".'$variables[\'%s\'] = $this->input->post(\'%s\', TRUE);'."\n";
	$error_array_format = '			\'%s\' => form_error(\'%s\'),'."\n";
		
	foreach ($fields as $field_info) {
		$rules .= sprintf($rules_format, $field_info['name'], $field_info['label'], $field_info['validation']);
		$vars .= sprintf($var_format, $field_info['name'], $field_info['name']);
		$error_array_contents .= sprintf($error_array_format, $field_info['name'], $field_info['name']);
	}

	
?>
	/**
	 * <?php echo $function_name; ?> 
	 * 
	 */
	function <?php echo $function_name; ?> {
		$this->load->library('form_validation');
		$is_ajax = $this->input->is_ajax_request();
		$ajax_error = FALSE;

<?php echo $rules; ?>

		// load any variables to refill the form
<?php echo $vars; ?>
		// alternately you can load them this way, if you are preloading an item from a database, and
		// you just want to overwrite the changed variables...
		/*
		$variables = $this->model_name->get($id);
		$posted = $this->input->post(NULL, TRUE);
		if ($posted) {
			foreach ($posted as $key => $value) {
				$variables[$key] = $value;
			}
		}
		*/
		
	
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == FALSE) {
			// first load or failed form
			$error = array(
			<?php echo $error_array_contents; ?>
				);
			$variables['error'] = $error;
			if ($is_ajax) {
				if (count($error) > 0) {
					$ajax_error = TRUE;
				}
				$message = $error;
			} else {
				// 
				// change the view name to the name of what you save the view file as
				//
				$message = $this->load->view('<?php echo $function_base; ?>', $variables, TRUE);
			}
		} else {
			// perform action based on submission of form, if you return $message in this manner
			// it defaults to replacing the form in the page with the message that is returned.
			$message = '<p>You just successfully submitted <strong>this</strong> form!</p>';
		}
		// this is where you display results.  javascript version or full template version
		if ($is_ajax) {
			if ($ajax_error) {
				echo json_encode($message);
			} else {
				echo json_encode(array('form_message' => $message));
			}
		} else {
			//load view here, or render if using a template library
			$config['content'] = $message;
			$this->template->add_script('<?php echo $form_name; ?>.js');
			$this->template->render($config); 
		}
	
	} // <?php echo $function_name; ?>
