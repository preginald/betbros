<?php require_once 'core/init.php'; ?>
<?php
$labelID = sanitize($_GET['labelID']);
$btID = sanitize($_GET['btID']);

echo update_bt_labelID($labelID,$btID);