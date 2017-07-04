<?php

function set_conf_res($array_del_key, $array_add_key, $array_value, $interface)
{
    $filename = '/etc/sysconfig/network/ifcfg-'.$interface;
    $fd = fopen($filename, 'rw+');
    $content = fread($fd, filesize($filename));

	print($content);
    $lines = explode("\n", $content);
	print_r($lines);
    $pos_line = 0;

    ftruncate($fd, 0);

    $pos_value = 0;
    foreach ($array_add_key as $key_add)
    {
	$pass = 0;
	$pos_line = 0;
	foreach ($lines as $line)
	{
	    if (strncmp($key_add, $line, strlen($key_add)) === 0)
	    {
	        $lines[$pos_line] = $key_add."='".$array_value[$pos_value]."'";
			$pass = 1;
			break;
	    }
	    $pos_line += 1;
	}
	if ($pass === 0)
	{
	    array_push($lines, $key_add."=".$array_value[$pos_value]);
	}
	$pos_value += 1;
    }

    foreach ($array_del_key as $key_del)
    {
	$pos_line = 0;
	foreach ($lines as $line)
	{
	    if (strncmp($key_del, $line, strlen($key_del)) === 0)
	    {
	        $lines[$pos_line] = "";
	    }

	}

    }

    foreach($lines as $new_line)
    {
        file_put_contents($filename, $new_line."\n", FILE_APPEND | LOCK_EX);
    }
    fclose($fd);
}

function get_conf_res($interface)
{
    $filename = '/etc/sysconfig/network/ifcfg-'.$interface;
    $fd = fopen($filename, 'rw+');
    $content = fread($fd, filesize($filename));

	$array_ret = array(array(), array());

    $lines = explode("\n", $content);

	foreach ($lines as $line)
	{
		if (strlen($line) > 1 && $line[0] != '#')
		{
			$values = explode("=", $line);
			if (count($values) >= 2)
			{
				array_push($array_ret[0], $values[0]);
				array_push($array_ret[1], str_replace("'", "", $values[1]));
			}
		}
	}
	
	return ($array_ret);
}

function parse_network()
{
   $array = get_conf_res('eth0');
   print_r($array);
}

parse_network();
?>