<?php	if(isset($_GET['page'])) {

		$pages=array("bet-tracker", "sports", "contact", "selection-board");

		if(in_array($_GET['page'], $pages)) {

			$_page=$GET['page'];
			echo $_page;

		} else{

			$_page="index";

		}

	} else {

		$_page="index";

	}

?>