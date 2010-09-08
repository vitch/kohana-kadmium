$(
	function()
	{
		$('textarea.wysiwyg').tinymce({
			script_url : '/kadmium/media/tiny_mce/tiny_mce.js', /* Make dynamic??? */
			theme : "advanced",

			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_buttons1 : "formatselect,|,bold,italic,underline,strikethrough,|,link,unlink,|,bullist,numlist,blockquote,hr,|,undo,redo,|,removeformat,visualaid,|,pasteword,cleanup,|,code",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_blockformats : "p,h1,h2,h3",
			plugins : "paste",
			doctype: "<!DOCTYPE HTML>",
			cleanup_on_startup: true,
			convert_urls: false,
			invalid_elements: "span",
			//theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			content_css : "/kadmium/media/css/tinymce-content.css", /* Make dynamic??? */
			body_class : "tinymce"
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
	}
);