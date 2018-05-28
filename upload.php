<!DOCTYPE html>
<html>
<body>

<form method="POST" enctype="multipart/form-data">
    Choisissez une fichier a envoyer:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>

<?php
$target_dir = "music/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$error_sentence = "";
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
        $uploadOk = 1;
}

// Check if file already exists
if (file_exists($target_file)) {
    $error_sentence  = " Ce fichier existe déjà.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 1000000000) {
    $error_sentence = " Ce fichier fait plus de 10Mo.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "mp3" && $imageFileType != "wav" && $imageFileType != "ogg") {
    $error_sentence = " Nous n'acceptons que les formats mp3, wav et ogg.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Désolé, ce dossier n'a pas pu être envoyé.".$error_sentence;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Le fichier ". basename( $_FILES["fileToUpload"]["name"]). " a bien été envoyé.";
    } else {
        echo "Désolé, une erreur est survenu lors de l'envoie de ce fichier.";
    }
}
?>
