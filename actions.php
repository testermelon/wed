<?php

/*Returns default new text for new file contents
 */
function newText(){
	$new_text = "date=" . date("Ymd") . "\n";
	$new_text.= "layout=article\n";
	$new_text.= "title=Artikel Baru\n";
	$new_text.= "---";
	return $new_text;
}

function actionsNewfile(){
	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		return "Directory path not specified";
	}
	$newname = rawurldecode($_POST['new-name']);
	if($newname == ""){
		return "File name not specified";
	}
	$filepath = $dirpath . "/" . $newname;
	file_put_contents($filepath,newText()) or die('File creation failed');
}

function actionsNewdir(){
	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		return "Directory path not specified";
	}
	$newname = rawurldecode($_POST['new-name']);
	if($newname == ""){
		return "Directory name not specified";
	}
	$filepath = $dirpath . "/" . $newname;
	mkdir($filepath) or die('Directory creation failed');
	echo "Directory Created";

}


function actionsRename(){
	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		return "Directory path not specified";
	}
	$oldname = rawurldecode($_POST['select-file']);
	if($oldname == ""){
		return "Target file not specified";
	}
	$newname = rawurldecode($_POST['new-name']);
	if($newname == ""){
		return "File name not specified";
	}
	$newname = $dirpath . '/' . $newname;
	rename($oldname,$newname) or die('r gagal');
	return "Renamed";

}

function actionsDelete(){

	$dirpath = rawurldecode($_POST['dir']);
	if($dirpath == ""){
		return "Directory path not specified";
	}

	$target = rawurldecode($_POST['select-file']);
	if($target == ""){
		return "File not specified";
	}

	if(is_file($target)){
		unlink($target) or die("Delete Failed");
		return "File Deleted";
	}
	if(is_dir($target)){
		rmdir($target) or die("Delete Failed");
		return "Directory Deleted";
	}
}

function actionsUpload(){
	return "Uploaded";
}
?>
