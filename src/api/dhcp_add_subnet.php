<?php

session_start();

include ("config.php");
include '../script_conf/connexion_ssh.php';
include '../script_conf/script_conf_dhcp.php';

$error = "";
		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["ip"]) && !empty($_POST["net_ip"]) && !empty($_POST["netmask"]) && !empty($_POST["range_s"]) && !empty($_POST["range_e"]) && !empty($_POST["routers"]) && !empty($_POST["name_serv"]) && !empty($_POST["domain_name"]) && !empty($_POST["default_time"]) && !empty($_POST["lease_time"]))
			{
				$ip = $_POST["ip"];
				
				$array_key = array("range", "option routers", "option domain-name-servers", "option domain-name", "default-lease-time", "max-lease-time");
				$array_value = array($_POST["range_s"]." ".$_POST["range_e"], $_POST["routers"], $_POST["name_serv"], "\"".$_POST["domain_name"]."\"", $_POST["default_time"], $_POST["lease_time"]);
				add_subnet_dhcp($_POST["net_ip"], $_POST["netmask"], $array_key, $array_value, "../tmp_file/service_dhcp".$ip);
				
				
				try
				{
					$ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
					$ssh->connect();
					$ssh->send_file("/ihm_test_suse/tmp_file/service_dhcp".$ip, "/etc/dhcpd.conf", null);
					$ssh->ssh_exec("echo '1HM_c0ntr0l3ur' | sudo -S service dhcpd restart");
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