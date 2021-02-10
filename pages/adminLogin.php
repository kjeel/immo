<?php
session_start();
include_once("../includes/header.php");
require_once('../classes/database.php');
require_once('../classes/users.php');
$user = new User();
$database = new Database();
$user = unserialize($_SESSION['sessionuser']);

// Wenn Login Button gedrückt dann schreibt er die eingegebenen Daten in Variablen
if(isset($_GET['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	//eingebene Variablen werden an die Login Funktionen übergeben
	$user = $database->login($email, $password);

	// Wenn login Funktion Werte zurückliefert und die Userrole Admin bzw. Admin Makler ist leite weiter auf Startpage
	if ($user && $user->userrole == 1 OR $user->userrole == 2) {
		// Schreibt userwerte in Session
		$_SESSION['sessionuser'] = serialize($user);
		// Weiterleitung an neue Seite
		header("Location: ../pages/adminStartpage.php");
	} else {
		echo "Login fehlgeschlagen";
	}
	// Wenn Usertyp nur "Makler" leitet direkt auf Maklerpage
	if ($user && $user->userrole == 4) {
		//Schreibt userdaten in session
		$_SESSION['sessionuser'] = serialize($user);
		// Weiterleitung an Maklerseite
		header("Location: ../pages/maklerStartpage.php");
	} else {
		echo "Login fehlgeschlagen";
	}
}


?>

<form action="?login=1" method="post">
	<input type="text" name="email" placeholder="E-Mail...">
	<input type="password" name="password" placeholder="Passwort...">
	<input type="submit" value="Login">
</form>

<?php
include_once("../includes/footer.php");
?>