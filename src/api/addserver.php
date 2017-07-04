<?php

//session_start();

include ("config.php");

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	$post_data = array("service_server", "server_name", "ip_server");			// Arguments that sould be in the post request
	$service_server = array("none", "dhcp", "dns");

	$entityBody = file_get_contents('php://input');
	$json = json_decode($entityBody, true);

	foreach($post_data as &$value) {
		if (!array_key_exists($value, $json)) {
			exit("Invalid arguments");
		}
	}

	if ($json['service_server'] != $service_server[0] &&
		$json['service_server'] != $service_server[1] &&
		$json['service_server'] != $service_server[2]) {
		exit("Invalid service argument");
	}


	$service = mysqli_real_escape_string($db, $json['service_server']);
	$name = mysqli_real_escape_string($db, $json['server_name']);
	$ip = mysqli_real_escape_string($db, $json['ip_server']);


	if (mysqli_num_rows(mysqli_query($db, "SELECT name FROM servers WHERE name = '$name'")) == 0 && 
		mysqli_num_rows(mysqli_query($db, "SELECT ip FROM servers WHERE ip = '$ip'")) == 0) {

		mysqli_query($db, "INSERT INTO servers(name,service,ip) VALUES('$name','$service','$ip')");
		exit("ok");

	} else {
		echo 'server already exist';
	}

} else {
	exit("Bad method request");
}

/*$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["service_server"]) && !empty($_POST["server_name"]) && !empty($_POST["ip_server"]))
{
	$service = mysqli_real_escape_string($db, $_POST["service_server"]);
	$name = mysqli_real_escape_string($db, $_POST["server_name"]);
	$ip = mysqli_real_escape_string($db, $_POST["ip_server"]);
	
	
	if (mysqli_num_rows(mysqli_query($db, "SELECT name FROM servers WHERE name = '$name'")) == 0)
	{
		mysqli_query($db, "INSERT INTO servers(name,service,ip) VALUES('$name','$service','$ip')");
		echo 'ok';
	} else {
		echo 'server already exist';
	}
}
else
{
	echo 'invalid args';
}*/
 		/*	else if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["signup_login"]) && !empty($_POST["signup_password1"]) && !empty($_POST["signup_password2"]))
				{
					$username = $_POST["signup_login"];
					$password = $_POST['signup_password1'];
					$password2 = $_POST['signup_password2'];
 					$uname = mysqli_real_escape_string($db, $username);
 					$pwd = md5(mysqli_real_escape_string($db, PREFIX_SALT.$password.SUFFIX_SALT));
 					$pwd2 = md5(mysqli_real_escape_string($db, PREFIX_SALT.$password2.SUFFIX_SALT));
					if (strcmp($password, $password2) != 0)
 						echo 'fail password';
 					else if ($pwd == $pwd2 && !empty(pwd) && !empty(pwd2))
 					{
 						if (mysqli_num_rows(mysqli_query($db, "SELECT name FROM User WHERE name = '$username'")) == 0)
 						{
 							mysqli_query($db,"INSERT INTO User(name,password) VALUES('$uname','$pwd')");
 							echo 'ok';
 						} else
 						{
 							echo 'fail login';
 						}
 					}
				}
				
				else
				echo 'invalid args'*/
				?>