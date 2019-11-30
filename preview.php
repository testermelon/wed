<?php
include "../blog/rendering.php";
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<base href="<?php echo $config['base']; ?>" >
	<link rel="stylesheet" type="text/css" href="<?php echo $css_path; ?>">
	<link type="image/x-icon" rel="icon" href="favicon-blog.ico" >
	<title> <?php echo $content['title']; ?> </title>
	<meta property="og:image" content="<?php echo $content['thumbnail']; ?>" >
	<meta property="og:type" content="article" >
</head>

<body>
	<div class="main">
		<?php echo $comp_main; ?>
	</div>

</body>
</html>
