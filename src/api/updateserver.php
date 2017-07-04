<?php

session_start();

include ("config.php");
					
$error = "";
		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["service_server"]) && !empty($_POST["service_id"]) && !empty($_POST["server_name"]) && !empty($_POST["ip_server"]))
			{
				$id = mysqli_real_escape_string($db, $_POST["service_id"]);
				$service = mysqli_real_escape_string($db, $_POST["service_server"]);
				$name = mysqli_real_escape_string($db, $_POST["server_name"]);
				$ip = mysqli_real_escape_string($db, $_POST["ip_server"]);
				
				//To UPDATE entry
				mysqli_query($db, "UPDATE `servers` SET `name` = \"$name\", `service` = \"$service\", `ip` = \"$ip\" WHERE `index` = $id");
				echo 'ok';
			}
			else
			{
				echo 'invalid args';
			}
?>