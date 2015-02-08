<div class="row">
	<div class="span16">
		<?=
			View::factory(
				'kadmium/element/breadcrumb',
				array(
					'breadcrumb' => $breadcrumb,
				)
			);
		?>
		<h1><?= $page_title; ?></h1>
		<form method="post">
			<div class="alert-message block-message error">
				<p>
					Are you sure you want to delete the <?= $item_type; ?> called <strong><?= $item_name; ?></strong>?
				</p>
					<div class="alert-actions">
						<?=
							implode(
								' ',
								array(
									Form::submit(
										'my-action',
										$delete_button_label,
										array(
											'class' => 'btn small danger'
										)
									),
									$cancel_button
								)
							);
						?>
					</div>
			</div>
		</form>
	</div>
</div>
