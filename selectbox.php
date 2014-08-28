<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';
?>   
      <script type="text/javascript">
           $(document).ready(function(){
 
               
               $("#event").change(function(){
                     var event=$("#event").val();
                     $.ajax({
                        type:"post",
                        url:"getteam.php",
                        data:"event="+event,
                        success:function(data){
                              $("#team").html(data);
                        }
                     });
               });
           });
      </script>

        <select name="event" id="event">
        <option>-select your event-</option>
        <?php 
        $result=mysql_query("SELECT ID,name from events order by name");
        while($event=mysql_fetch_array($result)){
         
        echo "<option value=$event[ID]>$event[name]</option>";
 
        } ?>
        </select>
         
 
        Team :
        <select name="team" id="team">
            <option>-select your team-</option>
        </select>
