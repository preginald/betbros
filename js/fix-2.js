function load_fix(){
  var load=0;
	$.ajax({
    url: "fix-2-sort-time.php",
    data: {
      load: load
    },
    success: function(data){
      $("#fix_div").html(data);
    }
  });
}

function clear_add_fix_form(){
  //$('#date').val('');
  //$('#time').val('');
  $('#ht').val('');
  $('#at').val('');
  $('#htID').val('');
  $('#atID').val('');
  //$('#esID').val('');
}

function addfixform(){
	$.get ( "fix-2-add-form.php" , function(data){
      $("#addfixform").html(data);
    });
}



// my autocomplete start
$("body").on('keyup','#ht',function(){
  var val = $.trim($(this).val());
  var placeHolder = $('#htDropdown');
  placeHolder.html('');

  if (val) {
    $.ajax({
        url: "autocomplete.php",
        //dataType: "json",
        data: {
          option: "team",
          tname: val,
          side: 'ht'
        },
        success: function(data){

          $("#htDropdown").html(data);

        }
      });
  };
});

// fixture search
$('body').on('keyup','#fix-search',function(){
    var val = $.trim($(this).val());
    var date = $.trim($('#fix-search-date').val());

    //console.log(val);

    if (val) {
      $.ajax({
          url: "fix-2-sort-time.php",
          //dataType: "json",
          data: {
            option: "filter",
            val: val,
            date: date
          },
          success: function(data){

            $("#fix_div").html(data); 

          }
        });
    };
});

$('body').on('change','#fix-search-date',function(){
  var date = $.trim($(this).val());
  var val = $.trim($('#fix-search').val());

    if (date) {
      $.ajax({
          url: "fix-2-sort-time.php",
          //dataType: "json",
          data: {
            option: "filter",
            val: val,
            date: date
          },
          success: function(data){

            $("#fix_div").html(data); 

          }
        });
    };  

});

// insert autocomplete dropdown values 
$('body').on('click','.list-group-item.ht',function(){
  event.preventDefault();
  var $that = $(this);
  var ID = $that.attr('data-ID');
  var name = $that.find('h4.list-group-item-heading').text();

  $('#ht').val(name);
  $('#htID').val(ID);
  $(".list-group-item.ht").remove();

});

$("body").on('keyup','#at',function(){
  var val = $.trim($(this).val());
  var placeHolder = $('#atDropdown');
  placeHolder.html('');

  if (val) {
    $.ajax({
        url: "autocomplete.php",
        //dataType: "json",
        data: {
          option: "team",
          tname: val,
          side: 'at'
        },
        success: function(data){

          $("#atDropdown").html(data);

        }
      });
  };
});

// insert autocomplete dropdown values 
$('body').on('click','.list-group-item.at',function(){
  event.preventDefault();
  var $that = $(this);
  var ID = $that.attr('data-ID');
  var name = $that.find('h4.list-group-item-heading').text();

  $('#at').val(name);
  $('#atID').val(ID);
  $(".list-group-item.at").remove();

});
// my autocomplete end


$('#fix_div').on('click','.fxdelbtn',function(){
  var $that = $(this);
  var fixID = $that.data('fixid');
  
  $.ajax({
    url: "fix-2_delete.php",
    data: { fixID: fixID},
    success: function(data){
      data = $.trim(data);
      if (data == "SUCCESS") {

        $that.closest('.fix-item').slideUp(250,function(){ $(this).remove() });

      };
    }
  });
});

$('body').on('click','.btn-addfix',function(){

  var date = $('#date').val(),
      time = $('#time').val(),
      ht = $('#ht').val(),
      at = $('#at').val(),
      htID = $('#htID').val(),
      atID = $('#atID').val(),
      esID = $('#esID').val();

  $.post('add_fixture_js.php',
  {
    date: date,
    time: time,
    ht: ht,
    at: at,
    htID: htID,
    atID: atID,
    esID: esID
  },function(data){
    if (data=="SUCCESS") {
      load_fix();
      clear_add_fix_form();
      var status = $('#status');
      status.removeClass('alert-danger');
      status.addClass('alert-success');
      status.html(ht + " vs " + at + " added."); 
    } else if (data=="EXISTS"){
      var status = $('#status');
      status.removeClass('alert-success');
      status.addClass('alert-danger');
      status.html("ERROR: The fixture you're adding already exists in database");
    };
  });
});

