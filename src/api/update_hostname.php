<?php
session_start();

include('config.php');
include('../script_conf/connexion_ssh.php');

$error = "";

			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["hostname_input"]) && !empty($_POST["ip_address"]))
			{
		        $new_hostname = $_POST["hostname_input"];
		        $ip = $_POST["ip_address"];
		        
		        try
		        {
		        	$esc_hostname = mysqli_real_escape_string($db, $new_hostname);
		        	$esc_ip = mysqli_real_escape_string($db, $ip);
					mysqli_query($db, "UPDATE servers SET name='$esc_hostname' WHERE ip='$esc_ip'");
		          $ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
		          $ssh->connect();
		          $ssh->get_file("/etc/hostname", "/ihm_test_suse/tmp_file/hostname" . $ip);
		          // TODO: use a real proper function that doesn't just truncate lol
		          $handle = fopen("../tmp_file/hostname_tmp".$ip, "w");
		          ftruncate($handle, 0);
		          fwrite($handle, $new_hostname);
		          fclose($handle);
		          
		          $ssh->send_file("/ihm_test_suse/tmp_file/hostname_tmp".$ip, "/etc/hostname", null);
		          $ssh->ssh_exec("echo '1HM_c0ntr0l3ur' | sudo -S reboot");
		         
   		          echo 'ok';
		        }
		        catch (Exception $e)
		        {
		        	echo 'fail';
					die();
		        }
      		}
      		else
      		{
      			echo 'invalid args';
      		}

?>
