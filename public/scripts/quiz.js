"use strict";

window.onload=function(){
	$('input[type="radio"]').each(function(){
	    $(this).prop('checked', false);
	});
};

var counter=1;
var limit=10;
var attempts = [];
var index=0;
var timeInSecs;
var ticker;
var flag=false;

function startTimer(secs,l){
	limit=l;
	timeInSecs = parseInt(secs)-1;
	ticker = setInterval("tick()",1000);   // every second
}
function tick() {
var mins=Math.floor(timeInSecs/60),
    secs = Math.floor(timeInSecs%60),
    pad1, pad2;

	if (timeInSecs>0) {
	    timeInSecs--;
	if(mins==0 && secs<20 && flag==false){
	    alert('You have less than 20 seconds to finish the quiz. The quiz will auto-submit once the timer reaches zero.');
	    flag=true;
	}
	if(mins<10 && mins>0)
	    pad1="0";
	else
	    pad1="";
	
	if(secs<10)
	    pad2=":0";
	else
	    pad2=":";
	document.getElementById("timer").innerHTML = pad1+mins+"m"+pad2+secs+"s";
	}else{
	    clearInterval(ticker); // stop counting at zero
	    document.getElementById("timer").innerHTML = "0:0";
		if(mins==0 && secs==0){
		    showResult();
		}
	}

}

function nextQuestion(){
var radio_buttons = $('#options-group').find("input[name='options']");
if( radio_buttons.filter(':checked').length == 0){
  alert('Please choose an option');
} else {

    var i, id=$('#qno').val(),
	    option=radio_buttons.filter(':checked').attr('id'),
        qq = $('.quiz_ques');

	index++;
	attempts[counter-1] = option;
	$('#loading').show();
	qq.hide();

  $.ajax({
   type:'POST',
   url:baseUrl+'fun/java-beginner',
   data:{ques:++counter},
   datatype:'json',
   success:function(data){
        qq.find('h2:first').text('Question '+counter);
        $('#question').replaceWith("<p id=\'question\'>"+data['question']+"</p>");
        $('#qno').replaceWith("<input type=\'hidden\' id=\'qno\' value="+data['id']+" />");
        for(i=1;i<=4;i++){
            $("label[for='options"+i+"']").find('.option-group').replaceWith('<span class="option-group">'+data['options'][i-1]+'</span>');
        }
        $('#loading').hide();
        qq.show();
        if(counter==limit){
            $('#next').attr('disabled','disabled').css({'background-color':'#ccc', 'color':'#000','cursor':'auto'});
            $('#skip').css({'display':'none'});
       }
   },
   complete:function(){
        var stop = $('.content').offset().top;
        var delay = 1000;
        $('body,html').animate({scrollTop: stop}, delay);
	}
  });

	$('input[type="radio"]').each(function(){
	    $(this).prop('checked', false);
	});

    }
}

function skip(){
	var stop = $('.content').offset().top,
        delay = 1000,
        loading = $('#loading'),
        qq = $('.quiz_ques');

    loading.show();
    qq.hide();

    $('body,html').animate({scrollTop: stop}, delay);
	$.ajax({
	  type:'POST',
	  url:baseUrl+'fun/java-beginner',
	  data:{ques:++counter},
	  datatype:'json',
	  success:function(data){
		qq.find('h2:first').text('Question '+counter);
		$('#question').replaceWith("<p id=\'question\'>"+data['question']+"</p>");
		$('#qno').replaceWith("<input type=\'hidden\' id=\'qno\' value="+data['id']+" />");
		for(var i=1;i<=4;i++){
		    $("label[for='options"+i+"']").find('.option-group').replaceWith('<span class="option-group">'+data['options'][i-1]+'</span>');
		}
		loading.hide();
		qq.show();
		if(counter>=limit){
			$('#next').attr('disabled','disabled').css({'background-color':'#ccc', 'color':'#000','cursor':'auto'});
			$('#skip').css({'display':'none'});
	    }
	}
  });

	$('input[type="radio"]').each(function(){
	    $(this).prop('checked', false);
	});
}
function showResult(){
var radio_buttons = $("input[name='options']");
$('#loading').show();
$('.quiz_ques').hide();
	var id=$('#qno').val();
	if(radio_buttons.filter(':checked').length != 0){
		attempts[counter-1] = radio_buttons.filter(':checked').attr('id');
		index++;
	}
	if(index==0){
		attempts="null";
	}
	$.ajax({
		type:'POST',
		url:baseUrl+'fun/java-beginner',
		data:{answers:attempts},
		success:function(data){
		},
		error:function(data){
		}
	});
}