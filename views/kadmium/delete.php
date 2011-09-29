<h1><?= $page_title; ?></h1>
<form method="post">
	<div class="alert-message block-message error">
		<p>
			Are you sure you want to delete the <?= $item_type; ?> called <strong><?= $item_name; ?></strong>?
		</p>
			<div class="alert-actions">
				<?php
					echo Form::submit(
						'my-action',
						$delete_button_label,
						array(
							'class' => 'btn small danger'
						)
					) . ' ';
					echo Html::anchor(
						Request::current()->uri(
							array(
								'action' => 'edit',
							)
						),
						'Cancel',
						array(
							'class' => 'btn small'
						)
					);
				?>
			</div>
	</div>
</form>
