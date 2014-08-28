<?php

include 'core/init.php';

protect_page();

admin_protect();

include 'includes/overall/header.php';

?>



<h1>Admin</h1>

<?php
include 'includes/admin-menu.php';

if(isset($_GET['page'])) {



        $pages=array(
        	"horses",
        	"horse-trainers",
        	"jockeys",
        	"person",
        	"bookies",
        	"owners",
        	"teams"

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

<?php require($_page.".php"); ?>

<?php include 'includes/overall/footer.php'; ?>