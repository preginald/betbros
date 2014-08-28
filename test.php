<?php
include 'core/init.php';
protect_page(); 
include 'includes/overall/header.php';


$bt_where = "bt.userID = $session_user_id";
$orderby = "bt.ID ASC";
$result_bt = bt_table($bt_where,$orderby);
$counter = $index = $w = $l = 0;
$mem = "";
$btIDwin = array();
$btIDlose = array();
$arrayName2 = array();
$win = array();
$lose = array();
while($row = mysql_fetch_assoc($result_bt)){
	$btID= $row['btID'];
	$bstID = $row['bstID'];
	$mem = ($counter == 0) ? $bstID : $mem;
	$name = $row['bstname'];

	if ($bstID == $mem) {
		switch ($bstID) {
			case 1:
				$w++;
				$btIDwin[] = $btID;
				break;

			case 2:
				$l++;
				$btIDlose[] = $btID;
				break;		
		}

	} else {
		switch ($bstID) {
			case 1:
				$w = 1;
				$btIDwin[] = $btID;
				$arrayName2[$index]["bstID"] = "L";
				$arrayName2[$index]["Count"] = $l;
				$arrayName2[$index]["btID"] = $btIDlose;
				$btIDlose = array();
				$l = 0;
				break;

			case 2:
				$l = 1;
				$btIDlose[] = $btID;
				$arrayName2[$index]["bstID"] = "W";
				$arrayName2[$index]["Count"] = $w;
				$arrayName2[$index]["btID"] = $btIDwin;
				$btIDwin = array();
				$w = 0;
				break;		

			case 3:
				//$l = 1;
				//$w = 0;
				break;	

		}

		$index++;
	}

	$mem = ($bstID != 3) ? $bstID : $mem;
	$counter++;

}

switch ($bstID) {
	case 1:
		$btIDlose[] = $btID;
		$arrayName2[$index]["Count"] = $w;
		$arrayName2[$index]["bstID"] = "W";
		$arrayName2[$index]["btID"] = $btIDwin;
		break;		
		
	case 2:
		$btIDwin[] = $btID;
		$arrayName2[$index]["Count"] = $l;
		$arrayName2[$index]["bstID"] = "L";
		$arrayName2[$index]["btID"] = $btIDlose;
		break;

	case 3:
		//$l = 1;
		//$w = 0;
		break;	

}


//print_r_pre($arrayName2);

$win = array();
$lose = array();

foreach ($arrayName2 as $key => $value) {

	switch ($value["bstID"]) {
		case 'W':
			if ($value["Count"] > 1) {
				$win[] = $value;
			}
			break;

		case 'L':
			if ($value["Count"] > 1) {
				$lose[] = $value;
			}
			break;
		
		default:
			# code...
			break;
	}
}

// print_r_pre($win);
// print_r_pre($lose);

$results["Win"] = $win;
$results["Lose"] = $lose;

echo json_encode($results);

$ws2 = array();
$ws3 = array();
$ws4 = array();
$ws5 = array();
$ws6 = array();
$ws7 = array();
$ws8 = array();
$ws9 = array();
$ws10 = array();

foreach ($win as $key => $value) {

	switch ($value["Count"]) {
		case 2:
			$ws2[] = $value;
			break;
		case 3:
			$ws3[] = $value;
			break;
		case 4:
			$ws4[] = $value;
			break;
		case 5:
			$ws5[] = $value;
			break;
		case 6:
			$ws6[] = $value;
			break;
		case 7:
			$ws7[] = $value;
			break;
		case 8:
			$ws8[] = $value;
			break;
		case 9:
			$ws9[] = $value;
			break;
		case 10:
			$ws10[] = $value;
			break;
		
		default:
			# code...
			break;
	}

}

// echo "2 x win streak: ", count($ws2), "<br/>";
// echo "3 x win streak: ", count($ws3), "<br/>";
// echo "4 x win streak: ", count($ws4), "<br/>";
// echo "5 x win streak: ", count($ws5), "<br/>";
// echo "6 x win streak: ", count($ws6), "<br/>";
// echo "7 x win streak: ", count($ws7), "<br/>";
// echo "8 x win streak: ", count($ws8), "<br/>";
// echo "9 x win streak: ", count($ws9), "<br/>";
// echo "10 x win streak: ", count($ws10), "<br/>";

?>

<script>
	$(function(){

		$('#hideshow_3').on('click', function(){
			$('#show_3').slideToggle(100);
		});

	});

</script>

<?php include 'includes/overall/footer.php'; ?>