$('#fix_div').on('click','.mselect',function(){
  var that = $(this);
  var mID = that.attr('mid');
  var fID = that.attr('fid');     
  var getURL = "fix-2-mk-sel.php?mID=" + mID + "&fixtureID=" +fID;

    $.get( getURL, function( data ) {
      var mIDfID = "" + mID + fID;
      
      $( "#mID_"+mIDfID ).html( data );
    
    });
});

$('#fix_div').on('click','.fxselbtn',function(){
  $that = $(this);
  var fID = $that.closest('li.fix-item').attr('id');

  $.ajax({
    url: "fixsel.php",
    data: {
      fID: fID
    },
    success: function(data){
      $( "#sel_"+fID ).html( data );
    }
  });


});


$('#fix_div').on('click','.fxmkbtn',function(){
  var fixtureID = $(this).closest('li.fix-item').attr('id');
  //alert(fixtureID);
  var getURL = "fixture_markets.php?fixtureID="+fixtureID;
  $.get( getURL, function( data ) {
    $( "#markets_"+fixtureID ).html( data );
        
  });
});

$('#fix_div').on('click','.fxeditbtn',function(){
  $that = $(this);
  var fixID = $that.closest('li.fix-item').attr('id');
  var esID  = $that.data('esid');
  var htID  = $that.data('htid');
  var atID  = $that.data('atid');

  $.ajax({

    url:  "fix-edit-score.php",
    data: {
      fixID: fixID,
      esID: esID,
      htID: htID,
      atID: atID
    },
    success: function(data){
      $('#fixedit_'+fixID).html(data);
    }

  });

});

$('#fix_div').on("click",".odds-modal",function(){
  var that = $(this);

  var selName = that.attr('selname');
  var eID = that.attr('eid');
  var mID = that.attr('mid');
  var selID = that.attr('selid');
  var odds = that.attr('odds');
  var ubkID = that.attr('ubkid');
  var bk = that.attr('b');

  //console.log(selID);

  $(".modal-header .modal-title").html( selName );
  $(".modal-body #odds").val( odds );
  $(".modal-body #bookieID").val( ubkID );
  $(".modal-body #eventID").val( eID );
  $(".modal-body #mID").val( mID );
  $(".modal-body #selID").val( selID );
});

// start score functions
$('body').on('change','.htscoreht',function(){
  var $that = $(this); 
  fixID = $that.closest('.fix-item').attr('id');

  var htscoreht = $(this).val();
  $('#htscore_'+fixID).val(htscoreht); 
});

$('body').on('change','.atscoreht',function(){
  var $that = $(this); 
  fixID = $that.closest('.fix-item').attr('id');

  var atscoreht = $(this).val();
  $('#atscore_'+fixID).val(atscoreht);  
});

$('body').on('change','.htscoreht',function(){
  var $that = $(this); 
  fixID = $that.closest('.fix-item').attr('id');

  var htscore = $('#htscore_'+fixID).val();
  var atscore = $('#atscore_'+fixID).val();

  $('#score_'+fixID).html(htscore + " - " + atscore); 
});

$('body').on('change','.atscoreht',function(){
  var $that = $(this); 
  fixID = $that.closest('.fix-item').attr('id');

  var htscore = $('#htscore_'+fixID).val();
  var atscore = $('#atscore_'+fixID).val();

  $('#score_'+fixID).html(htscore + " - " + atscore); 
});

$('body').on('change','.htscore',function(){
  var $that = $(this); 
  fixID = $that.closest('.fix-item').attr('id');

  var htscore = $('#htscore_'+fixID).val();
  var atscore = $('#atscore_'+fixID).val();

  $('#score_'+fixID).html(htscore + " - " + atscore); 
});

$('body').on('change','.atscore',function(){
  var $that = $(this); 
  fixID = $that.closest('.fix-item').attr('id');

  var htscore = $('#htscore_'+fixID).val();
  var atscore = $('#atscore_'+fixID).val();

  $('#score_'+fixID).html(htscore + " - " + atscore); 
});
// end score functions

