<?php

function actionsNewfile(){
	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		echo "Directory path not specified";
		return;
	}
	$newname = rawurldecode($_POST['new-name']);
	if($newname == ""){
		echo "File name not specified";
		return;
	}
	$filepath = $dirpath . "/" . $newname;
	file_put_contents($filepath,"") or die('File creation failed');
}

function actionsNewdir(){
	echo "newdir";

}


function actionsRename(){
	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		echo "Directory path not specified";
		return;
	}
	$oldname = rawurldecode($_POST['select-file']);
	if($oldname == ""){
		echo "Target file not specified";
		return;
	}
	$newname = rawurldecode($_POST['new-name']);
	if($newname == ""){
		echo "File name not specified";
		return;
	}
	$newname = $dirpath . '/' . $newname;
	rename($oldname,$newname) or die('r gagal');
	echo "Renamed";

}

function actionsDelete(){

	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		echo "Directory path not specified";
		return;
	}

	$target = rawurldecode($_POST['select-file']);
	if($target == ""){
		echo "File not specified";
		return;
	}

	if(is_file($target))
		unlink($target) or die("Delete Failed");
	if(is_dir($target))
		rmdir($target) or die("Delete Failed");
	echo "Deleted";

}
?>
