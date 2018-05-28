<title></title>

<h1 id="title"></h1>

<p id="time"></p>








<!-- <p><input type="button" value="Embêter le serveur" id="poke" /></p> -->
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="/socket.io/socket.io.js"></script>
<script type="text/javascript">

var socket = io.connect('http://localhost:8081');

socket.on('message', function(message, time) {
		document.title = message;
        document.getElementById("title").innerHTML = "Le thème est : " + message;
        //document.getElementById("time").innerHTML = time;
    })

socket.on('time', function(time) {
        document.getElementById("time").innerHTML = "Le thème changera dans " + time;
    })

/*$('#poke').click(function () {
            socket.emit('message', 'Salut serveur, ça va ?');
        })*/

</script>

