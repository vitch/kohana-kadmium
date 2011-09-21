
<div class="container-fluid">
	<?= Html::anchor(
			'',
			$project_name,
			array(
				'class' => 'brand',
			)
		);
	?>

	<!--ul class="nav">
		<li class="active"><a href="#">Home</a></li>
		<li><a href="#about">About</a></li>
		<li><a href="#contact">Contact</a></li>
	</ul-->
	<?php
		if ($is_logged_in) {
	?>
	<p class="pull-right">Logged in as <?= Html::anchor(
		$edit_user_link,
		$user_name
	) ?>. <?=
		Html::anchor(
			$logout_link,
			'Logout'
		);
	?>.</p>
	<?php
	}
	?>
</div>