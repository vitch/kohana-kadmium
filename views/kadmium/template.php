<!DOCTYPE HTML>
<html>
	<head>
		<title><?= $html_title; ?></title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		
		<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n\t\t" ?>

		<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n\t\t" ?>

	</head>
	<body>
		<div id="kadmium-content">
			<!--[if IE]>
				<div id="ie-warning">
					<p>
						You appear to be using the Internet Explorer web browser. This system will work with Internet Explorer
						but it is optimised for a more modern browser like <a href="http://getfirefox.com">Firefox</a>,
						<a href="http://www.google.com/chrome">Chrome</a>, <a href="http://www.opera.com/">Opera</a> or
						<a href="http://www.apple.com/safari/">Safari</a> - please consider downloading and installing
						one of these alternatives to enjoy this site at its best.
					</p>
				</div>
			<![endif]-->			
			<ul class="navigation">
			<?php
				foreach ($navigation_controllers as $controller=>$label)
				{
					echo '<li>';
					echo Html::anchor(
						Route::get('kadmium_list')->uri(
							array(
								'controller' => $controller
							)
						),
						$label,
						array(
							'class' => Request::current()->controller == $controller ? 'active' : ''
						)
					);
					echo '</li>';
				}
			?>
			</ul>
			<div class="main">
				<?= $content; ?>
			</div>
		</div>
	</body>
</html>