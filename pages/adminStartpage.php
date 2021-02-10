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

// Ruft Funktionen auf für Userdaten, Maklerdaten und Immobilien
$getAllUsers = $database->getAllUsers();
$getAllMaklers = $database->getAllMaklers();
$getAllImmobilienAdmin = $database->getAllImmobilienAdmin();
$getAllImmobilienBereiche = $database->getAllImmobilienBereiche();
// eingegebene Werte von Formular register werden eingelesen
if (isset($_GET['register'])) {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password_verify = $_POST['password_verify'];
	$street = $_POST['street'];
	$city = $_POST['city'];
	$plz = $_POST['plz'];
	$country = $_POST['country'];
	$userrole = $_POST['userrole'];
	$immobereichid = $_POST['immobereichid'];

	// gibt die eingebene E-Mail an die Funktion checkMail weiter
	$checkMail = $database->checkMail($email);

	// Wenn kein User mit der E-Mail vorhanden ist
	if ($checkMail != true) {
		//vergleicht die eingegebenen Passwörter
		if ($password == $password_verify) {
			// checkt ob es sich um eine valide E-Mail Adresse handelt
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				// übergibt die eingegebenen Daten an die Funktion registerUser
				$newUser = $database->registerUser($firstname, $lastname, $email, $password, $street, $city, $plz, $country, $userrole, $immobereichid);
				// Asusgabe wenn ein User angelegt wurde
 				 if ($newUser) {
 			 		echo "Der Benutzer wurde erfolgreich angelegt";
 			 }
		} else {
			echo "Die eingegebene E-Mail ist ungültig";
		}		 
	} else {
		echo "Passwörter stimmen nicht überein";
		}
	}
	else {
		echo "Die E-Mail Adresse ist bereits vergeben";
	}
}
// Liest die eingebenen Daten und ein ruft die Funktion newImmoBereich auf, welche einen Immobilienbereich anlegt
if (isset($_GET['newbereich'])) {
	$immobilienBereich = $_POST['immobilienbereich'];

	$newImmoBereich = $database->newImmoBereich($immobilienBereich);
	// Gibt eine Meldung wenn ein neuer Immobilienbereich erfolgreich angelegt wurde
	if ($newImmoBereich) {
		echo "Immobilienbereich wurde angelegt";
	}
}

?>

<center>
	<h1><?php echo "Hallo, " . $user->firstname . " " . $user->lastname ;?></h1>
	<details>
	<summary>Benutzerverwaltung</summary>
	<form action="?register=1" method="post">
		<input type="text" name="firstname" placeholder="Vorname...."><br>
		<input type="text" name="lastname" placeholder="Nachname...."><br>
		<input type="text" name="email" placeholder="E-Mail...."><br>
		<input type="password" name="password" placeholder="Passwort...."><br>
		<input type="password" name="password_verify" placeholder="Passwort wiederholen...."><br>
		<input type="text" name="street" placeholder="Straße...."><br>
		<input type="text" name="city" placeholder="Stadt...."><br>
		<input type="text" name="plz" placeholder="Postleitzahl...."><br>
		<input type="text" name="country" placeholder="Land...."><br>
		<label>Benutzertyp:</label>
		<select name="userrole">
			<option value="1">Admin</option>
			<option value="2">Admin-Makler</option>
			<option value="3">User</option>
			<option value="4">Makler</option>
		</select>
		<br>
		<label>Immobilienbereich:</label>
		<select name="immobereichid">
			<?php foreach($getAllImmobilienBereiche as $b) { ?>
			<option value="<?php echo $b['immobereichid']; ?>"><?php echo $b['immobilienbereich']; ?></option>
			<?php } ?>
		</select>	
		<br>
		<input type="submit" value="Benutzer anlegen">
	</form>
</details>
<br>
<details>
	<summary>Benutzer anzeigen</summary>
	<table border="1">
		<tr>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>E-Mail</th>
		</tr>
		<?php foreach ($getAllUsers as $u) { ?>
		<tr>
			<td><?php echo $u['firstname']; ?></td>
			<td><?php echo $u['lastname']; ?></td>
			<td><?php echo $u['email']; ?></td>
		</tr>
		<?php } ?>
	</table>
</details>
<br>
<details>
	<summary>Makler anzeigen</summary>
	<table border="1">
		<tr>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>E-Mail</th>
		</tr>
		<?php foreach ($getAllMaklers as $m) { ?>
		<tr>
			<td><?php echo $m['firstname']; ?></td>
			<td><?php echo $m['lastname']; ?></td>
			<td><?php echo $m['email']; ?></td>
		</tr>
		<?php } ?>
	</table>
</details>
<br>
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
</details>
<br>
<details>
	<summary>Neuen Immobilienbereich anlegen</summary>
	<form action="?newbereich=1" method="post">
		<input type="text" name="immobilienbereich">
		<input type="submit" value="Bereich anlegen">		
	</form>
</details>
<br>
<br>
<details>
	<summary>Maklerverwaltung</summary>
	<?php if ($user->userrole == 2) { ?>
<a href="../pages/maklerStartpage.php">klicken Sie hier um Immobilien zu bearbeiten</a>
<?php } ?>
</details>
</center>






<?php 
include_once("../includes/footer.php");
 ?>