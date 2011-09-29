
<div class="fill">
	<div class="container">
		<?= Html::anchor(
				'',
				$project_name,
				array(
					'class' => 'brand',
				)
			);

			if ($is_logged_in) {
		?>
			<ul class="nav">
			<?php
					foreach ($navigation_controllers as $controller=>$label)
					{
						$active_class = Request::current()->controller() == $controller ? 'active' : '';
						if (is_array($label)) {
							echo '<li class="dropdown ' . $active_class . '">';
							echo Html::anchor(
								'#',
								$controller,
								array(
									'class' => 'dropdown-toggle'
								)
							);
							echo '<ul class="dropdown-menu">';
							foreach ($label as $sub_controller=>$sub_label)
							{
								echo '<li' . (Request::current()->controller() == $sub_controller ? 'class="active"' : '') . '>';
								echo Html::anchor(
									Route::get('kadmium_list')->uri(
										array(
											'controller' => $sub_controller
										)
									),
									$sub_label
								);
								echo '</li>';
							}
							echo '</ul>';
							echo '</li>';
						} else {
							echo '<li class="' . $active_class . '">';
							echo Html::anchor(
								Route::get('kadmium_list')->uri(
									array(
										'controller' => $controller
									)
								),
								$label,
								array(
									'class' => Request::current()->controller() == $controller ? 'active' : ''
								)
							);
							echo '</li>';
						}
					}
			?>
			</ul>
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
</div>