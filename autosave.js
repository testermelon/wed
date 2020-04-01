
//debug
let debug = true;

//Capture dom elements
let elemForm = document.getElementById('wed-form');
let elemFormText = document.getElementById('form-text');
let elemStatusText= document.getElementById('status-text');

//build timer object to hold single timer instance
let timeoutHandler = {
	timer : 0,
	start : function(){
		clearTimeout(this.timer);
		this.timer = 0;
		this.timer = setTimeout(ajaxSave,5000);
		if (debug) console.log("timer was reset");
		elemStatusText.innerHTML = "Edited";
		elemStatusText.style = "color:red";
	}
}

//listens to changes in the text area form
elemFormText.addEventListener('change',timeoutHandler.start);
elemFormText.addEventListener('keyup',timeoutHandler.start);
elemFormText.addEventListener('keydown',timeoutHandler.start);

//autosaving ajax function
function ajaxSave(){
	if (debug) console.log("ajaxsave executed");

	let xhttp = new XMLHttpRequest();

	//prepare function to handle response of request
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (debug) console.log(this.responseText);
			elemStatusText.innerHTML = "Saved";
			elemStatusText.style = "color:green";
		}
	}

	//prepare xhttp request
	xhttp.open("POST","#",true);

	//obtain form data from elements
	form_data = new FormData(elemForm);
	
	//tell the server that this is an autosave request, 
	//please reply short report instead of full html
	form_data.append("autosave","true");

	for(var data of form_data.values()){
		if (debug) console.log(data);
	}

	//send request
	xhttp.send(form_data);

}
