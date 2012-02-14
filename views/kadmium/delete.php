<div class="row">
	<div class="span12">
		<?=
			View::factory(
				'kadmium/element/breadcrumb',
				array(
					'breadcrumb' => isset($breadcrumb) ? $breadcrumb : null,
				)
			);
		?>
		<div class="page-header">
			<h1><?= $page_title; ?></h1>
		</div>
		<form method="post">
			<div class="alert alert-block alert-error">
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
											'class' => 'btn btn-danger'
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
