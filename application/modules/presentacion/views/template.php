<!doctype html>
<html lang="es">

	<head>
		<meta charset="utf-8">

		<title>DNA2BPM Presentation Framework</title>

		<meta name="description" content="A framework for easily creating beautiful presentations using HTML">
		<meta name="author" content="Hakim El Hattab">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">

		<link rel="stylesheet" href="{module_url}assets/jscript/reveal.js/css/reveal.css">
		<link rel="stylesheet" href="{module_url}assets/jscript/reveal.js/css/theme/black.css" id="theme">
		<!-- font Awesome -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
		<!-- Code syntax highlighting -->
		<link rel="stylesheet" href="{module_url}assets/jscript/reveal.js/lib/css/zenburn.css">

		<!-- Printing and PDF exports -->
		<script>
			var link = document.createElement( 'link' );
			link.rel = 'stylesheet';
			link.type = 'text/css';
			link.href = window.location.search.match( /print-pdf/gi ) ? '{module_url}assets/jscript/reveal.js/css/print/pdf.css' : '{module_url}assets/jscript/reveal.js/css/print/paper.css';
			document.getElementsByTagName( 'head' )[0].appendChild( link );
		</script>

		<!--[if lt IE 9]>
		<script src="lib/js/html5shiv.js"></script>
		<![endif]-->
	</head>

	<body>

		<div class="reveal">

			<!-- Any section element inside of this container is displayed as a slide -->
			<div class="slides">
				{slides}
			</div>

		</div>

		<script src="{module_url}assets/jscript/reveal.js/lib/js/head.min.js"></script>
		<script src="{module_url}assets/jscript/reveal.js/js/reveal.js"></script>
		<script src="{module_url}assets/jscript/init.js"></script>

	</body>
</html>
