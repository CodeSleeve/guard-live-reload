// (function(ctx)
// {
	function onMessage(evt) 
	{ 
		var received_msg = evt.data;
		alert("Message is received...");
	}

	function onOpen()
	{
		// Web Socket is connected, send data using send()
		ws.send("Message to send");
		alert("Message is sent...");
	}

	function onClose()
	{
		console.log('hmm, closed');
	}

	function startPolling()
	{
		setInterval(function()
		{
			var xmlhttp;
			var time = (new Date).getTime();

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
				{
					if (xmlhttp.responseText === 'yes')
					{
						window.location.reload();
					}
				}
			}

			xmlhttp.open("GET", "/watcher-live-reload/should-i-reload?date=" + time, true);
			xmlhttp.send();

		}, 2000);
	}

	function startWebSocketConnection()
	{
	  if ("WebSocket" in window)
	  {
	     var ws = new WebSocket("ws://localhost:8000/watcher-live-reload/ws");

	     ws.onopen = onOpen;
	     ws.onmessage = onMessage;
	     ws.onclose = onClose;

	     return true;
	  }

	  return false;
	}

	if (startWebSocketConnection() == false)
	{
		startPolling();
	}

//})(this);