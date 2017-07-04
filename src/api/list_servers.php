<?php
include ("config.php");

	$sql = "SELECT * FROM servers";
	$result=mysqli_query($db,$sql);
	echo "<table border='1'>
	<tr>
	<th>Id</th>
	<th>Name</th>
	<th>Service</th>
	<th>Ip</th>
	</tr>";
	
	while($row = mysqli_fetch_array($result))
	{
	echo "<tr>";
	echo "<td>" . $row['index'] . "</td>";
	echo "<td>" . $row['name'] . "</td>";
	echo "<td>" . $row['service'] . "</td>";
	echo "<td>" . $row['ip'] . "</td>";
	echo "</tr>";
	}
	echo "</table>";
	
	mysqli_close($con);
?>