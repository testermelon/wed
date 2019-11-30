<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
	<link type="image/x-icon" rel="icon" href="favicon-wed.ico" >
</head>

<body>

<?php 

//obtain current directory from parameter
if(isset($_GET['dir'])) $targetdir = rawurldecode($_GET['dir']);
if(isset($_POST['dir'])) $targetdir = rawurldecode($_POST['dir']);
if(!isset($targetdir) || $targetdir == "" ) $targetdir = "data";

//handle bug regarding square brackets
$targetdir = str_replace('[','\[',$targetdir);
$targetdir = str_replace(']','\]',$targetdir);

if(isset($targetdir)){
	$pathnodes = explode('/',$targetdir);
	$i = 1;
	$len = count($pathnodes);
	$backlink = "";
	foreach($pathnodes as $node){
		$backlink .= $node;
		if($i == $len)
			echo '/' . $node;
		else{
			echo '/' . '<a href="index.php?dir=' . $backlink . '">'. $node . '</a>';
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

<form action="index.php" method="POST">

	<div class="panel">
		<input type="hidden" name="dir" value="<?php echo $targetdir ?>"></input>
		<input name="new-name" placeholder="New name/New item name"></input>
		<input type="submit"  name="submit-new" value="New"></input>
		<input type="submit"  name="submit-newdir" value="New Dir"></input>
		<input type="submit"  name="submit-rename" value="Rename"></input>
		<input type="submit"  name="submit-delete" value="Delete"></input>

<?php
		include './actions.php';
		if(isset($_POST['submit-new'])) actionsNewfile();
		if(isset($_POST['submit-newdir'])) actionsNewdir();
		if(isset($_POST['submit-rename'])) actionsRename();
		if(isset($_POST['submit-delete'])) actionsDelete();
		?>

	</div>

<br>


<?php

$file_list = glob($targetdir . "/*");

//show directories list
foreach ($file_list as $filepath){
	if(is_dir($filepath)){
		$path = rawurlencode($filepath);
		$nodes = explode('/',$filepath);
		$filename = array_pop($nodes);
		echo '<input type="radio" name="select-file" value="' . $path . '"></input>';
		echo '<a href="index.php?dir=';
			echo $path;
			echo '">';
			echo $filename . "/";
		echo '</a>';
		echo "<br>";
	}
}

//show file list
foreach ($file_list as $filepath){
	if(!is_dir($filepath)){
		$path = rawurlencode($filepath);
		$nodes = explode('/',$filepath);
		$filename = array_pop($nodes);
		echo '<input type="radio" name="select-file" value="' . $path . '"></input>';
		echo '<a href="edit.php?file=';
			echo $path;
			echo '">';
			echo $filename;
		echo '</a>';
		echo "<br>";
	}
}
?>

</body>

</html>


