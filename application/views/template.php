<!doctype html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php  if (isset($title)) { echo $title; }  ?></title>
	<meta name="description" content="<?php  if (isset($description)) { echo $description; }  ?>">
	<meta name="author" content="Patrick Spence">
	<meta name="copyright" content="Copyright 2011"> 

	<link rel="stylesheet" href="/assets/css/jquery-ui.css" />
<?php
	if (isset($css)) { 
		if (is_array($css)) {
			foreach ($css as $file) {
				echo sprintf('<link rel="stylesheet" href="/assets/css/%s">'."\n", $file);
			}
		} else {
			echo sprintf('<link rel="stylesheet" href="/assets/css/%s">'."\n", $css);
		}
	} 
?>
</head>
<body>
	<div class="row">
		<header class="twelvecol last">
			<nav><?php if (isset($primary_links)) { echo $primary_links; } ?></nav>
			<div class="logo">Form Builder</div>
		</header>
	</div>
	<div id="container" class="container fancy">
		<div class="row">
			<div id="main" role="main" class="twelvecol last">
				<div class="box">
		<?php if (isset($content)) { echo $content; } ?>
				</div>
			</div>	
		</div>
	</div> <!--! end of #container -->
    <footer>
	</footer>

	<!-- JavaScript at the bottom for faster page loading -->
	<!-- Grab Google CDN's jQuery, with a protocol relative URL -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
<?php
	if (isset($javascript)) { 
		if (is_array($javascript)) {
			foreach ($javascript as $file) {
				echo sprintf('<script src="'.base_url().'js/%s"></script>'."\n", $file);
			}
		} else {
			echo sprintf('<script src="'.base_url().'js/%s"></script>'."\n", $javascript);
		}
	} 
?>
	<!-- load custom plugins, including the cookie plugin needed for CSRF tags -->
	<script src="<?php echo base_url(); ?>js/plugins.js"></script>
</body>
</html>
