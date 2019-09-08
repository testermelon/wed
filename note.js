//************************************
//State variables for the Note app
//************************************
//These needs to be prepared and reflected on the html (view) at init time

//Home directory of files
let home_dir = "data";

//state variables of the browser
let current_dir = "";
let current_file = "";

//browser history
//in two parts: back and forward
//push on each browse traverse 
//   back[]  <--> current_dir <--> forward[]
let browser_history_back = [];
let browser_history_forward = [];

//********************
//User Actions
//*********************
//Script for user actions, these are mainly button pushes, etc.

function actionUp(){
	browser_history_back.push(current_dir);
	browser_history_forward = [];
	let slice_dir = current_dir.split("%2F");
	//pop away the current dir so it became upper dir
	slice_dir.pop();
	slice_dir.pop();
	let updir = slice_dir.join("%2F");
	//Prevent access to upper dir
	if (updir == "") return;
	ajaxObtainDirList(updir); 
}

function actionBrowserItemClick(dir) {
	browser_history_back.push(current_dir);
	browser_history_forward = [];
	ajaxObtainDirList(dir); 
}

function actionFileOpen(file){
	browser_history_back.push(current_dir);
	browser_history_forward = [];
	ajaxObtainFileContent(file);
}
	
function actionBack() {

	if (current_file != ""){
		ajaxSaveFile(current_file);
		current_file = "";
		unsetEditable();
	}

	let back_dir = browser_history_back.pop();
	if (back_dir == undefined) return;

	//slice out trailing slash
	let slice_dir = back_dir.split("%2F");
	slice_dir.pop();
	back_dir = slice_dir.join("%2F");

	browser_history_forward.push(current_dir);
	ajaxObtainDirList(back_dir); 
}

function actionNewFile(){
	ajaxAddFile();
}


function actionRenameFile(oldpath) {
	name = decodeURIComponent(oldpath.replace(current_dir,""));
	let newname = prompt("Nama barunya apa?",  name)
	if(newname==null) return;
	if(newname==name) return;
	ajaxRenameFile(oldpath,newname);
}

function actionHome(){
	if(current_dir == encodeURIComponent(home_dir + "/")) return;
	ajaxObtainDirList(encodeURIComponent(home_dir)); 
}

function actionDeleteFile(file){
	if( confirm("Hapus file "+ decodeURIComponent(file.replace(current_dir,"")) +" ? ") ) {
		ajaxDeleteFile(file);
	}
}

function actionNewDir(){
	let name = prompt("Nama Directory Barunya apa?","New Directory"); 
	ajaxNewDir(name);
}

function actionDeleteDir(dir){
	if( confirm("Hapus directory "+ decodeURIComponent(dir.replace(current_dir,"")) +" ? ") ) {
		ajaxDeleteDir(dir);
	}
}

//*************************
//event handlings
//*************************
//
//These are mostly event handlers, 
//some of them are natural browser events, 
//some of them are artifical events that needs to be triggered manually 

//Called on DOM loaded event
function initPlayer() {

	mediaListener(mediaEv);
	ajaxObtainDirList(encodeURIComponent(home_dir));
	unsetEditable();

}

function setEditable() {
	element = document.getElementById("editor-element");
	element.contentEditable = true;
	element.spellcheck = false;
	document.getElementById("new-button").style.display = "none";
	document.getElementById("newdir-button").style.display = "none";
	document.getElementById("up-button").style.display = "none";
	document.getElementById("home-button").style.display = "none";

}

function unsetEditable() {
	element = document.getElementById("editor-element");
	element.contentEditable = false;
	element.spellcheck = false;
	document.getElementById("new-button").style.display = "inline";
	document.getElementById("newdir-button").style.display = "inline";
	document.getElementById("up-button").style.display = "inline";
	document.getElementById("back-button").style.display = "inline";
	document.getElementById("home-button").style.display = "inline";
}




//**********************
// AJAX codes
//**********************

function ajaxAddFile() {
	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			ajaxObtainDirList(current_dir);
		}
	}
	
	xhttp.open("POST","textComposer.php",true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send('op=n&dirpath='+ encodeURIComponent(current_dir));
}

