<?php require_once 'core/init.php'; ?>
<?php
$labelID = sanitize($_GET['labelID']);
$sbID = sanitize($_GET['sbID']);

echo update_sb_labelID($labelID,$sbID);