<?php
	$field_name_php = sprintf('<?php echo $%s; ?>', $field_name);
	$field_label_php = sprintf('<?php echo $%s; ?>', $field_label);
	$field_name_set_value = sprintf('<?php echo set_value(\'%s\'); ?>', $field_name);
	$size_str = '';
	$insert_count = 2;
	if (isset($size)) {
		$size_str .= sprintf('size="%s" ', $size);
	}
	if (isset($maxlength)) {
		$size_str .= sprintf('maxlength="%s"', $maxlength);
	}
	// we use HTML5 tags too, non-supporting browsers treat them like type "text"
	switch ($field_type) {
	case 'text': 
		$format = '<input type="text" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'checkbox': 
		$format = '<input type="checkbox" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'radio': 
		$format = '<input type="radio" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'password': 
		$format = '<input type="password" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'select': 
		// only make one option, end user will have to fill the rest in
		
		$format = '<select name="%2$s" id="%2$s" />'."\n\t".'<option value="%1$s" selected>%1$s</option>'."\n".'</select>';
		break;
	case 'file': 
		$format = '<input type="file" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'email': 
		$format = '<input type="email" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'url': 
		$format = '<input type="url" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'number': 
		$format = '<input type="number" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'range': 
		$format = '<input type="range" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'date': 
		$format = '<input type="date" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'search': 
		$format = '<input type="search" value="%1$s" name="%2$s" id="%2$s" %3$s />';
		$insert_count = 3;
		break;
	case 'textarea': 
		$format = '<textarea name="%2$s" id="%2$s">%1$s</textarea>';
		break;
	case 'editor': 
		// we use .editor as the class to indicate to whatever editor JS solution you use that
		// it should be tagged as an editor.  
		$format = '<textarea name="%2$s" id="%2$s" class="editor">%1$s</textarea>';
		break;
	}
	if ($insert_count == 2) {
		$input_str = sprintf($format, $field_label, $field_name);
	} else {
		$input_str = sprintf($format, $field_label, $field_name, $size_str); // allow for maxlength/size parameters
	}
?>
<li>
	<label for="<?php echo $field_name; ?>"><?php echo $field_label; ?></label>
	<div class="block">
		<?php echo $input_str; ?>
	</div>
	
</li>
