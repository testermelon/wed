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

$image_file_string = "";

//This means an image was uploaded
if(isset($_POST['img_upload'])){
	$temp_filename = $_FILES['imgfile']['tmp_name'];
	$target_filename = '/img/' . $_FILES['imgfile']['name'];

	if(move_uploaded_file($temp_filename, $_SERVER['DOCUMENT_ROOT'] . $target_filename)){
		$image_file_string .= '![Image Alt Text](' . $target_filename . ')';
	}
	else die('upload failed: file cannot be moved');
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
	$content .= $image_file_string;
} else 
	die('Filepath not set');


//obtain image links inside the file using regex

preg_match_all('/!\[(.*?)\]\((.*?)\)/', $content, $image_links);

$image_list_html = "";
foreach ($image_links[2] as $imglink){

	$image_list_html .= '<img src="' . $imglink . '"  width="100" height="75" > ';
}

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

<form action="#" method="POST" enctype="multipart/form-data">
	<input type="submit" name="save" value="Save">
	<input name="file" type="hidden" value="<?php echo $filepath ?>"></input>

	<a href="preview.php?preview=<?php echo $filepath ?>" target="preview-win"> Preview </a>
	<br>
	File Contents:
	<br>
	<textarea name="content" cols="80" rows="25" style="max-width:90vw"><?php echo $content; ?></textarea>

<br>
<br>
<?php
	//list images contained in the file
	echo $image_list_html;
?>
<br>
<br>
	<input type="submit" name="img_upload" value="upload"></input>
	<input name="imgfile" type="file" ></input>
</form>

</body>
