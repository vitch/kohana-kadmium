<!DOCTYPE HTML>
<html>
	<head>
		<title><?= $html_title; ?></title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		
		<link rel="shortcut icon" href="/favicon.ico" />

		<link rel="stylesheet" href="/resource/css/datePicker.css" type="text/css" />
		<link rel="stylesheet" href="/resource/css/screen.css" type="text/css" />
		<link rel="stylesheet" href="/resource/css/jquery.asmselect.css" type="text/css" />
		<link rel="stylesheet" href="/resource/colorbox/styles/colorbox.css" type="text/css" />
		<link rel="stylesheet" href="/resource/css/admin.css" type="text/css" />

		<!-- script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script -->
		<script type="text/javascript" src="/resource/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/resource/js/date.js"></script>
		<script type="text/javascript" src="/resource/js/jquery.datePicker.js"></script>
		<script type="text/javascript" src="/resource/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript" src="/resource/js/jquery.asmselect.js"></script>
		<script type="text/javascript" src="/resource/js/jquery.tablednd_0_5.js"></script>
		<script type="text/javascript" src="/resource/colorbox/scripts/jquery.colorbox-min.js"></script>
		<script type="text/javascript" src="/resource/js/admin.js"></script>

		<!--[if lte IE 6]>
		<link rel="stylesheet" href="/resource/css/ie6.css" type="text/css" media="screen" />
		<script type="text/javascript" src="/resource/js/jquery.bgiframe.min.js"></script>
		<![endif]-->

		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-16987548-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
	</head>
	<body>
		<div id="str-frame">
			<?= View::factory('element/header'); ?>
			<div id="str-content">
				<div class="col-184 col">
					
					<?=
						View::factory(
							'element/navigation',
							array(
								'navigation' => $navigation,
								'selected_nav_item' => $selected_nav_item,
							)
						);
					?>
					
				</div>
				
				<div id="content" class="col-576 col col-last">
					<?= $content; ?>
				</div>
				
			</div>
			<?= 
				View::factory(
					'element/footer', 
					array(
						'last_updated' => $last_updated,
					)
				); 
			?>
		</div>
	</body>
</html>