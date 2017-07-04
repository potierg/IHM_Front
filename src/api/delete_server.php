<?php
session_start();
include ("config.php");

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["id"]))
{
				$id = mysqli_real_escape_string($db, $_POST["id"]);
				mysqli_query($db, "DELETE FROM `IHM_Test_OpenSUSE`.`servers` WHERE `servers`.`index` = $id");
				echo 'ok';
}
else
{
	echo 'fail';
}
?>