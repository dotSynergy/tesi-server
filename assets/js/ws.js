let webSocket;
let imgTag = new Image();

checkWebSocket(webSocket);

function checkWebSocket(webSocket){

	if(typeof(webSocket) == "undefined" || (webSocket.readyState == WebSocket.CLOSED)){
		console.log(Date() + ' Connecting webSocket...');
		webSocket = startWebSocket();
	}

	setTimeout(function(){
		checkWebSocket(webSocket)
	}, 1500);
}

function startWebSocket(){

	url = 'wss://dotsynergy.ddns.net:443/wss-drone';

	webSocket = new WebSocket(url);
	let x, y = 0;
		
	webSocket.onmessage = function (event) {
		response = JSON.parse(event.data);
		is_airborne = response.data.is_airborne;

		document.getElementById('ws-data').replaceChildren(buildTable(response.data));
	    canvas = document.getElementById('ws-canvas');
	    cont = document.getElementById('ws-canvas-container');
	    ctx = canvas.getContext("2d");

	    if(!x)
	    	x = canvas.width = cont.clientWidth;
	    if(!y)
	    	y = canvas.height = cont.clientHeight;

	    console.log(x);

	    if(imgTag.src == ''){
			imgTag.src = "assets/img/drone.png";   // load image
			imgTag.onload = animate;
	    }

		l = 0;

		function animate() {
			ctx.clearRect(0, 0, canvas.width, canvas.height);  // clear canvas
			newY = y-(imgTag.height);
			if(is_airborne) newY = newY+(Math.sin(l)*2);

			ctx.drawImage(imgTag, 0, newY);

			l = l+0.1;
			if(is_airborne && (y > (imgTag.height))) y=y-5;
			else if(!is_airborne && y < cont.clientHeight) y=y+5;
			requestAnimationFrame(animate)        // loop

		}
	}

	webSocket.onclose = function(event) {
		if(event.code != 1006)
			wsDisconnected();
	};

	webSocket.onerror = function(event) {
		console.log(event);
		wsError();
	};

	webSocket.onopen = function() {
		wsConnected();
	};


	return webSocket;
}

function wsConnected(){
	document.getElementById('ws-message').replaceChildren(createLabel('green', 'WebSocket Connected'));
}

function wsError(){
	document.getElementById('ws-message').replaceChildren(createLabel('red', 'WebSocket Error'));
	imgTag.onload = null;
}

function wsDisconnected(){
	document.getElementById('ws-message').replaceChildren(createLabel('yellow', 'WebSocket Disconnected'));
	imgTag.onload = null;
}

function createLabel(color, text){
	lbl = document.createElement('label');
	lbl.style.color = color;
	lbl.innerHTML = text;
	return lbl;
}

function buildTable(data){
	var div = document.createElement("div");
	var lbl = document.createElement("label");
	var d = new Date();
	var datestring = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +
    d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2) + ":" + ("0" + d.getSeconds()).slice(-2);

	lbl.innerHTML = 'Last data: '+datestring;
	div.appendChild(lbl);
	// create elements <table> and a <tbody>
	var tbl = document.createElement("table");
	var tblBody = document.createElement("tbody");

	// cells creation
	for (item in data) {
		// table row creation
    	var row = document.createElement("tr");
    	var cell1 = document.createElement("td");
    	var cell2 = document.createElement("td");
    	var cell1Text = document.createTextNode(item);
    	var cell2Text = document.createTextNode(data[item]);
    	cell1.appendChild(cell1Text);
    	cell2.appendChild(cell2Text);

    	row.appendChild(cell1);
    	row.appendChild(cell2);
    	//row added to end of table body
    	tblBody.appendChild(row);
	}

    // append the <tbody> inside the <table>
	tbl.appendChild(tblBody);
	// put <table> in the <body>
	body.appendChild(tbl);
	// tbl border attribute to 
	tbl.setAttribute("width", "auto");

	div.appendChild(tbl);

	return div;
}