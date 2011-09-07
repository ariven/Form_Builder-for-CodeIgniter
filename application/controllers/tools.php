<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tools extends CI_Controller {

	/**
	 * _wrap_output wraps the output in a textarea html tag
	 * 
	 * @author patrick (5/20/2011)
	 * 
	 * @param $variables 
	 */
	function _wrap_output($text) {
		$count = count(explode("\n", $text));
		$wrapper = sprintf('<textarea cols="100" rows="%s" wrap="off" style="font-family: \'Courier New\', Courier, monospace;font-size: 11px;">%s</textarea>', $count, $text);
		return $wrapper;
	}

	/**
	 * _generate_php_output generates the php content
	 * 
	 * @author patrick (5/20/2011)
	 * 
	 * @param $variables 
	 */
	function _generate_php_output($variables) {
		$data = $variables;
		$field_count = $variables['field_count'];
		$return_string = '';
		$field_data = unserialize($variables['field_data']);
		foreach ($field_data as $field_info) {
			$data['fields'][] = $field_info;
		}
		$template_dir = 'form_builder/templates/'.$variables['template'];

		$return_string .= $this->load->view($template_dir.'/form_builder_php', $data, TRUE);
		return $return_string;
	} // _generate_php_output
	
	/**
	 * generate_javascript_output generates the javascript content
	 * 
	 * @author patrick (5/20/2011)
	 * 
	 * @param $variables 
	 */
	function _generate_javascript_output($variables) {
		$data = $variables;
		$field_count = $variables['field_count'];
		$return_string = '';
		$field_data = unserialize($variables['field_data']);
		foreach ($field_data as $field_info) {
			$data['fields'][] = $field_info;
		}

		$template_dir = 'form_builder/templates/'.$variables['template'];

		$return_string = $this->load->view($template_dir.'/form_builder_javascript', $data, TRUE);
		return $return_string;
	} // _generate_javascript_output

	/**
	 * _generate_html_output generates the html output
	 * 
	 * @author patrick (5/20/2011)
	 * 
	 * @param $variables 
	 */
	function _generate_html_output($variables) {
		$this->load->helper('form');
		$data = $variables;
		$field_count = $variables['field_count'];
		$return_string = '';
		$attributes = array('id' => $variables['form_name']);
		$return_string .= sprintf('<?php echo form_open(\'%s\', array(\'id\' => \'%s\')); ?>'."\n", $variables['form_action'], $variables['form_name']);
		$template_dir = 'form_builder/templates/'.$variables['template'];
		$field_data = unserialize($variables['field_data']);
		foreach ($field_data as $field_info) {
			$data['field_name'] = $field_info['name'];
			$data['field_label'] = $field_info['label'];
			$data['field_type'] = $field_info['type'];
			$data['size'] = $field_info['size'];
			$data['maxlength'] = $field_info['maxlength'];
			$return_string .= $this->load->view($template_dir.'/form_builder_item', $data, TRUE);
		}
		$return_string .= $this->load->view($template_dir.'/submit_button', $data, TRUE);
		$return_string .= '</form>';
		return $return_string;
	} // _generate_html_output

	/**
	 * _form_builder_get_template_list - generates a list of template directories
	 * 
	 * @author Patrick Spence (5/21/2011)
	 */
	function _form_builder_get_template_list() {
		$this->load->helper('directory');
		$base_dir = FCPATH.'application/views/form_builder/templates';
		
		$files = directory_map($base_dir);
		$format = '<option value="%s" %s>%s</option>';
		$select = '<select name="template" id="template">';
		foreach ($files as $key => $item) {
			if (is_array($item)) {
				if ($key == 'generic') {
					$selected = 'SELECTED';
				} else {
					$selected = '';
				}
				$template_name = file_get_contents($base_dir.'/'.$key.'/template_name.txt');
				$select .= sprintf($format, $key, $selected, $template_name);
			}
		}
		$select .= '</select>';
		return $select;
	}// _form_builder_get_template_list
	
	/**
	 * form_builder - builds a form
	 * 
	 * @author Patrick Spence (5/21/2011)
	 */
	function form_builder() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$is_ajax = $this->input->is_ajax_request();

		$this->form_validation->set_rules('form_name', 'Form Name', 'required|trim');
		$this->form_validation->set_rules('form_action', 'Form Action', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$variables['form_name'] =  $this->input->post('form_name', TRUE);
		$variables['form_action'] =  $this->input->post('form_action', TRUE);
		$variables['field_count'] = $this->input->post('field_count', TRUE);
		$variables['template'] = $this->input->post('template' , TRUE);
		$variables['field_data'] = $this->input->post('field_data', TRUE);
		for ($loop = 1; $loop <= $variables['field_count']; $loop++) {
			$variables['field_'.$loop] = $this->input->post('field_'.$loop, TRUE);
			$variables['label_'.$loop] = $this->input->post('label_'.$loop, TRUE);
		}
		$variables['templates'] = $this->_form_builder_get_template_list();
		
		if ($this->form_validation->run() == FALSE) {
			$error = array(
				'form_name' => form_error('form_name'),
				'form_action' => form_error('form_action'),
				);
			$variables['error'] = $error;
			if ((strlen($error['form_name']) > 0) or (strlen($error['form_action']) > 0)) {
				$variables['message'] = '<div class="error">There was an error</div>';
			} else {
				$variables['message'] = '';
			}
			
			if ($is_ajax) {
				$config['content'] = json_encode($error);
			} else {
				$config['content'] = $this->load->view('form_builder/form_builder', $variables, TRUE);
			}
		} else {
			$process['php_output'] = $this->_wrap_output($this->_generate_php_output($variables));
			$process['html_output'] = $this->_wrap_output($this->_generate_html_output($variables));
			$process['javascript_output'] = $this->_wrap_output($this->_generate_javascript_output($variables));
			$process['message'] = sprintf('<div class="info">Form processed, %s field(s) produced.</div>', $variables['field_count']);
			if ($is_ajax) {
				$fd = unserialize($this->input->post('field_data'));
				$process['debug'] = '<pre>'.print_r($fd, TRUE).'</pre>';
				
				$config['content'] = json_encode($process);
			} else {
				$config['content'] ='<pre>'.print_r($_POST, TRUE).'</pre>';
			}
		}
		// we add the time() to force no caching during development.  This can be removed in production.
		$config['javascript'][] = 'form_builder.js?t='.time();
		$config['javascript'][] = 'form_builder_functions.js?t='.time();
		$config['javascript'][] = 'array_functions.js?t='.time();
		
		if ($is_ajax) {
			echo $config['content'];
		} else {
			$this->load->view('template', $config);
		}

	} // form_builder

	// ////////////////////////////////////////////////////////////////////
	// // AJAX Functions //////////////////////////////////////////////////
	// ////////////////////////////////////////////////////////////////////
	
	/**
	 * ajax_form_builder_new_field returns a snippet of html for a new form field
	 * 
	 * @author patrick (5/19/2011)
	 */
	function ajax_form_builder_new_field() {
		$this->load->helper('form');
		$field_name = $this->input->post('name', TRUE);
		$field_label = $this->input->post('label', TRUE);
		$field_type = $this->input->post('type', TRUE);
		$field_size = $this->input->post('size', TRUE);
		$field_max = $this->input->post('maxlength', TRUE);
		$field_validation = $this->input->post('validation', TRUE);
		if ($field_name <> FALSE && $field_label <> FALSE && $field_type <> FALSE) {
			$config['field_name'] = $field_name;
			$config['field_label'] = $field_label;
			$config['field_type'] = $field_type;
			$config['field_validation'] = $field_validation;
			if (is_numeric($field_size)) {
				$config['size'] = $field_size;
			}
			if (is_numeric($field_max)) {
				$config['maxlength'] = $field_max;
			}
			$snippet = $this->load->view('form_builder/form_builder_single_item', $config, TRUE);
		} else {
			$snippet =  '';
		}
		echo $snippet;
	} // ajax_form_builder_new_field
	
	/**
	 * returns JSON database structure of named table in current database. To be used for auto generation
	 * of forms
	 */
	function ajax_database_structure() {
		$table_name = $this->input->post('table', TRUE);
		$database_name = $this->input->post('database', TRUE);
		$username = $this->input->post('user', TRUE);
		$password = $this->input->post('password', TRUE);
		$hostname = $this->input->post('hostname', TRUE);
		$exclude_ai = $this->input->post('exclude_ai', TRUE);

		if ($table_name && $database_name && $username && $password && $hostname) {
			// load alternate database
			$config['hostname'] = $hostname;
			$config['username'] = $username;
			$config['password'] = $password;
			//$config['database'] = $database_name;
			$config['database'] = 'information_schema';
			$config['dbdriver'] = "mysql";
			$config['dbprefix'] = "";
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			$config['cache_on'] = FALSE;
			$config['cachedir'] = "";
			$config['char_set'] = "utf8";
			$config['dbcollat'] = "utf8_general_ci";
			//$db = $this->load->database($config);
			$this->load->database($config);
			$this->db->where('table_name', $table_name);
			$this->db->where('table_schema', $database_name);
			$request = $this->db->get('columns');
			$rows = $request->result_array();
			$columns = array();
			foreach ($rows as $data) {
				$row_data = array();
				$exclude = FALSE;
				if ($data['EXTRA'] == 'auto_increment') { // exclude auto increment fields
					if ($exclude_ai) {
						$exclude = TRUE;
					}	
				}
				if (! $exclude) {
					$row_data['column'] = strtolower($data['COLUMN_NAME']);
					$row_data['type'] = $data['DATA_TYPE'];
					$row_data['max_length'] = $data['CHARACTER_MAXIMUM_LENGTH'];
					$row_data['key'] = $data['COLUMN_KEY'];
					$row_data['extra'] = $data['EXTRA'];
					$columns[] = $row_data;
				}
			}
			echo json_encode($columns);
		} else {
		}
	}// ajax_database_structure	
	
	
} // class tools

