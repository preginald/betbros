<?php protect_page(); ?>

<div class="row" id="fix_menu">
  <div class="col-lg-6">
    <div class="input-group">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default" id="addfixbtn"><i class="fa fa-plus-circle fa-lg"></i></button>
      </div>
      <input type="text" class="form-control" id="fix-search" placeholder="Super search" >
    </div><!-- /.input-group -->
  </div>
  <div class="col-lg-3">
       <input type="date" class="form-control" id="fix-search-date" >
  </div>
</div>

<div class="alert" id="status" role="alert"></div>

<div class="row" id="addfixform">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="row">
        <div class="form-group col-md-5">
          <label for="eID">League</label>
          <select class="form-control" id="eID" required> <?php event_dropDown($eID, 2) ?></select>
        </div>
        
        <div class="form-group col-md-3">
          <label for="date">Kick Off Date</label>
          <input type="date" class="form-control" id="date" >
        </div>
      
        <div class="form-group col-md-2">
          <label for="time">Kick Off Time</label>
          <input type="time" class="form-control" id="time" >
        </div>
      </div>

      <div class="row">  
        <div class="form-group col-md-5">
          <input type="text" class="form-control" id="ht" placeholder="Home Team">
          <div class="list-group" id="htDropdown"></div>
        </div>
          
        <div class="form-group col-md-5">
          <input type="text" class="form-control" id="at" placeholder="Away Team">
          <div class="list-group" id="atDropdown"></div>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-12">
        <button type="button" class="btn btn-default btn-addfix">Add</button>
        </div>  

          <input id="htID" type="hidden" name="htID">
          <input id="atID" type="hidden" name="atID">
          <input id="esID" type="hidden" name="esID">
        
      </div>
    </div>
  </div> 
</div>


<div class="row">
  <ul id="fix_div"></ul>
</div>


<script id="dropdownTemplate" type="text/x-handlebars-template">
  {{#each this}}
  <a href="#" class="list-group-item" data-ID="{{ID}}">
    <h4 class="list-group-item-heading">{{name}}</h4>
    <p class="list-group-item-text"><span class="label label-default">{{r}}</span></p>
  </a>
  {{/each}}
</script>

<script id="addFixtureError" type="text/x-handlebars-template">
  <div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>Warning!</strong>  The Kick Off date you entered does not match any Events in the database. 
  </div>
</script>

<script>
  $(function(){
    var load=0;

      $(window).scroll(function(){
        if($(window).scrollTop()>= $(document).height() - $(window).height() - 333){
          load++;
          var val = $.trim($('#fix-search').val()),
              date = $.trim($('#fix-search-date').val());

          console.log(val);
          $.ajax({
            url: "fix-2-sort-time.php",
            data: {
              load: load,
              option: "filter",
              val: val,
              date: date
            },
            success: function(data){
              $("#fix_div").append(data);
            }
          });
        }
      });

    load_fix();
    
    $('#addfixform').hide();



    $('#addfixbtn').on('click',function(){

      $('#addfixform').toggle();
    
    });


    $('.btn-odds').click(function(){
      var fixtureID = $(this).attr('data-target');
      //alert(fixtureID);
      $('#odds-'+fixtureID).slideToggle(100);
    });

  });



</script>

<script type="text/javascript" src="js/fix-2.js"></script>