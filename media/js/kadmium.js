
$(
	function()
	{
		Date.format = 'yyyy-mm-dd';
		$('input.timestamp').datePicker({startDate:'1980-01-01'});
		
		
		$('textarea.wysiwyg').tinymce({
			script_url : '/kadmium/media/tiny_mce/tiny_mce.js', /* Make dynamic??? */
			theme : "advanced",
			
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_buttons1 : "formatselect,|,bold,italic,underline,strikethrough,sub,sup,|,link,unlink,|,bullist,numlist,blockquote,hr,|,undo,redo",
			theme_advanced_buttons2 : "tablecontrols,|,removeformat,visualaid,|,pasteword,cleanup,code",
			theme_advanced_buttons3 : "",
			theme_advanced_blockformats : "p,h1,h2,h3",
			plugins : "paste,table",
			doctype: "<!DOCTYPE HTML>",
			cleanup_on_startup: true,
			invalid_elements: "span",
			//theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			content_css : "/resource/css/screen.css",
			body_class : "copy"
/*
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
*/
		});

		$('select[multiple]').asmSelect({
			addItemTarget: 'bottom',
			animate: true,
			highlight: false,
			sortable: false
		});

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
					'width' : 640,
					'height' : 400,//$(window).height() - 50,
					'onOpen' : function() {
						openedMenu = $(this).parents('ul').attr('id');
					},
					'onClosed' : function() {
						var loadingMenu = '#' + openedMenu;
						$(loadingMenu).parent().load(
							location.href + '?action=reload&field=' + openedMenu,
							function()
							{
								initColorboxes(loadingMenu)
							}
						);
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