$('body').on('click','.btn-addscore',function(){
  var $that = $(this); 
  var fixID = $that.closest('.fix-item').attr('id');
  var htscoreht = $('#htscoreht_'+fixID).val();
  var atscoreht = $('#atscoreht_'+fixID).val();
  var htscore = $('#htscore_'+fixID).val();
  var atscore = $('#atscore_'+fixID).val();
  var htID = $('#htID_'+fixID).val();
  var atID = $('#atID_'+fixID).val();
  var esID = $('#esID_'+fixID).val();

  $.ajax ({
    url: "fix-edit-score-proc.php",
    data: {
      fixID: fixID,
      esID: esID,
      htID: htID,
      atID: atID,
      htscoreht: htscoreht,
      atscoreht: atscoreht,
      htscore: htscore,
      atscore: atscore
    },
    success: function(data){
      $('#score_'+fixID).html(data);
      $('#fixedit_'+fixID).collapse('hide');
    }
  });

});

// market selection modal window scripts
$('body').on('click','.btn-odds',function(){
  var id = $(this).attr('odds-id');

  var option = "add_odds";

  var odds = $('#odds').val();
  var bookieID = $('#bookieID').val();
  var lID = $('#lID').val();

  var eventID = $('#eventID').val();
  var mID = $('#mID').val();

  var selID = $('#selID').val();

  var sID = 2;

  $.ajax({
    type: 'POST',
    url: 'fix-2-form-proc.php',
    dataType: 'json',
    data: {
      option: option,
      odds:odds,
      bookieID: bookieID,
      lID: lID,
      eventID: eventID,
      mID: mID,
      selID: selID,
      sID: sID
    },
    success: function(data){
      console.log(data);
      var oddsStatus = data.oddsStatus;
      if (oddsStatus=="SUCCESS") {
        $('#odds-modal').modal('hide');
      };
    }

  });  
});

$('body').on('click','.btn-select',function(){
  var option = "add_sel";

  var odds = $('#odds').val();
  var bookieID = $('#bookieID').val();
  var lID = $('#lID').val();

  var eventID = $('#eventID').val();
  var mID = $('#mID').val();
  var selID = $('#selID').val();

  var sID = 2;


  $.post('fix-2-form-proc.php',
  {
    option: option,
    odds:odds,
    bookieID: bookieID,
    lID: lID,
    eventID: eventID,
    mID: mID,
    selID: selID,
    sID: sID
  },function(data){
    console.log(data);
    if (data=="SUCCESS") {
      $('#odds-modal').modal('hide'); 
    };
  });
});

$('body').on('click','.btn-bet',function(){
  var option = "add_bet";

  var odds = $('#odds').val();
  var bookieID = $('#bookieID').val();
  var lID = $('#lID').val();

  var eventID = $('#eventID').val();
  var mID = $('#mID').val();
  var selID = $('#selID').val();

  var sID = 2;

  var stake = $('#stake').val();


  $.ajax({
    type: 'POST',
    url: 'fix-2-form-proc.php',
    dataType: 'json',
    data: {
      option:   option,
      odds:     odds,
      bookieID: bookieID,
      lID:      lID,
      eventID:  eventID,
      mID:      mID,
      selID:    selID,
      sID:      sID,
      stake :   stake
    },
    success: function(data){
      //console.log(data);
      var oddsStatus = data.oddsStatus;
      var sbID = data.sbID;
      //console.log(oddsStatus);
      if (oddsStatus =="SUCCESS") {
        $('#odds-modal').modal('hide');
        
        $.post('abc.php',{

          'bookieID[]': bookieID,
          'sbID02[]'  : sbID,
          'odds[]'  : odds,
          'stake[]' : stake,
          'labelID[]' : lID,
          'fixID[]' : eventID,
          'selID[]' : selID,
          'mID[]'   : mID,
          'add-bet' : '',
          'bet-type'  : 'Single'

        },function(data){
          console.log(data);
        }); 


      };
    }    
  });

});

$('body').on('change keyup','#eID, #date',function(){
  eID = $('#eID').val();
  date = $('#date').val();

  if (eID != "" && date != "") {

    $.ajax({
      type: 'POST',
      url: 'fix-2-form-proc.php',
      dataType: 'json',
      data: {
        option: "eventCheck",
        eID: eID,
        date: date
      },
      success: function(data){
        console.log(data);
        if (data.status == "ERROR") {
          
          var source   = $("#addFixtureError").html();
          var template = Handlebars.compile(source);
          var context = data;
          var html    = template(context);    

          $('#status').html(html);

        } else if (data.status == "OK") {
          $('#esID').val(data.esID);
          $('#status').html('');

        };
      }

    });

  };


});