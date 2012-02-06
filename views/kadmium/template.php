<!DOCTYPE HTML>
<html>
	<head>
		<title><?= $html_title; ?></title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n\t\t" ?>

		<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n\t\t" ?>

	</head>
	<body class="<?= $is_logged_in ? 'logged-in' : 'logged-out'; ?>">
		<div class="navbar">
			<?php
				echo View::factory(
					'kadmium/element/topbar',
					array(
						'project_name' => $project_name,
						'is_logged_in' => $is_logged_in,
						'navigation_controllers' => $navigation_controllers,
						'edit_user_link' => 'user/edit',
						'user_name' => $user_name,
						'logout_link' => 'user/logout',
					)
				);
			?>
		</div>

		<!--[if IE LT 7]>
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
		<div class="container">
			<div class="content">
				<?php
					echo $content;
					if ($is_logged_in) {
				?>
				<footer>
					<p>&copy; Luck Laboratories Ltd <?= date('Y'); ?>.</p>
				</footer>
				<?php
					}
				?>
			</div>
			<?php
				if ($show_profiler) {
					echo '<div id="kohana-profiler">';
					echo View::factory('profiler/stats');
					echo '</div>';
				}
			?>
		</div>
	</body>
</html>