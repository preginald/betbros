<?php
include 'core/init.php';
protect_page(); 
include 'includes/overall/header.php';
if(isset($_GET['page'])) {

	$pages=array(
		"admin",
        "bet-tracker",
        "post-analysis",
        "sports",
        "selection-board",
        "profile",
        "racecards",
        "racecards-detail",
        "events",
        "fixtures",
        "teams",
        "players",
        "incident",
        "lineup",
        "shot",
        "tips",
        "horses",
        "jockeys",
        "horse-trainers",
        "racecourses",
        "release-notes",
        "selection-simulator",
        "simulator"
        );

	if(in_array($_GET['page'], $pages)) {

		$_page=$_GET['page'];

    } else {

        $_page="comingsoon";

    }
    
} else {

    $_page="comingsoon";

}

?>

<div class="col-md-10"><?php require($_page.".php"); ?></div>

<?php include 'includes/overall/footer.php' ;