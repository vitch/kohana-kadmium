<!DOCTYPE HTML>
<html>
	<head>
		<title><?= $html_title; ?></title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		
		<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>

		<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>

	</head>
	<body>
		<div id="str-frame">
			<?= View::factory('element/header'); ?>
			<div id="str-content">
				<div class="col-184 col">
					
					<?=
						View::factory(
							'element/navigation',
							array(
								'navigation' => $navigation,
								'selected_nav_item' => $selected_nav_item,
							)
						);
					?>
					
				</div>
				
				<div id="content" class="col-576 col col-last">
					<?= $content; ?>
				</div>
				
			</div>
			<?= 
				View::factory(
					'element/footer', 
					array(
						'last_updated' => $last_updated,
					)
				); 
			?>
		</div>
	</body>
</html>