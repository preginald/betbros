<?php
  include "core/database/connect.php";
 
 $event=$_POST["event"];
  $result=mysql_query("select ID,name FROM teams where eventID='$event' ");
  while($team=mysql_fetch_array($result)){
    echo"<option value=$team[ID]>$team[name]</option>";
 
  }
?>