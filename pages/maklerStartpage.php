<?php
session_start();
include_once("../includes/header.php");
require_once('../classes/database.php');
$user = new User();
$database = new Database();
$user = unserialize($_SESSION['sessionuser']);

// Holt sich alle Immobilieninformationen mit eienr Funktion
$getAllImmobilienAdmin = $database->getAllImmobilienAdmin();
if (isset($_GET['upload'])) {
	//definiert den Ordner auf den zugegriffen wrid
$target_dir = "../documents/";
// Pfad wo das PDF hochgeladen wird
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// die Fileextions wird in Kleinhuchstaben umgewandelt
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Meldung falls Upload erfolgreich
 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } 
}

?>

<center>
	<details>
	<summary>Immobilien anzeigen</summary>
	<table border="1">
		<tr>
			<th>Anzeigenamen</th>
			<th>Dokumentennamen</th>
			<th>Download</th>
		</tr>
		<?php foreach ($getAllImmobilienAdmin as $i) { ?>
		<tr>
			<td><?php echo $i['anzeigenamen']; ?></td>
			<td><?php echo $i['path']; ?></td>
			<td><a href="../documents/<?php echo $i['path'];?>">Download</a></td>
		</tr>
		<?php } ?>
	</table>
	<form action="?upload=1" method="post" enctype="multipart/form-data">
		<p> Bitte verwenden Sie den gleichen PDF Namen damit der Austausch richtig funktioniert und zugeorndet werden kann</p>
  Select PDF to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="PDF hochladen" name="submit">
</form>
</details>
</center>




<?php
include_once("../includes/footer.php");
?>