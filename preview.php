<!DOCTYPE html>
<html>
<head>
	<title>
<?php
	include '../blog/menu.php';
	if(isset($_GET['path'])){
		$content = get_article_data($_GET['path']);
		echo $content['title'];
	}
	else
		echo "preview target not set";
?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/css/theme.css">
	<meta property="og:image" content="<?php
	if (isset($content['thumbnail']))
		echo $content['thumbnail'];
	else 
		echo 'http://testermelon.com/img/testermelon-banner.png';?>" />

	<meta property="og:type" content="article" />
</head>

<body>

<div class="main">
<?php
	show_article($content);
?>
</div>

</body>

</html>
