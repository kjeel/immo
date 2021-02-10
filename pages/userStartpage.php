<?php
//startet session
session_start();
// Bindet headerfile und datenbank file ein
include_once("../includes/header.php");
require_once('../classes/database.php');
// legt neues Userobjekt an
$user = new User();
// legt neues Datenbankobjekt an
$database = new Database();
// schreibt Userdaten in die Session
$user = unserialize($_SESSION['sessionuser']);

//gibt alle Immobilien aus je nachdem welchem bereich der User zugeordnet ist
$immos = $database->getAllImmobilien($user->immobereichid);


if (isset($_GET['passwordreset'])) {
	$oldpassword = $_POST['oldpassword'];
	$newpassword = $_POST['newpassword'];
	$passwordverify = $_POST['passwordverify'];

	if (password_verify($oldpassword, $user->password)) {
		if ($newpassword == $passwordverify) {
			$changePassword = $database->changePassword($newpassword, $user->userid);
			echo "Das Kennwort wurde erfolgreich geändert";
		} else {
			echo "Die neue Kennwort wurde nicht zwei mal richtig eingegeben";
		}
	} else {
		echo "Das eingebene alte Kennwort ist nicht korrekt bitte versuchen sie es noch einmal";
	}
}




?>

<h1><?php echo "Hallo, " . $user->lastname . " " . $user->firstname; ;?></h1>

<center>
<details>
	<summary>Immobilien anzeigen</summary>
		<table border="1">
			<tr>
				<th>Anzeigename</th>
				<th>Dokumentename</th>
				<th>Download</th>
			</tr>
			<?php foreach($immos as $i) {?>
			<tr>
				<td><?php echo $i['anzeigenamen'];?></td>
				<td><?php echo $i['path'];?></td>
				<td><a href="../documents/<?php echo $i['path'];?>">Download</a></td>
			</tr>
			<?php } ?>
		</table>
</details>
<br>
<details>
	<summary>Passwort ändern</summary>
	<form action="?passwordreset" method="post">
		<input type="password" name="oldpassword" placeholder="Altes Kennwort"> <br>
		<input type="password" name="newpassword" placeholder="Neues Kennwort"> <br>
		<input type="password" name="passwordverify" placeholder="Neues Kennwort wiederholen"> <br>
		<input type="submit" value="Passwort zurücksetzen">
	</form>
</details>
</center>





<?php 
include_once("../includes/footer.php");
 ?>