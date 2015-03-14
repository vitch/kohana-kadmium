<div class="alert">
	<h4>
		Viewing <?= number_format($page->current_first_item()); ?>-<?= number_format($page->current_last_item()); ?> <small>of <?= number_format($page->total_items()); ?> results<?php
      if ($display_search) {
        $q = Arr::get($_GET, 'q');
        if ($q) {
          echo ' matching "<strong>' . $q . '</strong>"';
        }
      }
    ?></small>
	</h4>
</div>