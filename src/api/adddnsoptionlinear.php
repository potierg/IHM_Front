<?php

session_start();

include ("config.php");
include '../script_conf/connexion_ssh.php';
include '../script_conf/script_conf_dns.php';

$error = "";
		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["ip"]) && !empty($_POST["key"]) && !empty($_POST["value"]) && !empty($_POST["type"]))
			{
				$ip = $_POST["ip"];
				$type = $_POST["type"];
				
				dns_add_linear_option($type, $_POST["key"], $_POST["value"], "../tmp_file/service_dns".$ip);
				try
				{
					$ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
					$ssh->connect();
					
					$ssh->send_file("/ihm_test_suse/tmp_file/service_dns".$ip, "/etc/named.conf", null);
					echo "ok";
				}
				catch (Exception $e) {
					echo "fail";
					die();
				}
			}
			else
				echo "invalid args";
?>