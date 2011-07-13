<?php
	$field_name_php = sprintf('<?php echo $%s; ?>', $field_name);
	$field_label_php = sprintf('<?php echo $%s; ?>', $field_label);
	$field_name_set_value = sprintf('<?php echo set_value(\'%s\'); ?>', $field_name);

	// we use HTML5 tags too, non-supporting browsers treat them like type "text"
	switch ($field_type) {
	case 'text': 
		$format = '<input type="text" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'checkbox': 
		$format = '<input type="checkbox" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'radio': 
		$format = '<input type="radio" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'password': 
		$format = '<input type="password" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'select': 
		// only make one option, end user will have to fill the rest in
		$format = "<select name=\"%2$s\" id=\"%2$s\" />\n\t<option value=\"%$1s\" selected>%$1s</option>\n</select>";
		break;
	case 'file': 
		$format = '<input type="file" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'email': 
		$format = '<input type="email" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'url': 
		$format = '<input type="url" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'number': 
		$format = '<input type="number" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'range': 
		$format = '<input type="range" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'date': 
		$format = '<input type="date" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'search': 
		$format = '<input type="search" value="%1$s" name="%2$s" id="%2$s" />';
		break;
	case 'textarea': 
		$format = '&lt;textarea name="%2$s" id="%2$s" &gt;%1$s&lt;/textarea&gt;';
		break;
	case 'editor': 
		// we use .editor as the class to indicate to whatever editor JS solution you use that
		// it should be tagged as an editor.  
		$format = '&lt;textarea name="%2$s" id="%2$s" class="editor"&gt;%1$s&lt;/textarea&gt;';
		break;
	}
	$input_str = sprintf($format, $field_name_set_value, $field_name);
?>
<div class="form_row">
	<span class="form_label"><label for="<?php echo $field_name; ?>"><?php echo $field_label; ?></label></span>
	<span class="form_input">
		<?php echo $input_str; ?>
	</span>
	<span class="form_error" id="<?php echo $field_name;?>_error">
<?php
	echo sprintf('<?php if (isset($error[\'%s\'])) { echo $error[\'%s\']; } ?>', $field_name, $field_name);
?>
</span>
</div>
