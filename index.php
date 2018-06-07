<title></title>

<h1 id="title"></h1>

<p id="time"></p>

<a href="lecteur.php">Lecteur</a><br><br>
<a href="upload.php">Upload</a>

<!-- <p><input type="button" value="Embêter le serveur" id="poke" /></p> -->
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
<script type="text/javascript">

var socket = io.connect('http://151.236.62.165:8081');

socket.on('message', function(message, time) {
		document.title = message.charAt(0).toUpperCase() + message.substring(1).toLowerCase();
        document.getElementById("title").innerHTML = "Le thème est : " + message.charAt(0).toUpperCase() + message.substring(1).toLowerCase();
        //document.getElementById("time").innerHTML = time;
    })

socket.on('time', function(time) {
        document.getElementById("time").innerHTML = "Le thème changera dans " + time;
    })

/*$('#poke').click(function () {
            socket.emit('message', 'Salut serveur, ça va ?');
        })*/

</script>

