<?php

function add_dhcp($array_key, $array_value, $head)
{	
	$filename = "/etc/dhcp/dhcpd.conf";
	file_put_contents($filename, "\n".$head." {\n", FILE_APPEND | LOCK_EX);
	for ($i = 0; $i < count($array_key); $i++)
	{
		file_put_contents($filename, "\t".$array_key[$i]." ".$array_value[$i].";\n", FILE_APPEND | LOCK_EX);
	}
	file_put_contents($filename, "}\n", FILE_APPEND | LOCK_EX);
}

function get_block_of_file($content)
{
	$lines = explode("\n", $content);
	$block = array();

	$nb_block = 0;
	$start = 0;
	foreach ($lines as $line)
	{
		if (strncmp("subnet", $line, 6) === 0 || strncmp("lease", $line, 5) === 0)
		{
			array_push($block, array());
			$start = 1;
		}
		if (strncmp('}', $line, 1) === 0 && $start === 1)
		{
			$start = 0;
			array_push($block[$nb_block], $line);
			$nb_block++;
		}
		else if ($start === 1)
		{
			array_push($block[$nb_block], $line);
		}
	}
	
	return ($block);
}

function update_dhcp($res_ip, $res_mask, $array_key, $array_value, $check)
{
	$filename = "/etc/dhcp/dhcpd.conf";
    $fd = fopen($filename, 'rw+');
    $content = fread($fd, filesize($filename));
	
	$blocks = get_block_of_file($content);
	
	ftruncate($fd, 0);
	fclose($fd);
	foreach ($blocks as $block)
	{
		if (strncmp($check, $block[0], strlen($check)) === 0)
		{
			for ($i = 0; $i < count($array_key); $i++)
			{
				for ($j = 1; $j < count($block) - 1; $j++)
				{
					if (strncmp("\t".$array_key[$i], $block[$j], strlen($array_key[$i])) === 0)
					{
						$block[$j] = "\t".$array_key[$i]." ".$array_value[$i].";";
					}
				}
			}
		}
		
		foreach ($block as $line)
		{
			file_put_contents($filename, $line."\n", FILE_APPEND | LOCK_EX);
		}
		file_put_contents($filename, "\n", FILE_APPEND | LOCK_EX);
	}
}

function delete_dhcp($check)
{
	$filename = '/etc/dhcp/dhcpd.conf';
    $fd = fopen($filename, 'rw+');
    $content = fread($fd, filesize($filename));
	
	$blocks = get_block_of_file($content);
	
	ftruncate($fd, 0);
	fclose($fd);
	foreach ($blocks as $block)
	{
		if (strncmp($check, $block[0], strlen($check)) != 0)
		{
			foreach ($block as $line)
			{
				file_put_contents($filename, $line."\n", FILE_APPEND | LOCK_EX);
			}
			file_put_contents($filename, "\n", FILE_APPEND | LOCK_EX);
		}
	}
}

function get_subnet_lease_dhcp($filename, $array_key)
{
    $fd = fopen($filename, 'rw+');
    $content = fread($fd, filesize($filename));
	
	$blocks = get_block_of_file($content);
	$ret = array();
	
	$id = 0;
	foreach ($blocks as $block)
	{
		array_push($ret, array($block[0], array()));
		for ($i = 1; $i < count($block); $i++)
		{
			if (strncmp('}', $block[$i], 1) != 0)
			{
				foreach ($array_key as $key)
				{
					if (strncmp("\t".$key, $block[$i], strlen($key) + 1) === 0)
					{
						array_push($ret[$id][1], array($key, explode(" ", substr($block[$i], strlen($key) + 2))));
					}
				}
			}
		}
		$id++;
	}
	print_r($ret);
	return ($ret);
}

function set_dhcp_interface($interface)
{
	$filename = '/etc/sysconfig/dhcpd';
    $fd = fopen($filename, 'rw+');
    $content = fread($fd, filesize($filename));
	
	$lines = explode("\n", $content);
	
	ftruncate($fd, 0);
	fclose($fd);
	foreach ($lines as $line)
	{
		if (strncmp("DHCPD_INTERFACE=", $line, 16) === 0)
		{
			file_put_contents($filename, "DHCPD_INTERFACE=\"".$interface."\"\n", FILE_APPEND | LOCK_EX);
		}
		else
		{
			file_put_contents($filename, $line."\n", FILE_APPEND | LOCK_EX);
		}
	}
}

?>