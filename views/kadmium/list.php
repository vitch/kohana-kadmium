<h1><?= $page_title; ?></h1>

<?php
if ($items->count() == 0) {
?>
<div class="error">
	<p>
		<?php
			if ($display_search && Arr::get($_GET, 'q')) {
				echo 'No results matching your search (<strong>' . Arr::get($_GET, 'q') . '</strong>). ';
				echo Html::anchor(Arr::get($_SERVER, 'HTTP_REFERER'), 'Go back') . '.';
			} else {
				echo 'You don\'t have any ' . Inflector::plural($item_type) . ' yet.';
				if ($display_add_links) {
					echo 'You can ' . Html::anchor(
						$add_link,
						'create one',
						array(
							'class' => 'link'
						)
					) . ' now.';
				}
			}
		?>
	</p>
</div>
<?php
} else {
	if ($display_add_links) {
		echo '<p>';
		echo Html::anchor(
			$add_link,
			'Add new ' . strtolower($item_type),
			array(
				'class' => 'add',
			)
		);
		echo '</p>';
	}
	if ($display_search) {
		$get_params = Arr::merge(array(), $_GET);
		unset($get_params['q']);
		echo Form::open(
			Request::current()->uri(array('page'=>'1')), 
			array('class'=>'search-form', 'method'=>'get')
		);
		foreach($get_params as $key=>$value) {
			echo Form::hidden($key, $value);
		}
		$q = Arr::get($_GET, 'q');
		echo Form::label('q', Inflector::plural('Search ' . strtolower($item_type)));
		echo Form::input('q', $q, array('id' => 'q'));
		echo Form::submit('my_action', 'Search');
		echo Form::close();
		echo '<br style="clear: both;" />';

		$clear_search_link = Url::site(Request::current()->uri() . '?' . http_build_query($get_params));

		if ($q) {
			echo <<<EOT
			<div class="feedback">
				Viewing results for "<strong>{$q}</strong>". <a href="$clear_search_link">Clear search</a>.
			</div>
EOT;
		}
	}
	echo View::factory(
		'kadmium/element/list_table',
		array(
			'items' => $items,
			'allow_sorting' => $allow_sorting,
			'show_edit' => $show_edit,
			'extra_button_view' => $extra_button_view,
		)
	);
	echo $pagination;
}
?>