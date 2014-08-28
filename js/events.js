$('#event').hide();
$('#eventSeason').hide();




$('body').on('change','#cID',function(){
	$that = $(this);
	cID = $that.val();

	$('#eventSeason').hide();
	$('#view_e').html('');

	//if (cID == "") {
		$('#view_es').html('');
		$('#view_ts').html('');
		$('#fix_div').html('');
		$('#event').val('');
		$('#eventSeason').val('');
	//};
	
	load_teams_list('cID',cID);
	ajaxEventDropdown(cID);
});

function ajaxEventDropdown(cID){
	$.ajax({
		url: "events-proc.php",
		data: { 
			option: "event",
			cID: 	cID 
		},
		success: function(data){
			$('#event').show();
			$('#event').html(data);		
		}
	});
}

$('body').on('change','#event',function(){
	$that = $(this);
	eID = $that.val();

	if (eID == "") {
		$('#view_e').html('');
		$('#view_es').html('');
		$('#view_ts').html('');
		$('#fix_div').html('');
	};

	if (eID == "new") {
		$('#eventSeason').hide();
		load_add_event(cID);
	} else {
		$('#view_e').html('');
		ajaxEventSeasonDropdown(eID);
	}
});

function ajaxEventSeasonDropdown(eID){
	$.ajax({
		url: "events-proc.php",
		data: { 
			option: "eventSeason",
			eID: 	eID 
		},
		success: function(data){
			load_event(eID);
			$('#eventSeason').show();
			$('#eventSeason').html(data);
		}
	});	
}

// add event functions
$('body').on('keyup','#eventName',function(){
	var placeHolder = $('#view_e_list');
	placeHolder.html('');
	var e = $(this).val();
	var c = $('#cID').val();
	
	$.ajax({
		url: "events-proc.php",
		dataType: "json",
		data: {
			option: "eventNameLookup",
			cID: cID,
			e: e
		},
		success: function(data){
			console.log(data);
			var source   = $("#e-list-template").html();
			var template = Handlebars.compile(source);
			var context = data;
			var html    = template(context);		

   			$.each(data,function(index,element){
   				// Generate the HTML for each post
          		var html = template(element);
          		// Render the posts into the page
          		placeHolder.append(html);
        	});

		}
	});
});

$('body').on('click','#addevent',function(){
	var placeHolder = $('#view_e');
	var e = $('#eventName').val();
	var c = $('#cID').val();
	$.ajax({
		url: "events-proc.php",
		dataType: "json",
		data: {
			option: "addEventSQL",
			e: e,
			cID: c
		},
		success: function(data){
			console.log(data);
			if(data.status == "OK"){
				placeHolder.html('');

				var source   = $("#addEventSQLStatus").html();
				var template = Handlebars.compile(source);
				var context = data;
				var html    = template(context);		

	         	placeHolder.append(html);
	         	ajaxEventDropdown(cID);
			}
		}
	});
});

$('body').on('click','#addeventSeason',function(){
	var placeHolder = $('#view_e');
	var e = $('#event  option:selected').text();
	var eID = $('#event').val();
	var eventStartDate = $('#eventStartDate').val();
	var eventEndDate = $('#eventEndDate').val();
	if ( eventStartDate > eventEndDate ) {
		var source   = $("#addEventSeasonFormError").html();
     	placeHolder.append(source);		
	} else {
		$.ajax({
			url: "events-proc.php",
			dataType: "json",
			data: {
				option: "addEventSeasonSQL",
				eventStartDate: eventStartDate,
				eventEndDate: eventEndDate,
				eID: eID,
				e: e
			},
			success: function(data){
				console.log(data);
				if(data.status == "OK"){
					placeHolder.html('');

					var source   = $("#addEventSeasonSQLStatus").html();
					var template = Handlebars.compile(source);
					var context = data;
					var html    = template(context);		

		         	placeHolder.append(html);
		         	ajaxEventSeasonDropdown(eID);
				}
			}
		});		
	}

});

