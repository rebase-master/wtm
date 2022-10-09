$(document).ready(function(){
  $(".sol_snippet").hide();
  $("button").click(function(){$(this).next().slideToggle(5);$(this).next().css("visibility","visible")});
  $("#guidelines").hide();$(".slide").click(function(){$("#guidelines").css("visibility","visible");
  $("#guidelines").slideToggle(500,function(){if($(this).is(":hidden")){$(".slide").html("&#x25BE; ")}else{$(".slide").html("&#x25B4; ")}})});
  $(".years li div").hide();
  $(".years h4").click(function(){$(this).next().slideToggle(500);$(this).next().css("visibility","visible")})
initFun();
});
function initFun(){
	 $('.q_ech').hover(function(){
	$(this).css({'border':'1px solid #ddd'});
	$(this).find('.post_rc:first').css({'display':'block'});
 },function(){
	$(this).css({'border':'1px solid #eee'});
	$(this).find('.post_rc:first').css({'display':'none'});
 }
 );
 $('.aqc').hover(function(){
	$(this).css({'border':'1px solid #ddd'});
	$(this).find('.post_rc:first').css({'display':'block'});
 },function(){
	$(this).css({'border':'1px solid #eee'});
	$(this).find('.post_rc:first').css({'display':'none'});
 }
 );
 $('.response').hover(function(){
	$(this).find('.post_rc:first').css({'display':'block'});
	$(this).find('.v_unu').css({'opacity':'1'});
 },function(){
	$(this).find('.post_rc:first').css({'display':'none'});
	$(this).find('.v_unu').css({'opacity':'0.2'});
 }
 );
 $('.v_unu').hover(function(){
	var first=$(this).find('img:first');
	first.attr({'src':'/images/v_auc1.png'});
	first.nextAll('img').attr({'src':'/images/v_adc1.png'});
 },function(){
	var first=$(this).find('img:first');
	first.attr({'src':'/images/v_au1.png'});
	first.nextAll('img').attr({'src':'/images/v_ad1.png'});
 }
 );
}
function addComment(e){
	var pid=$(e).parent().parent().parent().parent().prevAll('.aqc:first').find('input').val();
	var loadimg=$('.loading');
	var ta=$(e).prev('textarea');
	var comment=ta.val().trim();
	ta.val('');
	if(comment=="")
	    alert("Please Enter some text!");
	else{
	  $.ajax({
	      type: 'POST',
	      url: baseUrl+'programs/ajax-add-comment',
	      data: { 'pid' : pid, 'comment':comment},
		    dataType: 'json',
		    beforeSend: function(){
			    loadimg.removeClass('hide');
//                $(e).addClass('hide');
		    },
		    success:function(data){
                if(data == -1){
                    alert("You are not logged in.");
                }else{
                    reply=$('<div class="response res_cont"><input type="hidden" value="'+data.cid+'" /><a class="dp_reply" href="http://www.wethementors.com/profile/index/user/'+data.username+'"><img src="'+data.dp+'" /></a><div class="reply"><span class="post_rc" onclick="rtc_c(this);"><img src="/images/del.png" title="Delete comment" alt="Delete comment" /></span><a class="ru" href="/profile/index/user/'+data.username+'">'+data.username+'</a><span class="dt">...'+data.time+'</span><br style="clear:left" /><p class="reply_text">'+data.comment+'</p></div></div>');
                    $('#user_sols').append(reply);
                    $('.nosol').hide();

//                    initFun();
                }
		    },
		    complete:function(){
//		    loadimg.css({'display':'none'});
		    },
          error: function(x,t,m){
              alert("An error occured!");
          }
	      });
	}
}
function rtc(e){
	pid=$(e).nextAll('input:first').val();
	loadimg=$('<span class="vote-img"></span>');
		$.ajax({
		type:'POST',
		url:baseUrl+'programs/ajax-remove-question',
		data:{pid:pid},
		timeout:10000,
		beforeSend: function(){
			loadimg.css({'display':'block','margin-top':'15px', 'width':'50px'});
			$(e).parent().parent().prepend(loadimg);
		},
		success:function(data){
		window.location.replace('http://www.wethementors.com/programs/questions');
		},
		complete:function(){
		loadimg.remove();
		},
		error:function(){
		$(e).parent().text('An error occured! Please try again in some time.');
		}
	});
}
function rtc_c(e){
	cid=$(e).parent().prevAll('input:first').val();
	loadimg=$('<span class="vote-img"></span>');
		$.ajax({
		type:'POST',
		url:baseUrl+'programs/ajax-remove-comment',
		data:{cid:cid},
		timeout:10000,
		beforeSend: function(){
			loadimg.css({'display':'block','margin-top':'15px', 'width':'50px'});
			$(e).parent().parent().prepend(loadimg);
		},
		success:function(data){
		$(e).parent().parent().fadeOut("slow").sleep(1000).remove();
		},
		complete:function(){
			$('#user_sols').find('.nosol').css({'display':'block'});
		loadimg.remove();
		},
		error:function(){
		  $(e).parent().text('An error occured! Please try again in some time.');
		}
		});
}
function acv(e,mode){
	cid=$(e).parent().parent().parent().find('input').val();
    $.ajax({
            type: 'POST',
            url: baseUrl+'programs/ajax-add-comment-vote',
            data: {cid : cid,mode:mode},
			dataType: 'json',
			success:function(data){
			$(e).parent().find('p').text(data.votes);
			var vote = $(e).parent().find('p');
			if(data.votes == 1)
				vote.attr('title','1 person found this comment useful');	
			else if(data.votes == -1)
				vote.attr('title','1 person found this comment not useful');
			else if(data.votes > 1)
				vote.attr('title',data.votes+' people found this comment useful');
			else if(data.votes < -1)
				vote.attr('title',data.votes+' people found this comment useful');
			else
				vote.attr('title','Be the first to vote for this comment.');	
			initFun();
			}
	});
}
