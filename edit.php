<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
</head>

<body>

<?php
if(isset($_GET['file']))
	$filepath = rawurldecode($_GET['file']);
if(isset($_POST['file']))
	$filepath = rawurldecode($_POST['file']);
if(isset($filepath)){
	$pathnodes = explode('/',$filepath);
	$i = 1;
	$len = count($pathnodes);
	$backlink = "";
	foreach($pathnodes as $node){
		$backlink .= $node;
		if($i == $len)
			echo '/' . $node;
		else{
			echo '/' . '<a href="index.php?dir=' . $backlink . '">' . $node . '</a>';
		}

		$backlink .= '/';

		$i++;
	}
	echo "<br>";
}
else
	die('Filepath not set');
?>

<hr>

<?php
if(isset($_POST['content'])){
	$content = rawurldecode($_POST['content']);
	file_put_contents($filepath,$content) or die("s  gagal");
}
?>

<form action="edit.php" method="POST">
	<input type="submit" value="Save">
	<input name="file" type="hidden" value="<?php echo $filepath ?>"></input>
	<a href="preview.php?path=<?php echo $filepath ?>" target="_blank"> Preview </a>
	<br>
	File Contents:
	<br>
	<textarea name="content" cols="80" rows="25" style="max-width:90vw"><?php
		$content = file_get_contents($filepath) or die("o gagal");
		echo $content;
	?>
