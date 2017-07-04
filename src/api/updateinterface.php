<?php

//session_start();

include ("config.php");
include '../script_conf/script_conf_res.php';
include '../script_conf/connexion_ssh.php';

$error = "";
		
			if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["ip"]) && !empty($_POST["inter"]) && !empty($_POST["type"]) && !empty($_POST["auto"]))
			{
				$ip = $_POST["ip"];
				$interface = $_POST["inter"];
				
				$type = $_POST["type"];
				$array_key = array("BOOTPROTO", "BROADCAST", "IPADDR", "NETMASK", "NETWORK", "STARTMODE", "DHCLIENT_SET_DEFAULT_ROUTE");
				$array_value = array();
				$auto = $_POST["auto"];

				if ($type === "dhcp")
				{
					$array_value = array("dhcp", "", "", "", "", $auto, "yes");
				}
				else if ($type === "static" && !empty($_POST["broadcast"]) && !empty($_POST["ipaddr"]) && !empty($_POST["netmask"]) && !empty($_POST["network"]))
				{
					$broadcast = $_POST["broadcast"];
					$ipaddr = $_POST["ipaddr"];
					$netmask = $_POST["netmask"];
					$network = $_POST["network"];
					$array_value = array("static", $broadcast, $ipaddr, $netmask, $network, $auto, "yes");
				}
				else
				{
					echo 'invalid args';
					die();
				}
				$filename = "../tmp_file/it_config-".$ip."-".$interface;
				set_conf_res($array_key, $array_value, $filename);
				
				try
				{
					$esc_ip = mysqli_real_escape_string($db, $ip);
					$esc_new_ip = mysqli_real_escape_string($db, $ipaddr);
					if ($type === "static" && !empty($esc_new_ip)) {
						mysqli_query($db, "UPDATE servers SET ip='$esc_new_ip' WHERE ip='$esc_ip'");
					}

					$ssh = new MySSH($ip, "ihmuser", "1HM_c0ntr0l3ur");
					$ssh->connect();
					$ssh->send_file("/ihm_test_suse/tmp_file/it_config-".$ip."-".$interface, "/etc/sysconfig/network/ifcfg-".$interface, null);
					
					$cmd = "echo '1HM_c0ntr0l3ur' | sudo -S wicked ifdown ".$interface." ; echo '1HM_c0ntr0l3ur' | sudo -S wicked ifup ".$interface;
					$ssh->ssh_exec($cmd);

					echo "ok";
				}
				catch (Exception $e) {
					echo "fail";
					die();
				}				
			}
			else
			{
				echo "invalid args";
			}
?>