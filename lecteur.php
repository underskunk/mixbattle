<style type="text/css">
.volume {
	vertical-align: bottom;
	display: inline-block;
	height: 25px;
	white-space: nowrap;
	position: relative;
	width: 45px;
}

.volume a {
	display: inline-block;
	background: #ebebeb;
	width: 8px;
	vertical-align: bottom;
	margin-left: 0px;
	margin-right: 0px;
	cursor: pointer;
	border-left: 1px solid #fff;
	position: absolute;
	bottom: 0;	
}


.stick1 { height: 5px;  left: 0px; }
.stick2 { height: 10px; left: 9px; }
.stick3 { height: 15px; left: 18px; }
.stick4 { height: 20px; left: 27px; }
.stick5 { height: 25px; left: 36px; }

.progress {
	vertical-align: bottom;
	display: inline-block;
	height: 25px;
	white-space: nowrap;
	position: relative;
	width: 300px;
	background: grey;
}
</style>

<?php
$hote = '127.0.0.1';
$port = "";
$nom_bdd = 'mixbattle';
$utilisateur = 'root';
$mot_de_passe = 'root';

try {
    //On test la connexion à la base de donnée
    $pdo = new PDO('mysql:host='.$hote.';port='.$port.';dbname='.$nom_bdd, $utilisateur, $mot_de_passe);
} catch(Exception $e) {
    echo "Connection failed";
}

$add_name_request = $pdo->prepare("SELECT * FROM `son`;");
$add_name_request->bindParam(':name', $basename);
$add_name_request->execute();

$source['source'] = $add_name_request->fetchall();
foreach ($source['source'] as $key => $value) {
	$nom[$key] = $value['nom'];
}
// $title = implode('","', $nom);

for ($i = 0; $i < count($nom); $i++) {

?>

	<a id="<?php echo $i; ?>" onclick="add('<?php echo $i; ?>')"><?php echo $nom[$i]; ?></a><br>

<?php

}

?>

<p id="p"></p>

<audio id="audioPlayer" ontimeupdate="update(this)" src=""></audio>

<div>
	<div class="progress" id="progressBarControl" onclick="clickProgress('audioPlayer', this, event)">
		<div id="progressBar" >Pas de lecture</div>
	</div>
</div>
<span id="progressTime">00:00</span>

<button class="control" onclick="play('audioPlayer', this)">Play</button>
<button class="control" onclick="prev('audioPlayer', this)">Previous</button>
<button class="control" onclick="next('audioPlayer', this)">Next</button>

<span class="volume">
	<a class="stick1" onclick="volume('audioPlayer', 0)"></a>
	<a class="stick2" onclick="volume('audioPlayer', 0.3)"></a>
	<a class="stick3" onclick="volume('audioPlayer', 0.5)"></a>
	<a class="stick4" onclick="volume('audioPlayer', 0.7)"></a>
	<a class="stick5" onclick="volume('audioPlayer', 1)"></a>
</span>


<br><a href="upload.php">Upload</a>

<script type="text/javascript">
	audio = document.getElementById("audioPlayer");
	namespace = "music/";
	src = [];
	index = 0;
	indexMax = 0;
	
	current = src[index];
	// document.getElementById("p").innerHTML = indexMax;

	function add(id) {
		source = document.getElementById(id);
		titre = source.innerText || source.textContent;
		src[indexMax] =  titre;
		indexMax = src.length;
		document.getElementById("p").innerHTML = src;
		return src;
		}

	function next(idPlayer, control) {
		var player = document.querySelector('#' + idPlayer);
		if (index > src.length - 2) {
			return;
		} else {
			current = src[index + 1];
			audio.setAttribute("src", namespace + current);
			player.play();
			index = index + 1;
			return index;
		}
	}

	function prev(idPlayer, control) {
		var player = document.querySelector('#' + idPlayer);
		if (index < 1) {
			return;
		} else {
			current = src[index - 1];
			audio.setAttribute("src", namespace + current);
			player.play();
			index = index - 1;
			return index;
		}
	}

	function play(idPlayer, control) {
		var player = document.querySelector('#' + idPlayer);
		if (player.paused) {
			current = src[index];
			audio.setAttribute("src", namespace + current);
			player.play();
			control.textContent = 'Pause';
		} else {
			player.pause();	
			control.textContent = 'Play';
		}
	}

	function volume(idPlayer, vol) {
		var player = document.querySelector('#' + idPlayer);

		player.volume = vol;
	}

	function update(player) {
	    var duration = player.duration;    // Durée totale
	    var time     = player.currentTime; // Temps écoulé
	    var fraction = time / duration;
	    var percent  = Math.ceil(fraction * 100);

	    var progress = document.querySelector('#progressBar');

	    progress.style.width = percent + '%';
	    progress.textContent = percent + '%';
	    document.querySelector('#progressTime').textContent = formatTime(time);
	}

	function formatTime(time) {
	    var hours = Math.floor(time / 3600);
	    var mins  = Math.floor((time % 3600) / 60);
	    var secs  = Math.floor(time % 60);
		
	    if (secs < 10) {
	        secs = "0" + secs;
	    }
		
	    if (hours) {
	        if (mins < 10) {
	            mins = "0" + mins;
	        }
			
	        return hours + ":" + mins + ":" + secs; // hh:mm:ss
	    } else {
	        return mins + ":" + secs; // mm:ss
	    }
	}

	function getMousePosition(event) {
	    return {
	        x: event.pageX,
	        y: event.pageY
	    };
	}

	function getPosition(element){
	    var top = 0, left = 0;
	    
	    do {
	        top  += element.offsetTop;
	        left += element.offsetLeft;
	    } while (element = element.offsetParent);
	    
	    return { x: left, y: top };
	}

	function clickProgress(idPlayer, control, event) {
	    var parent = getPosition(control);    // La position absolue de la progressBar
	    var target = getMousePosition(event); // L'endroit de la progressBar où on a cliqué
	    var player = document.querySelector('#' + idPlayer);
	  
	    var x = target.x - parent.x; 
	    var wrapperWidth = document.querySelector('#progressBarControl').offsetWidth;
	    
	    var percent = Math.ceil((x / wrapperWidth) * 100);    
	    var duration = player.duration;
	    
	    player.currentTime = (duration * percent) / 100;
	}
</script>
