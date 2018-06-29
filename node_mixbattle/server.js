var http = require('http');
var fs = require('fs');
var mysql = require('mysql');

// Chargement du fichier index.html affiché au client
var server = http.createServer(function(req, res) {
	fs.readFile('./../index.php', 'utf-8', function(error, content) {
		res.writeHead(200, {"Content-Type": "text/html"});
		res.end(content);
	});
});

var io = require('socket.io').listen(server);

var con = mysql.createConnection({
	host: "127.0.0.1",
	user: "root",
	password: "root",
	database: "mixbattle"
});

con.connect(function(err) {
	if (err) throw err;
	console.log("Connected!");
});

/*io.sockets.on('message', function (message) {
	console.log('Un client me parle ! Il me dit : ' + message);
});*/

io.sockets.on('connection', function(socket) {
	console.log('Nouvel arrivant');
	con.query("SELECT * FROM `theme_en_cours`", function (err, result, fields) {
		if (err) throw err;
		console.log("Le theme est " + result[0].theme_en_cours);
		socket.emit('message', result[0].theme_en_cours);
	});

	function date() {
		d = new Date().toLocaleTimeString();
		if (d === "23:59:59") {
			con.query("SELECT * FROM `themes` ORDER BY RAND() LIMIT 1", function (err, result, fields) {
				if (err) throw err;
				id = result[0].id_theme;
				theme = result[0].nom_theme;
				console.log(theme);
				var update = "UPDATE `theme_en_cours` SET `theme_en_cours`=?, `id`=? WHERE `id_en_cours`=0";
				var filtre = [theme, id];
				con.query(update, filtre, function (err, result, fields) {
					if (err) throw err;
					con.query("SELECT * FROM `theme_en_cours`", function (err, result, fields) {
						if (err) throw err;
						console.log("Le theme est " + result[0].theme_en_cours);
						socket.emit('message', result[0].theme_en_cours);
					});
				});
			});
		}

		var x = setInterval(function() {

			var countDownDate = new Date("Sep 6, 5000 23:59:59").getTime();

		    // Get todays date and time
		    var now = new Date().getTime();
		    
		    // Find the distance between now an the count down date
		    var distance = countDownDate - now;
		    
		    // Time calculations for days, hours, minutes and seconds
		    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		    var timer = hours + "h " + minutes + "m " + seconds + "s ";
		    socket.emit('time', timer);
		    
		    // If the count down is over, write some text 
		    if (distance < 0) {
		    	
		    	socket.emit('time', "EXPIRED");
	   		}
	   		clearInterval(x);
		}, 200);
	
	}

	setInterval(date, 200);
});

server.listen(8081);
