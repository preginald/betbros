<?php 
$menuClass = '';
$sql = "SELECT * FROM topMenu WHERE parent='0' AND enable='1' ORDER BY `order`"; $result = mysql_query($sql); 
?>
    
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">BetBros</a>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            <?php 
            while($row = mysql_fetch_array($result)) { ;
            if (isset($_GET['page'])) {
              $menuClass = ($_GET['page'] == $row['file']) ? 'class="active"' : '';
            }
             
            ?>
            <li <?= $menuClass ?>><a href="<?= $row['link']; ?>"><?= $row['1']; ?></a></li>
            <?php }; ?>

            </ul>

          <ul class="nav navbar-nav navbar-right">
            <?php include "loginstatus.php"; ?>
          </ul>
        </div><!--/.nav-collapse -->

      </div>
    </nav>