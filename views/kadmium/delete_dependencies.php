<h1><?= $page_title; ?></h1>

<div class="alert-message block-message error">
	<p>You cannot delete the <?= $item_type; ?> called "<?= $item_name; ?>" because of the following:</p>
	<ul>
	<?php
		foreach ($belongs_to as $item):
	?>
			<li>It is linked to the <?= $item['model']; ?> called "<?=
				Html::anchor(
					$item['link'],
					$item['name']
				);
			?>"</li>
	<?php
		endforeach;
		foreach ($children as $item):
	?>
			<li>It has the dependant <?= $item['model']; ?> called "<?=
				Html::anchor(
					$item['link'],
					$item['name']
				);
			?>"</li>
	<?php
		endforeach;
	?>
	</ul>
</div>
<?= $edit_link; ?>
