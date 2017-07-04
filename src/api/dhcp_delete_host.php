<?php
	session_start();
	include '../script_conf/connexion_ssh.php';
	include '../script_conf/script_conf_dhcp.php';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["ip"]) && !empty($_POST["net_ip"]) && !empty($_POST["mask"]) && !empty($_POST["hostname"]))
	{
		$ip = $_POST["ip"];
		$net_ip = $_POST["net_ip"];
		$netmask = $_POST["mask"];
		$hostname = $_POST["hostname"];
		
		delete_host_dhcp($net_ip, $netmask, $hostname, "../tmp_file/service_dhcp".$ip);
		try {
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
		echo ("invalid args");
	}
?>