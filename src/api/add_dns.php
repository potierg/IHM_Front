<?php

session_start();

include ("config.php");
include '../script_conf/connexion_ssh.php';
include '../script_conf/script_dns_suse.php';

$error = "";
		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["ip"]) && !empty($_POST["ip_dns"]) && !empty($_POST["name_dns"]))
			{
				$ip = $_POST["ip"];
				$ip_dns = $_POST["ip_dns"];
				$name_dns = $_POST["name_dns"];
				
				add_dns_ip($ip_dns, "../tmp_file/dns_file-".$ip);
				add_dns_name($name_dns, "../tmp_file/dns_file-".$ip);
				
				try
				{
					$ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
					$ssh->connect();
					$ssh->send_file("/ihm_test_suse/tmp_file/dns_file-".$ip, "/etc/dhcpd.conf", null);
					
					echo "ok";
				}
				catch (Exception $e) {
					echo "fail";
					die();
				}
			}
			else
			{
				echo 'invalid args';
			}
?>