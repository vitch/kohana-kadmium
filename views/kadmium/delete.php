<h1><?= $page_title; ?></h1>

<p>Are you sure you want to delete the <?= $item_type; ?> called "<?= $item_name; ?>"?</p>

<form method="post">
<input type="submit" name="my-action" value="<?= $delete_button_label; ?>" />
</form>