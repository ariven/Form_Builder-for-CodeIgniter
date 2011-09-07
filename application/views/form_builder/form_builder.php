<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Generation</a></li>
		<li><a href="#tabs-2">HTML</a></li>
		<li><a href="#tabs-3">Javascript</a></li>
		<li><a href="#tabs-4">PHP</a></li>
	</ul>
	<div id="tabs-1">
		<div id="form_messages"> </div>
		<input type="hidden" name="field_count" id="field_count" value="1" />
		<input type="hidden" name="field_data" id="field_data" value="" />
		<div class="row">
			<div class="fivecol">
<?php
			echo form_open('tools/form_builder', array('id' => 'form_builder')); 
?>

				<h2 class="box-title">Form Details</h2>
				<div class="box">
					<ol>
						<li>
							<label for="form_name">Form Name</label>
							<div class="block">
								<input type="text" value="<?php echo set_value('form_name'); ?>" name="form_name" id="form_name" /><br />
								<span id="form_name_error"><?php if (isset($error['form_name'])) { echo $error['form_name']; }  ?></span>
							</div>
							
						</li>
						<li>
							<label for="form_action">Form action</label></span>
							<div class="block">
								<input type="text" value="<?php echo set_value('form_action'); ?>" name="form_action" id="form_action" /><br />
								<span  id="form_action_error"><?php if (isset($error['form_action'])) { echo $error['form_action']; }  ?></span>
							</div>
						</li>
						<li>
							<label for="form_action">Form template</label>
							<div class="block">
								<?php echo $templates; ?><br />
								<span id="template"><?php if (isset($error['templates'])) { echo $error['template_list']; }  ?></span>
							</div>
						</li>
					</ol>
					
				</div>
				<div class="box">
					<ol>
						<li>
							<label for="field_name">Field Name</label>
							<input type="text" id="field_name" name="field_name" />
							<span id="field_name_error"></span>
						</li>
						<li>
							<label for="field_label">Field Label</label>
							<input type="text" id="field_label" name="field_label" />
							<span id="field_label_error"></span>
						</li>
						<li>
							<label for="field_type">Field Type</label>
							<select id="field_type" name="field_type">
								<option value="text">Text Entry</option>
								<option value="checkbox">Checkbox</option>
								<option value="radio">Radio Button</option>
								<option value="password">Password</option>
								<option value="select">Select</option>
								<option value="file">Upload File</option>
								<option value="email">Email Address</option>
								<option value="url">URL</option>
								<option value="number">Number</option>
								<option value="range">Number Slider</option>
								<option value="date">Date</option>
								<option value="search">Search</option>
								<option value="textarea">Text area</option>
								<option value="editor">Editor</option>
							</select>
							<span id="field_type_error"></span>
						</li>
						<li>
							<label for="field_validation">Field Validation</label>
							<input type="text" id="field_validation" name="field_validation" value="trim|required" />
							<span id="field_validation_error"></span>
						</li>
						<li>
							<label for="field_size">Field Size</label>
							<input type="number" id="field_size" name="field_size" />
							<span id="size_error"></span>
						</li>
						<li>
							<label for="field_maxlength">Field max length</label>
							<input type="number" id="field_maxlength" name="field_maxlength" />
							<span id="maxlength_error"></span>
						</li>
						<li>
							<label for="add_field">Add a field</label>
							<input type="button" value="add field" id="add_field" name="add_field"/>
						</li>
					</ol>
					<input name="submit" type="submit" value="submit" />
				</div>
				</form>
			</div>
			<div class="box fivecol">
				<h2 class="box-title">Form Fields</h2>
				<div id="form_fields">
					<ol>
						<span id="insert_point"></span>
					</ol>
				</div>
				<div id="debug_point"></div>
			</div>

		</div>

	</div><!-- tab 1 -->
	<div id="tabs-2">
		<fieldset>
			<legend>View HTML</legend>
			<div class="output" id="html_output"> </div>
			<div class="output" id="html_render"> </div>
		</fieldset>
	</div><!-- tab 2 -->
	<div id="tabs-3">
		<fieldset>
			<legend>Javascript</legend>
			<div class="info">
			<p>You will need the jQuery cookie plugin from <a href="http://plugins.jquery.com/files/issues/jjquery.cookie-modified.js_.txt" target="_blank"> here </a> to handle the cookie portions of this code.<br />
			The javascript portion uses the jQuery library found <a href="http://www.jQuery.com" target="_blank"> here </a></p>
			</div>
			<div class="output" id="javascript_output"> </div>
		</fieldset>
	</div><!-- tab 3 -->
	<div id="tabs-4">
		<fieldset>
			<legend>Controller Code</legend>
			<div class="output" id="php_output"> </div>
		</fieldset>
	</div><!-- tab 4 -->
</div>

<div id="form_column">
</div>
<div id="output_column">
</div>

<div class="clear"> &nbsp; </div>
