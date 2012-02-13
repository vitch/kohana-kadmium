<!DOCTYPE HTML>
<html>
	<head>
		<title><?= $html_title; ?></title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8">

		<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/kadmium/media/js/jquery-1.7.1.min.js"><\/script>');</script>
		<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>
	</head>
	<body>
		<div id="kadmium-content" class="lb">
			<?= $content; ?>
		</div>
	</body>
</html>