$('body').on('click','.delete-copy',function(e){
	$that = $(this);
	var li = $that.closest('li.list-group-item');
	var tname = li.find('.tname').html();
	var tID = li.attr('id');

	//console.log(tname);

	$.ajax({
		url: "events-proc.php",
		//dataType: "json",
		data: {
			option: "deleteCopyForm",
			tID: tID,
			tname: tname
		},
		success: function(data){
			//console.log(data);		

			li.html(data);
		}
	});

	$('body').on('click','.dup-search',function(e){
		
		$.ajax({
			url: "events-proc.php",
			data: {
				option: "deleteCopySearch",
				tID: tID,
				tname: tname
			},
			success: function(data){
				li.html(data);
			}

		});
		e.preventDefault;
	});

	$('body').on('click','#btn-deleteDuplicates',function(){
		var li = $(this).closest('li');
		var dupID = li.find('span.dupID').html();

		$.ajax({
			url: "teams_del_dup.php",
			dataType: "json",
			data: {
				option: "deleteCopyExec",
				tID: tID,
				dupID: dupID
			},
			success: function(data){
				var status = data.status;
				if (status == "SUCCESS") {
					li.remove();
				}; 
			}

		});

	});

	e.preventDefault();
});



$('body').on('click','.edit-team',function(e){
	var li = $(this).closest('li.list-group-item');
	var tID = li.attr('id');

	console.log(tID);

	$.ajax({
		url: "events-proc.php",
		//dataType: "json",
		data: {
			option: "teamLookup",
			tID: tID
		},
		success: function(data){
			//console.log(data);
			var source   = $("#updateTeam").html();
			var template = Handlebars.compile(source);
			var context = data;
			var html    = template(context);		

			li.html(data);			
		}
	});

	e.preventDefault();
});

$('body').on('click','#btn-updateTeam',function(e){
	var cID = $('#cID').val();

	$.ajax({
		url: "events-proc.php",
		dataType: "json",
		data: $("#teamUpdateForm").serialize(),
		success: function(data){
			console.log(data);
			load_teams_list('cID',cID);

		}

	});

	e.preventDefault();
});

$('body').on('click','#btn-updateTeamCancel',function(e){
	var cID = $('#cID').val();
	load_teams_list('cID',cID);

	e.preventDefault();
});

$('body').on('change','#eventSeason',function(){
	$that = $(this);
	esID = $that.val();


	if (esID == "") {
		$('#view_e').html('');
		$('#view_es').html('');
		$('#view_ts').html('');
		$('#fix_div').html('');

	} else if (esID == "new") {

		load_add_eventSeason(esID);

		$('#view_es').html('');
		$('#view_ts').html('');
		$('#fix_div').html('');

	} else {

		$('#view_e').html('');

		$.ajax({
			url: "events-proc.php",
			dataType: "json",
			data: { 
				option: "view_es",
				esID: 	esID 
			},
			success: function(data){
				data.startDate = format_date(data.startDate);
				data.endDate = format_date(data.endDate);

				var source   = $("#es-view-template").html();
				var template = Handlebars.compile(source);
				var context = data;
				var html    = template(context);		

				$('#view_es').html(html);

				load_teams_list('esID',esID);
				load_fixture(esID);
			}
		});
	}
});

function format_date(date){
	// Split timestamp into [ Y, M, D]
	var d = date.split("-");
	return d[2] +"-"+ d[1] +"-"+ d[0];
}

function load_event(eID){

	$.ajax({
		url: "events-proc.php",
		dataType: "json",
		data: {
			option: "view_e",
			eID: eID
		},
		success: function(data){
			console.log(data);
			$('#view_e').html(data[0]);
		}
	});
}

function load_add_event(cID){
	$.ajax({
		url: "events-proc.php",
		//dataType: "json",
		data: {
			option: "add_e",
			cID: cID
		},
		success: function(data){
			//console.log(data);
			$('#view_e').html(data);
		}
	});	
}

function load_add_eventSeason(esID){
	$.ajax({
		url: "events-proc.php",
		//dataType: "json",
		data: {
			option: "add_es",
			esID: esID
		},
		success: function(data){
			//console.log(data);
			$('#view_e').html(data);
		}
	});	
}


