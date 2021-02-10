<?php 
require_once('immobilien.php');
require_once('immobilienbereich.php');
require_once('users.php');


class Database {

	private $mysqli;
	public function __construct() {
		$this->mysqli = new mysqli('localhost', 'root', '', 'lap3');
		if ($this->mysqli -> connect_error) {
 			 echo "Failed to connect to MySQL: " . $this->mysqli -> connect_error;
		} else {
			return true;
		}
	}
	public function getDatabase() {
		return $this->mysqli;
	}

	public function login($email, $password) {
		$statement = "SELECT * FROM `users` WHERE email = '$email'";

		$result = mysqli_query($this->mysqli, $statement);
		$sqlResult = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if(count($sqlResult) > 0) {
			$user = new User();
			$user->userid = $sqlResult['userid'];
			$user->firstname = $sqlResult['firstname'];
			$user->lastname = $sqlResult['lastname'];
			$user->email = $sqlResult['email'];
			$user->password = $sqlResult['password'];
			$user->street = $sqlResult['street'];
			$user->city = $sqlResult['city'];
			$user->country = $sqlResult['country'];
			$user->plz = $sqlResult['plz'];
			$user->userrole = $sqlResult['userrole'];
			$user->immobereichid = $sqlResult['immobereichid'];
		} else {
			echo "Kein Benutzer mit der E-Mail " . $email . " gefunden";
		} 
		if ($user) {
			if (password_verify($password, $user->password)) {
				return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	public function registerUser($firstname, $lastname, $email, $password, $street, $city, $plz, $country, $userrole, $immobereichid) {
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$statement = "INSERT INTO `users`(`firstname`, `lastname`, `email`, `password`, `immobereichid`, `userrole`, `street`, `city`, `plz`, `country`) VALUES ('$firstname', '$lastname', '$email', '$hash', '$immobereichid', '$userrole', '$street', '$city', '$plz', '$country')";
		$result = mysqli_query($this->mysqli, $statement);

		return $result;
	}

	public function getAllImmobilien($bereichid) {
		$statement = "SELECT * FROM `immobilien` WHERE immobereichid = '$bereichid'";

		$result_array = array();

		$result = mysqli_query($this->mysqli, $statement);

		while ($row = $result->fetch_array()) {
			$result_array[] = $row;
		}
		return $result_array;
	}

	public function changePassword($newpassword, $userid) {
		$hash = password_hash($newpassword, PASSWORD_DEFAULT);

		$statement = "UPDATE `users` SET `password`= '$hash' WHERE userid = $userid";
		$result = mysqli_query($this->mysqli, $statement);
		return $result;
	}

	public function getAllUsers() {
		$statement = "SELECT * FROM `users` WHERE userrole = '3'";

		$result_array = array();

		$result = mysqli_query($this->mysqli, $statement);

		while ($row = $result->fetch_array()) {
			$result_array[] = $row;
		}
		return $result_array;
	}

	public function getAllMaklers() {
		$statement = "SELECT * FROM `users` WHERE userrole in (2,4)";

		$result_array = array();

		$result = mysqli_query($this->mysqli, $statement);

		while ($row = $result->fetch_array()) {
			$result_array[] = $row;
		}
		return $result_array;
	}

	public function getAllImmobilienAdmin() {
		$statement = "SELECT * FROM `immobilien`";

		$result_array = array();

		$result = mysqli_query($this->mysqli, $statement);

		while ($row = $result->fetch_array()) {
			$result_array[] = $row;
		}
		return $result_array;
	}

	public function newImmoBereich($immobilienBereich) {
		$statement = "INSERT INTO `immobilienbereich` (`immobilienbereich`) VALUES ('$immobilienBereich')";
		$result = mysqli_query($this->mysqli, $statement);
		return $result;
	}

	public function checkMail($email) {
		$statement = "SELECT * FROM `users` WHERE email = '$email'";
		$result = mysqli_query($this->mysqli, $statement);

		$sqlResult = mysqli_fetch_array($result, MYSQLI_ASSOC);

		//returns true wenn ein User mit der E-Mail gefunden wurde
		if ($sqlResult) {
			return true;
		} else {
			return false;
		}
	}

	public function getAllImmobilienBereiche() {
		$statement = "SELECT * FROM `immobilienbereich`";
		$result_array = array();

		$result = mysqli_query($this->mysqli, $statement);

		while ($row = $result->fetch_array()) {
			$result_array[] = $row;
		}
		return $result_array;
	}

	public function getMaklerLeitung($userid) {
		$statement = "SELECT * FROM immobilienbereich ";
	}
}

 ?>