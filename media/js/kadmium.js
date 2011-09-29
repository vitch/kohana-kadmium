
$(
	function()
	{
//		Date.format = 'yyyy-mm-dd';
//		$('input.timestamp').datePicker({startDate:'1980-01-01'});

//		$('select[multiple]').asmSelect({
//			addItemTarget: 'bottom',
//			animate: true,
//			highlight: false,
//			sortable: false
//		});

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
				$span.remove();
			}
		);
		if (hasSortableTable) {
			var t = $('table.list-page');
			t.find('tr').css('width', t.innerWidth()).end().sortable(
				{
					items: '>tbody>tr',
					handle: 'a.sorter',
					helper: function(event, tr)
					{
						var childWidths = [];
						tr.children().each(function()
							{
								childWidths.push($(this).width());
							}
						);
						return tr.clone().children().each(
							function(i)
							{
								$(this).width(childWidths[i]);
							}
						).end();

					},
					stop: function(event, ui)
					{
						var ids = [];
						t.find('a.sorter').each(
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
					}
				}
			);
		}

		var openedMenu;

//		// Handle colorbox so the add image etc are in pop up boxes...
//		var initColorboxes = function(context) {
//			$('a.lb', context).colorbox(
//				{
//					'iframe' : true,
//					'scrolling' : true,
//					'width' : 820,
//					'height' : $(window).height() - 50,
//					'onOpen' : function() {
//						openedMenu = $(this).parents('ul').attr('rel');
//					},
//					'onClosed' : function() {
//						var loadingMenu = '#' + openedMenu;
//						var q = location.href.indexOf('?') == -1 ? '?' : '&';
//						$.ajax({
//							url : location.href + q + 'action=reload&field=' + openedMenu,
//							success : function (data)
//							{
//								var wrapper = $(loadingMenu).parent();
//								wrapper.html($(data).children());
//								initColorboxes(wrapper);
//								initSortable(loadingMenu);
//							}
//						});
//						openedMenu = undefined;
//					}
//				}
//			).each(
//				function()
//				{
//					var q = this.href.indexOf('?') == -1 ? '?' : '&';
//					$(this).data('colorbox').href = this.href + q + 'lb=true';
//				}
//			);
//		}
//		initColorboxes();

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

		// Permalink form fields
		var permalinkFrom = $('.permalink_from'),
			permalinkTo = $('.permalink_to');
		if (permalinkFrom.length && permalinkTo.length) {

			var fromVal = permalinkFrom.val().toLowerCase().split(' ').join('-');
			permalinkFrom.bind(
				'keyup',
				function()
				{
					var toVal = permalinkTo.val(),
						synced = toVal == '' || toVal == fromVal;
					fromVal = permalinkFrom.val().toLowerCase().split(' ').join('-');
					if (synced) {
						permalinkTo.val(fromVal);
					}
				}
			);
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
