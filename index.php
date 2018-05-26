<title></title>

<h1 id="title"></h1>

<p id="time"></p>

<form method="POST" enctype="multipart/form-data">
     <!-- On limite le fichier à 100Ko -->
     <input type="hidden" name="MAX_FILE_SIZE" value="100000">
     Fichier : <input type="file" name="avatar">
     <input type="submit" name="envoyer" value="Envoyer le fichier">
</form>

<?php
$dossier = '/music/';
$fichier = basename($_FILES['avatar']['name']);
$taille_maxi = 100000;
$taille = filesize($_FILES['avatar']['tmp_name']);
$extensions = array('.mp3', '.gif', '.jpg', '.jpeg');
$extension = strrchr($_FILES['avatar']['name'], '.'); 
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici...
	$fichier = strtr($fichier, 
		'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
		'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
     	echo 'Upload effectué avec succès !';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
     	echo 'Echec de l\'upload !';
     	var_dump($fichier);
     }
 }
else
{
     echo $erreur;
}
?>

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

