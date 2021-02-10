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

// Wenn Login Button gedrückt dann beschreibt er Variablen
if(isset($_GET['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	// schickt Variablen an die Loginfunktion 
	$user = $database->login($email, $password);
	// Wenn Userrole übereinstimmt wird man auf Userseite witergeleitet und userdaten werden in die Session geschrieben
	if ($user && $user->userrole == 3) {
		$_SESSION['sessionuser'] = serialize($user);
		header("Location: ../pages/userStartpage.php");
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