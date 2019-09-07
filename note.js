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
	obtainDirList(updir); 
}

function actionBrowserItemClick(dir) {
	browser_history_back.push(current_dir);
	browser_history_forward = [];
	obtainDirList(dir); 
}

function actionFileOpen(file){
	browser_history_back.push(current_dir);
	browser_history_forward = [];
	obtainFileContent(file);
}
	
function actionBack() {

	if (current_file != ""){
		saveFile(current_file);
		current_file = "";
	}

	let back_dir = browser_history_back.pop();
	if (back_dir == undefined) return;

	//slice out trailing slash
	let slice_dir = back_dir.split("%2F");
	slice_dir.pop();
	back_dir = slice_dir.join("%2F");

	browser_history_forward.push(current_dir);
	obtainDirList(back_dir); 
}

function actionRename() {
	let name = prompt("Nama barunya apa?", playlist_list[playlist_showing_no]);
	if(name==null) return;
	renamePlaylist(playlist_showing_no,name);
}

function actionHome(){
	if(current_dir == encodeURIComponent(home_dir + "/")) return;
	obtainDirList(encodeURIComponent(home_dir)); 
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
	obtainDirList(encodeURIComponent(home_dir));
	unsetEditable();

}

function setEditable() {
	element = document.getElementById("editor-element");
	element.contentEditable = true;
	element.spellcheck = false;

}

function unsetEditable() {
	document.getElementById("editor-element").contentEditable = false;
}




//**********************
// AJAX codes
//**********************

function addFile(path) {
	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			obtainPlaylist(playlist_showing_no);
			if(playlist_no == playlist_showing_no){
				playlist = playlist_showing;
				if(shuffle) updateShuffleList();
			}
		}
	}
	
	xhttp.open("GET","playlist.php?op=add&pl="+ plselectdom.value +"&path="+path,true);
	xhttp.send();
}

function delTrack(plno,trno) {
	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			obtainPlaylist(playlist_showing_no);

		}
	}
	
	xhttp.open("GET","playlist.php?op=dt&pl="+ plno + "&tr=" + trno,true);
	xhttp.send();
}

//obtain playlist from server and show it to playlist pane
// dirname should be URL safe
//
function obtainDirList(dirname) {

	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			let dir_data = JSON.parse(this.responseText);

			let playlist_html = '<div style="width:100%">';
			let name = "";
			for (let i=0;i<dir_data.dir.length;i++){
				name = decodeURIComponent(dir_data.dir[i].split("%2F").slice(-1)[0]);
				playlist_html += '<div class="list_item">';
				playlist_html += '<div class="list-item-name" style="color:lawngreen" onclick=actionBrowserItemClick(\"'+dir_data.dir[i]+'\") >';
				playlist_html += '&#x21b3; ' + name;
				playlist_html += '</div>';
				playlist_html += '</div>';
			}

			for (let i=0;i<dir_data.file.length;i++){
				name = decodeURIComponent(dir_data.file[i].split("%2F").slice(-1)[0]);
				playlist_html += '<div class="list_item">';
				playlist_html += '<div class="list-item-name" onclick=actionFileOpen(\"'+dir_data.file[i]+'\")>';
				playlist_html += name;
				playlist_html += '</div>';
				playlist_html += '<button class="track_button">x</button>';
				playlist_html += '</div>';
			}
			document.getElementById("editor-element").innerHTML = playlist_html;
			current_dir = dirname + "%2F";
			document.getElementById("current_dir").innerHTML = decodeURIComponent(current_dir).replace(home_dir,'') ;
		}
	}
	xhttp.open("GET","getDirList.php?dir="+dirname,true);
	xhttp.send();
}

function obtainFileContent(file) {

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

function saveFile(file) {

	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			alert("saved");
		}
	}
	xhttp.open("POST","textComposer.php",true);
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	content = document.getElementById("editor-element").innerHTML;

	let filedata = 'op=s&filepath=' + encodeURIComponent(file) +'&content='+encodeURIComponent(content);

	xhttp.send(filedata);
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
