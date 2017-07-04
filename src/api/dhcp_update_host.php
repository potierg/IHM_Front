<?php

session_start();

include ("config.php");
include '../script_conf/connexion_ssh.php';
include '../script_conf/script_conf_dhcp.php';

$error = "";
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["ip"]) && !empty($_POST["net_ip"]) && !empty($_POST["netmask"])  && !empty($_POST["hostname"]) && !empty($_POST["mac"]) && !empty($_POST["addr"]))
			{
				$ip = $_POST["ip"];
				
				$array_key = array("hardware ethernet", "fixed-address");
				$array_value = array($_POST["mac"], $_POST["addr"]);

				update_host_dhcp($_POST["net_ip"], $_POST["netmask"], $_POST["hostname"], $array_key, $array_value, "../tmp_file/service_dhcp".$ip);
				try
				{
					$ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
					$ssh->connect();
					$ssh->send_file("/ihm_test_suse/tmp_file/service_dhcp".$ip, "/etc/dhcpd.conf", null);
					
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