function load_fixture(esID){

	$.ajax({
		url: "fix-2-sort-time.php",
		data: {
			esID: esID
		},
		success: function(data){
			$('#fix_div').html(data);
		}
	});

}

function load_teams_list(option,ID){
	// $.get ( "teams-list.php?esid="+esID , function(data){
 //      $("#view_ts").html(data);
	  
	//   var tempArray = [];
	//   $.each($('ul#teams > li'),function(index,value){
	// 	$(".dedupbtn").remove();
	// 	var ID = ($(this).attr('id')); 
	// 	var t = ($.trim( $(this).html())); 
	// 	tempArray[tempArray.length] = {'ID': ID, 't': t};
		
	// 	});
	// 	console.log(tempArray);
		
 //    });

	$.ajax({
		url: "teams-list.php",
		data: {
			option: option,
			ID: ID
		},
		success: function(data){
			$('#view_ts').html(data);
		}
	});

}


$('body').on('click','#btn-addteamSeason',function(){
	var tID 	= $('#tID').val(),
		cID 	= $('#cID').val(),
		esid 	= $('#esID').val(),
		t 		= $('#teamSeason').val(),
		sname 	= $('#sname').val(),
		lname 	= $('#lname').val();

	$(".list-group-item.teamSeason").remove();
	$('#teamSeason').val('');
	$('#sname').val('');
	$('#lname').val('');

	$.ajax({
		url: "events-proc.php",
		dataType: "json",
		data: {
			option: "addTeamSeasonSQL",
			tID: tID,
			esID: esID,
			t: t,
			sname: sname,
			lname: lname,
			cID: cID
		},
		success: function(data){
			//console.log(data);
			if(data.status == "OK"){
				//load_teams_list(esID);
				//$('#'+data.ID).val('list-group-item-success');

				var source   = $("#newTeamSeasonAdded").html();
				var template = Handlebars.compile(source);
				var context = data;
				var html    = template(context);		

				$('#teams').prepend(html);
				
				
			} else if(data.status == "EXISTS"){
				$('#'+data.tsID).addClass('list-group-item-warning');
			}
		}
	});	
})

$('body').on('click','.list-group-item-warning',function(){
	$(this).removeClass('list-group-item-warning');
});

$('body').on('click','.dedupbtn',function(){
	$that = $(this);
	tsID = $that.data('tsid');
	$.ajax({
		url: "teams_del_dup.php",
		data: {tsID: tsID},
		success: function(data){
			console.log(data);
			var esID = $('#esID').val();
			load_teams_list('esid',esID);
		}
	});
});


$("body").on('keyup','#teamSeason',function(){
  var val = $.trim($(this).val());
  $('#teamSeasonDropdown').removeClass('hidden');
  var placeHolder = $('#teamSeasonDropdown');
  placeHolder.html('');
  $('#tID').val('');

  if (val) {
  	$('.extra').removeClass('hidden');

    $.ajax({
        url: "autocomplete.php",
        //dataType: "json",
        data: {
          option: "team",
          tname: val,
          side: 'teamSeason'
        },
        success: function(data){

          $("#teamSeasonDropdown").html(data);

        }
      });
  };
});

$('body').on('mouseenter','li.team',function(){
	var $that = $(this);
	var hideThis = $that.find('div.pull-right.hidden');

	hideThis.removeClass('hidden');
	
	$that.on('mouseleave',function(){
		hideThis.addClass('hidden')
	});
});

// insert autocomplete dropdown values 
$('body').on('click','.list-group-item.teamSeason',function(){
  event.preventDefault();
  var $that = $(this);
  var ID = $that.attr('data-ID');
  var name = $that.find('h4.list-group-item-heading').text();

  $('#teamSeason').val(name);
  $('#tID').val(ID);
  $(".list-group-item.teamSeason").remove();
    
});

$('body').on('click','span.tname',function(){
//	var $this = $(this);
//	$(this).html($this + '<button>?</button>');
});