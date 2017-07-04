<?php

session_start();

include ("config.php");
include '../script_conf/connexion_ssh.php';
include '../script_conf/script_conf_dns.php';

$error = "";
		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["data"]))
			{
				$data = explode(",", $_POST['data']);
				$ip = $data[0];
				$type = $data[1];
				
				try
				{
					$ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
					$ssh->connect();
					
					for ($i = 2; $i < count($data); $i += 2)
					{
						if (strlen($data[$i + 1]) > 1)
						     dns_update_linear_option($type, $data[$i], $data[$i + 1], "../tmp_file/service_dns".$ip);
						else
						{
							echo "invalid args";
							die();
						}	
					}
					$ssh->send_file("/ihm_test_suse/tmp_file/service_dns".$ip, "/etc/named.conf", null);
					echo "ok";
				}
				catch (Exception $e) {
					echo "fail";
					die();
				}
			}
?>