function ajaxDeleteFile(file) {
	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			ajaxObtainDirList(current_dir);
		}
	}
	
	xhttp.open("POST","textComposer.php");
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	xhttp.send("op=d&filepath="+file);
}

function ajaxDeleteDir(dir) {
	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			ajaxObtainDirList(current_dir);
		}
	}
	
	xhttp.open("POST","textComposer.php");
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	xhttp.send("op=D&dirpath="+dir);
}

//obtain playlist from server and show it to playlist pane
// dirname should be URL safe
//
function ajaxObtainDirList(dirname) {

	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			let dir_data = JSON.parse(this.responseText);

			let filelist_html = '<div style="width:100%">';
			let name = "";
			for (let i=0;i<dir_data.dir.length;i++){
				name = decodeURIComponent(dir_data.dir[i].split("%2F").slice(-1)[0]);
				filelist_html += '<div class="list_item">';
				filelist_html += '<div class="list-item-name" style="color:lawngreen" onclick=actionBrowserItemClick(\"'+dir_data.dir[i]+'\") >';
				filelist_html += '&#x21b3; ' + name;
				filelist_html += '</div>';
				filelist_html += '<button onclick=actionRenameFile(\"'+dir_data.dir[i]+'\") class="track_button">&#x270e;</button>';
				filelist_html += '<button onclick=actionDeleteDir(\"'+dir_data.dir[i]+'\") class="track_button">x</button>';
				filelist_html += '</div>';
			}

			for (let i=0;i<dir_data.file.length;i++){
				name = decodeURIComponent(dir_data.file[i].split("%2F").slice(-1)[0]);
				filelist_html += '<div class="list_item">';
				filelist_html += '<div class="list-item-name" onclick=actionFileOpen(\"'+dir_data.file[i]+'\")>';
				filelist_html += name;
				filelist_html += '</div>';
				filelist_html += '<button onclick=actionRenameFile(\"'+dir_data.file[i]+'\") class="track_button">&#x270e;</button>';
				filelist_html += '<button onclick=actionDeleteFile(\"'+dir_data.file[i]+'\") class="track_button">x</button>';
				filelist_html += '</div>';
			}
			document.getElementById("editor-element").innerHTML = filelist_html;
			if (current_dir != dirname){
				current_dir = dirname + "%2F";
			}
			document.getElementById("current_dir").innerHTML = decodeURIComponent(current_dir).replace(home_dir,'') ;
		}
	}

	xhttp.open("GET","getDirList.php?dir="+dirname,true);
	xhttp.send();
}

function ajaxObtainFileContent(file) {

	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("current_dir").innerHTML = decodeURIComponent(file).replace(home_dir,'') ;
			document.getElementById("editor-element").innerHTML = this.responseText;
			setEditable();
			current_file = file;
		}
	}
	xhttp.open("GET","textComposer.php?op=o&filepath="+file,true);
	xhttp.send();
}

function ajaxSaveFile(file) {

	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//indicate success 
		}
	}
	xhttp.open("POST","textComposer.php",true);
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	content = document.getElementById("editor-element").innerHTML;

	let filedata = 'op=s&filepath=' + encodeURIComponent(file) +'&content='+encodeURIComponent(content);

	xhttp.send(filedata);
}

function ajaxRenameFile(oldpath,newname){
	let x = new XMLHttpRequest();
	x.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			ajaxObtainDirList(current_dir);
		}
	}
	x.open("POST","textComposer.php",true);
	x.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	newpath = current_dir + newname;
	x.send('op=r&oldpath='+encodeURIComponent(oldpath)+'&newpath='+encodeURIComponent(newpath));
}

function ajaxNewDir(name){
	let x = new XMLHttpRequest();
	x.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			ajaxObtainDirList(current_dir);
		}
	}
	x.open("POST","textComposer.php",true);
	x.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	dirpath = current_dir + name;
	x.send('op=m&dirpath='+ dirpath);
}


function mediaListener(x) {
	if(x.matches){
		//bigger than xxx px wide
	}
}


//execution scripts 

let mediaEv = window.matchMedia("(min-width:500px)");
mediaEv.addListener(mediaListener);

window.addEventListener("DOMContentLoaded", initPlayer);
