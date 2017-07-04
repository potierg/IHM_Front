<?php

include ("config.php");

if($_SERVER['REQUEST_METHOD'] == 'GET') {

	$query = "SELECT * FROM servers";
	$result = mysqli_query($db, $query);

	$tab = array();	
	while ($row = mysqli_fetch_array($result)) {
		$array = array();
		$array['hostname'] = $row['name'];
		$array['service'] = $row['service'];
		$array['ip'] = $row['ip'];
		$array['id_bdd'] = $row['index'];
		$tab[] = $array;
	}

	echo json_encode($tab);

} else {
	exit("Bad method request");
}
?>