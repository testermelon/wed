<?php 

if(file_exists('config.php'))
	$config = include('config.php');
else
	$config = include('defaults.php');


//obtain current directory from parameter
if(isset($_GET['dir'])) $targetdir = rawurldecode($_GET['dir']);
if(isset($_POST['dir'])) $targetdir = rawurldecode($_POST['dir']);
if(!isset($targetdir) || $targetdir == "" ) $targetdir = $config['homedir'] ;

//handle bug regarding square brackets
$targetdir = str_replace('[','\[',$targetdir);
$targetdir = str_replace(']','\]',$targetdir);

if(isset($targetdir)){
	$pathnodes = explode('/',$targetdir);
	$i = 1;
	$len = count($pathnodes);
	$backlink = "";
	$pathstring ="";
	foreach($pathnodes as $node){
		$backlink .= $node;
		if($i == $len)
			$pathstring .= '/' . $node;
		else{
			$pathstring .= '/' . '<a href="index.php?dir=' . $backlink . '">'. $node . '</a>';
		}
		$backlink .= '/';
		$i++;
	}
} else
	die('Filepath not set');

include './actions.php';

if(isset($_POST['submit-new'])) $action_output = actionsNewfile();
if(isset($_POST['submit-newdir'])) $action_output = actionsNewdir();
if(isset($_POST['submit-rename'])) $action_output = actionsRename();
if(isset($_POST['submit-delete'])) $action_output = actionsDelete();
if(isset($_POST['submit-upload'])) $action_output = actionsUpload();


//Generating dir and file list

$file_list = glob($targetdir . "/*");

//show directories list
$dirlist_string = "";
foreach ($file_list as $filepath){
	if(is_dir($filepath)){
		$path = rawurlencode($filepath);
		$nodes = explode('/',$filepath);
		$filename = array_pop($nodes);
		$dirlist_string .= '<input type="radio" name="select-file" value="' . $path . '"></input>';
		$dirlist_string .= '<a href="index.php?dir=';
			$dirlist_string .= $path;
			$dirlist_string .= '">';
			$dirlist_string .= $filename . "/";
		$dirlist_string .= '</a>';
		$dirlist_string .= "<br>";
	}
}

//show file list
$filelist_string = "";
foreach ($file_list as $filepath){
	if(!is_dir($filepath)){
		$path = rawurlencode($filepath);
		$nodes = explode('/',$filepath);
		$filename = array_pop($nodes);
		$filelist_string .= '<input type="radio" name="select-file" value="' . $path . '"></input>';
		$filelist_string .= '<a href="edit.php?file=';
			$filelist_string .= $path;
			$filelist_string .= '">';
			$filelist_string .= $filename;
		$filelist_string .= '</a>';
		$filelist_string .= "<br>";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
	<link type="image/x-icon" rel="icon" href="favicon-wed.ico" >
</head>

<body>

<form action="index.php" method="POST">
	<div class="panel">
		<input type="hidden" name="dir" value="<?php echo $targetdir ?>"></input>
		<input name="new-name" placeholder="New name/New item name"></input>
		<input type="submit"  name="submit-new" value="New"></input>
		<input type="submit"  name="submit-newdir" value="New Dir"></input>
		<input type="submit"  name="submit-rename" value="Rename"></input>
		<input type="submit"  name="submit-delete" value="Delete"></input>
		<?php if(isset($action_output)) echo '<em>' . $action_output . '</em>'; ?>
	</div>
<input type="file" name="upload-files" mutiple >
<input type="submit" name="submit-upload" value="Upload"></input>
<br>

Path: <?php echo $pathstring; ?>
<br>

<?php echo $dirlist_string; ?>
<?php echo $filelist_string; ?>

</body>

</html>


