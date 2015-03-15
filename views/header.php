<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="utf-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="@julioelpoeta">
		<meta http-equiv="Content-Language" content="en">
		<meta name="title" content="<?php echo $title; ?>" />
		<meta name="description" content="<?php echo $description; ?>" />
		<meta http-equiv="og:title" content="<?php echo $title; ?>" />
		<meta http-equiv="og:description" content="<?php echo $description; ?>" />
		<meta name="description" content="<?php echo $description; ?>">


		<title>
			<?php echo $title; ?>
		</title>


		<!-- CSS AND JS-->
		<?php
			foreach($js_files['header'] AS $file) {
				echo '<script  src="' . $file . '></script>'.PHP_EOL;
			}

			foreach($css_files['header'] AS $file) {
				echo '<link href="' . $file . '" rel="stylesheet">'.PHP_EOL;
			}
		?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script type="text/javascript" src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
