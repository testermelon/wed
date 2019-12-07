<?php

//this means first access from index.php
if(isset($_GET['file']))
	$filepath = rawurldecode($_GET['file']);
//-- handled GET


//this means file contents was posted
if(isset($_POST['file']))
	$filepath = rawurldecode($_POST['file']);
if(isset($_POST['content'])){
	$content = rawurldecode($_POST['content']);
	file_put_contents($filepath,$content) or die("s  gagal");
}
//--- handled POST

//process data
if(isset($filepath)){
	$pathnodes = explode('/',$filepath);
	$i = 1;
	$len = count($pathnodes);
	$backlink = "";
	$pathstring = "";
	foreach($pathnodes as $node){
		$backlink .= $node;
		if($i == $len)
			$pathstring .=  '/' . $node;
		else{
			$pathstring .=  '/' . '<a href="index.php?dir=' . $backlink . '">' . $node . '</a>';
		}
		$backlink .= '/';
		$i++;
	}
	$content = file_get_contents($filepath) or die("o gagal");
} else 
	die('Filepath not set');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
</head>

<body>

Path: <?php echo $pathstring; ?> 

<hr>

<form action="#" method="POST">
	<input type="submit" value="Save">
	<input name="file" type="hidden" value="<?php echo $filepath ?>"></input>

	<a href="preview.php?preview=<?php echo $filepath ?>" target="preview-win"> Preview </a>
	<br>
	File Contents:
	<br>
	<textarea name="content" cols="80" rows="25" style="max-width:90vw"><?php echo $content; ?></textarea>
</form>

<br>

<?php
	//list images contained in the file
	echo '<img src="/img/testermelon-banner.png" width="100" height="75" >';
?>


<form action="#" method="">
	<input type="submit" value="upload"></input>
	<input name="filepath" type="file" ></input>
</form>

</body>
