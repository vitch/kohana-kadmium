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