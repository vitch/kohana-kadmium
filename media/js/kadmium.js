
$(
	function()
	{
		Date.format = 'yyyy-mm-dd';
		$('input.timestamp').datePicker({startDate:'1980-01-01'});

		$('select[multiple]').asmSelect({
			addItemTarget: 'bottom',
			animate: true,
			highlight: false,
			sortable: false
		});

		// Collapsible side nav panes
		$('ul.navigation ul').each(
			function()
			{
				var ul = $(this).hide();
				var isOpen = false;
				var h3 = ul.prev('h3').css('cursor', 'pointer').bind(
					'click',
					function()
					{
						ul[isOpen ? 'slideUp' : 'slideDown']();
						isOpen = !isOpen;
						h3[isOpen ? 'addClass' : 'removeClass']('open');
					}
				);
				if (ul.has('a.active').length > 0) {
					ul.show();
					h3.trigger('click');
				}
			}
		);

		// Handle sorting for list tables...
		var hasSortableTable = false; // TODO: Will break if there is more than one list-table on the page
		$('table.list-page span.sort-on').each(
			function()
			{
				hasSortableTable = true;
				var $span = $(this);
				$span.after(
					$('<a href="#" />')
						.attr(
							{
								rel: $span.attr('rel'),
								title: 'Drag to sort'
							}
						)
						.addClass('sorter')
						.text($span.text())
						.bind(
							'click',
							function()
							{
								return false;
							}
						)
					);
				$span.parent('td').addClass('drag-handle');
				$span.remove();
			}
		);
		if (hasSortableTable) {
			$('table.list-page').tableDnD(
				{
					onDrop: function(table, row)
					{
						var ids = [];
						$(table).find('a.sorter').each(
							function()
							{
								ids.push($(this).attr('rel'));
							}
						);

						$('body').addClass('busy');

						$.ajax(
							{
								success: function(data)
								{
									$('body').removeClass('busy');
								},
								error: function ()
								{
									$('body').removeClass('busy');
								},
								data: {
									'ids': ids.join(','),
									'my-action': 'sortItems'
								},
								type: 'post',
								dataType: 'json',
								url: document.location.href
							}
						);
					},
					onDragClass: 'dragging',
					dragHandle: 'drag-handle'
				}
			);

		}

		var openedMenu;

		// Handle colorbox so the add image etc are in pop up boxes...
		var initColorboxes = function(context) {
			$('a.lb', context).colorbox(
				{
					'iframe' : true,
					'scrolling' : true,
					'width' : 820,
					'height' : $(window).height() - 50,
					'onOpen' : function() {
						openedMenu = $(this).parents('ul').attr('rel');
					},
					'onClosed' : function() {
						var loadingMenu = '#' + openedMenu;
						$.ajax({
							url : location.href + '?action=reload&field=' + openedMenu,
							success : function (data)
							{
								var wrapper = $(loadingMenu).parent();
								wrapper.html($(data).children());
								initColorboxes(wrapper);
								initSortable(loadingMenu);
							}
						});
						openedMenu = undefined;
					}
				}
			).each(
				function()
				{
					$(this).data('colorbox').href = this.href + '?lb=true';
				}
			);
		}
		initColorboxes();

		$('a.js-close-link').bind(
			'click',
			function()
			{
				parent.$.fn.colorbox.close();
			}
		)

		// Drag and drop for sortable child nodes...
		var initSortable = function(selector) {
			$(selector).sortable(
				{
					items: '>li',
					stop: function(event, ui)
					{
						var ul = ui.item.parent();
						var ids = [];
						ul.find('>li').each(
							function(i)
							{
								ids.push($(this).attr('rel'));
							}
						);

						$('body').addClass('busy');

						$.ajax(
							{
								success: function(data)
								{
									$('body').removeClass('busy');
								},
								error: function ()
								{
									$('body').removeClass('busy');
								},
								data: {
									'ids': ids.join(','),
									'child_id': ul.attr('id'),
									'action': 'sortItems'
								},
								type: 'post',
								dataType: 'json',
								url: document.location.href
							}
						)
					}
				}
			).disableSelection();
		}
		initSortable('ul.has-many-uniquely.sortable');

		// Help IE out with some of the CSS selectors it has trouble with which are relied on by the design
		if ($.browser.msie) {
			$('table.list-page tr:odd').addClass('odd');
			$('table.list-page tr:even').addClass('even');
		}

		// store the value of all form fields so we can warn the user if they edit and then try to leave without saving...
		var pageLoadData = {};
		$(':input').each(
			function() {
				pageLoadData[this.id] = $(this).val();
			}
		);

		// listen for the beforeunload event and if the data has changed and we aren't submitting the form then warn the user...
		if ($('form.saveable').length) {
			var isSubmitting = false;

			$('input[type=submit]').bind(
				'click',
				function() {
					isSubmitting = true;
				}
			);


			window.onbeforeunload = function() {
				var dataChanged = false;
				if (!isSubmitting) {
					dataChanged = hasDataChanged();
				}
				return dataChanged ? "Any changes you have made will be lost if press OK. To save changes press Cancel and then save this item." : undefined;
			};

			function hasDataChanged() {
				var dataChanged = false;
				if (window.tinymce) {
					window.tinymce.triggerSave();
				}
				$(':input').each(
						function() {
							if (pageLoadData[this.id] != $(this).val()) {
								if ($.isArray(pageLoadData[this.id]) && pageLoadData[this.id].join(',') == $(this).val().join(',')) {
									// ALL OK
									return false;
								}
								// FIXME: In IE the tinymce column can have it's whitespace changed
								//window.console.log(this.id, pageLoadData[this.id], $(this).val());
								//alert(this.id);
								//alert(pageLoadData[this.id]);
								//alert($(this).val());
								dataChanged = true;
								return false;

							}
						}
					);
				return dataChanged;
			}
		}

	}
);
