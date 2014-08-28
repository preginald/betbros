<?php
include 'core/init.php';

$term = sanitize($_GET["term"]);

$s = "SELECT * FROM teams where sportID = 2 AND name like '%".$term."%' order by name ";

$query=mysql_query($s);

$json=array();


while($teams=mysql_fetch_array($query)){

	$json[]=array(

		'value' => $teams["ID"],
		'label' => $teams["name"]

		);
}

echo json_encode($json);