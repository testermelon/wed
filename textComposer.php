<?php

/* This script is used to handle file and directory creation using php.
 *
 * newFile(filepath)
 *
 * readFile(dirpath)
 *
 * newDir(dirpath)
 *
 * saveFile(filepath,filecontent)
 *
 * Required parameters:
 * 	filepath
 * 	dirpath
 * 	filecontent
 */

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$dirpath = rawurldecode($_GET['dirpath']);
	$op = $_GET['op'];
}
else
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$op = $_POST['op'];
}
else die('no request detected');

//handle bug regarding square brackets
$filepath = str_replace(']','\]',$filepath);
$dirpath = str_replace('[','\[',$dirpath);

function newFile() {
	$dirpath = rawurldecode($_POST['dirpath']);
	$filepath = $dirpath . date('Y-m-d-hi') . '.txt';
	file_put_contents($filepath,"Your Text Here") or die("gagal");
}

function renameFile(){
	$filepath = rawurldecode($_POST['oldpath']);
	$newpath = rawurldecode($_POST['newpath']);
	rename($filepath,$newpath) or die('r gagal');
}

function newDir() {

}

function getFileContents(){
	$filepath = rawurldecode($_GET['filepath']);
	$content = file_get_contents($filepath) or die("o gagal");
	echo $content;
}

function writeFileContents(){
	$filepath = rawurldecode($_POST['filepath']);
	$content = rawurldecode($_POST['content']);
	file_put_contents($filepath,$content) or die("s  gagal");
}

function deleteFile(){
	$filepath = rawurldecode($_POST['filepath']);
	unlink($filepath) or die("d  gagal");
}

function createDir(){
	$dirpath = rawurldecode($_POST['dirpath']);
	mkdir($dirpath) or die("m gagal");
}

function deleteDir(){
	$dirpath = rawurldecode($_POST['dirpath']);
	rmdir($dirpath) or die("m gagal");
}
switch($op){

	case 'n':
		newFile();
		break;
	case 'o':
		getFileContents();
		break;
	case 's':
		writeFileContents();
		break;
	case 'r':
		renameFile();
		break;
	case 'd':
		deleteFile();
		break;
	case 'm':
		createDir();
		break;
	case 'D':
		deleteDir();
		break;
	
}

?>

