<?php

session_start();

include ("config.php");
					
$error = "";

			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["login"]) && !empty($_POST["password"]))
				{
					$username=$_POST['login'];
					$password=$_POST['password'];
					$username = stripslashes($username);
					$password = stripslashes($password);
					$username = mysqli_real_escape_string($db, $username);
					$password = mysqli_real_escape_string($db, $password);
					$password = md5(PREFIX_SALT.$password.SUFFIX_SALT);
					$sql="SELECT * FROM User WHERE name='$username' and password='$password'";
					$result=mysqli_query($db,$sql);
					$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
					if(mysqli_num_rows($result) == 1)
						{
							$_SESSION['login_user'] = $username; 
							echo 'ok';
							//header('Location: http://ihmtestsuse.eloryal.fr/welcome.php');
						} else {
							echo 'fail';
							$error = "Incorrect username or password.";
					}
				}
				
 			else if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["signup_login"]) && !empty($_POST["signup_password1"]) && !empty($_POST["signup_password2"]))
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
					echo 'invalid args'
?>