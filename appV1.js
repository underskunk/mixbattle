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
	password: "",
	database: "mixbattle"
});

con.connect(function(err) {
	if (err) throw err;
	console.log("Connected!");
});

io.sockets.on('message', function (message) {
		console.log('Un client me parle ! Il me dit : ' + message);
	});

// Quand un client se connecte, on le note dans la console
io.sockets.on('connection', function (socket) {
	console.log('Un client est connecté !');
	//socket.emit('message', theme);
	//socket.broadcast.emit('message', theme);

	function intTheme() {
		con.query("SELECT `nom_theme` FROM `themes` ORDER BY RAND() LIMIT 1", function (err, result, fields) {
			if (err) throw err;
			console.log("Repeat theme " + result[0].nom_theme);
			theme = result[0].nom_theme;
			socket.emit('message', result[0].nom_theme);
			socket.broadcast.emit('message',  result[0].nom_theme);
		});
	}

	setInterval(intTheme, 10000);

function tps() {
	d = new Date().toLocaleTimeString();
	socket.emit('time', d);
	socket.broadcast.emit('time', d);
}

setInterval(tps, 1000);
	//socket.broadcast.emit('message', 'Un autre client vient de se connecter !');
	

	socket.on('message', function (message) {
		console.log('Un client me parle ! Il me dit : ' + message);
	});

});

/*function intTheme(theme) {
	con.query("SELECT `nom_theme` FROM `themes` ORDER BY RAND() LIMIT 1", function (err, result, fields) {
		if (err) throw err;
		console.log("Repeat theme " + result[0].nom_theme);
		theme = result[0].nom_theme;
		socket.emit('message', result[0].nom_theme);
		socket.broadcast.emit('message',  result[0].nom_theme);
	});
}


*/

server.listen(8081);