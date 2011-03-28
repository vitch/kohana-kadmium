<h1><?= $page_title; ?></h1>

<div class="error">
	<p>You cannot delete the <?= $item_type; ?> called "<?= $item_name; ?>" because of the following:</p>
</div>
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
<?= $edit_link; ?>