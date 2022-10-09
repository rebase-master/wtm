"use strict";

var tags = [];

$(function(){

    initFun();

    $(".alert button.close").click(function (e) {
        $(this).parent().addClass('hide');
    });
    getTags();
});
function getTags(){
    $.ajax({
        type: 'GET',
        url: baseUrl+'qa/ajax-find-tags',
        success: function(d){
            if( d != -9999){
                var data = JSON.parse(d);
                $.each(data, function(i,k){
                   tags[i] = k['tag'];
                });
            }
        }
    });
}
function initFun(){
    $('.aqc-qc').hover(function(){
        var ae = $(this).find('.post_rc');
        ae.removeClass('hide');
    },function(){
        var ae = $(this).find('.post_rc');
        ae.addClass('hide');
    });

    var quesCont = $('.qa-ask');

    //Post a question
    quesCont.find('#ask-question').on('click',function(){
        var data = {};
        var title = quesCont.find('#heading'),
            desc = quesCont.find('#description'),
            tag = quesCont.find('#tag'),
            titleVal = title.val().trim(),
            descVal = desc.val().trim(),
            tagVal = tag.val().trim(),
            notice = quesCont.find('.notice');

        if(titleVal == '' || descVal == '' || tagVal == ''){

            notice.removeClass().addClass('alert alert-danger notice').find('p').text('All fields are required.');

        }else if(titleVal.length < 15){

            notice.removeClass().addClass('alert alert-danger notice').find('p').text('Title must be at least 15 characters in length.');

        }else if(descVal.length < 30){

            notice.removeClass().addClass('alert alert-danger notice').find('p').text('Description must be at least 30 characters in length.');

        }else{

            notice.removeClass().addClass('alert alert-success notice').find('p').text('Please wait. Adding your question...');
            data.tags = tagVal;
            data.question = titleVal;
            data.description = descVal;

            aques(data);
        }
        return false;
    });

    $('#tag').tagsinput({
        typeahead: {
            source: tags
        },
        maxTags: 5
    });

}

function aques(data){
    $.ajax({
        type: 'POST',
        url: baseUrl+'qa/ajax-add-question',
        data: {'data': data},
        beforeSend: function(){

        },
        success: function(d){
            if(d == -9999){
                alert('An error occured!');
                location.reload();
            }else if(d == -1){
                $('.qa-ask').find('.notice').addClass('alert-danger').find('p').text('You need to login to ask a question.');
            }else{
                window.location = baseUrl+'qa/questions/id/'+parseInt(d);
            }
        }
    })
}
function searchTags(ele){
    var searchField = ele.val(),
    regex = new RegExp(searchField, "i"),
    output = $('#tag-suggest'),
    list = '<ul>',
    flag = false;

    $.each(tags, function(key, val){
        if((val.search(regex) != -1)){
            list +='<li><a href="'+tags[key]+'">'+val+'</a></li>';
            flag = true;
        }
    });

    if(!flag){
        list += "<li>No results found</li>";
    }
    list +='</ul>';
//    var elePos = ele.offset();
//    var eleWidth = ele.width();
//    output.css({'top':(elePos.top+28), 'left':(elePos.left), 'width':eleWidth+100,'display': 'inline-block'});
    output.empty().append(list);
}


function aans(e){
    var pid=$('.aqc').find('.aqc-qc').find('input[type="hidden"]').val();
	var loadimg=$('.loading');
    var ta=$(e).parent().prev('.comment_post').find('textarea');
	var comment=ta.val().trim();
	if(comment=="")
	    alert("Please Enter some text!");
	else{
	  $.ajax({
	      type: 'POST',
	      url: baseUrl+'qa/ajax-add-comment',
	      data: { 'pid' : pid, 'comment':comment},
		    dataType: 'json',
		    beforeSend: function(){
			    loadimg.removeClass('hide');
                $(e).addClass('hide');
		    },
		    success:function(data){
                if(data == -1){
                    alert("You are not logged in.");
                }else if(data == 0){
                    alert('An error occured');
                }else{
                    //var reply=$('<div class="response res_cont"><input type="hidden" value="'+data.cid+'" /><a class="dp_reply" href="http://www.wethementors.com/profile/index/user/'+data.username+'"><img src="'+data.dp+'" /></a><div class="reply"><span class="post_rc" onclick="rtc_c(this);"><img src="'+baseUrl+'images/del.png" title="Delete comment" alt="Delete comment" /></span><a class="ru" href="/profile/index/user/'+data.username+'">'+data.username+'</a><span class="dt">...'+data.time+'</span><br style="clear:left" /><p class="reply_text">'+data.comment+'</p></div></div>');
                    var props = ['cid','username','time','dp','comment'],
                        flag = true;

                    for(var key in props){
                        if(!data.hasOwnProperty(props[key])){
                           flag = false;
                            break;
                        }
                    }
                    if(flag){
                        var reply = '<div class="aoq"><div class="clearfix"><div class="aqc-vc col-xs-2 col-sm-2 col-md-2"><div class="metrics text-center pull-right"><div class="alert alert-dismissable alert-warning vote-error hide" role="alert"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><p>You cannot vote on your own answer.</p></div><div onclick="acv(this,1)" class="arrow-up vote-i own" title="This answer is useful"></div><div class="qv-count"><p>0</p></div><div onclick="acv(this,-1)" class="arrow-down vote-i own" title="This answer is not useful"></div></div></div><div class="aqc-qc res_cont col-xs-10 col-sm-10 col-md-10"><input type="hidden" value="'+data.cid+'" /><div class="feedback col-md-12"><span class="post_rc pull-right hide" onclick="rta(this);"><img src="'+baseUrl+'/images/del.png" title="Delete answer" alt="Delete answer" /></span><p class="reply_text">'+data.comment+'</p></div><br /><div class="asker col-xs-6 col-md-4 col-md-offset-8 clearfix"><div class="asker_pic pull-left"><a href="'+baseUrl+'u/'+data.username+'"><img src="'+data.dp+'" alt="'+data.username+'" title="'+data.username+'" onerror="this.src=\''+baseUrl+'images/placeholder_male.png\'"; /></a></div><div class="asker_username pull-left"><a class="username" href="'+baseUrl+'u/'+data.username+'">'+data.username+'</a><span class="date_asked">'+data.time+'</span></div></div></div></div></div>';
                        $('#user_sols').append(reply);
                        $('.nosol').hide();

                        initFun();
                    }else{
                        alert('An error occured!');
                    }
                }
		    },
		    complete:function(){
                loadimg.addClass('hide');
                ta.val('');
                $(e).removeClass('hide');
            },
          error: function(x,t,m){
              alert("An error occured!");
          }
	      });
	}
}

function rta(e){
    if(confirm('Are you sure you want to delete this?')){
        var cid=$(e).parent().prevAll('input:first').val();
        var loadimg=$('<span class="vote-img"></span>');
        $.ajax({
            type:'POST',
            url:baseUrl+'qa/ajax-remove-comment',
            data:{cid:cid},
            timeout:10000,
            beforeSend: function(){
                loadimg.css({'display':'block'});
                $(e).parent().parent().prepend(loadimg);
            },
            success:function(data){
                $(e).parent().parent().parent().parent().animate({'opacity': '0.0'},2000, function(){ $(this).remove(); });
            },
            complete:function(){
//                $('#user_sols').find('.nosol').css({'display':'block'});
                loadimg.remove();
            },
            error:function(){
                $(e).parent().text('An error occured! Please try again in some time.');
            }
        });
    }
}

function rtq(e){
    if(confirm('Are you sure you want to delete this?')){

        var pid = parseInt($('#qHead').find('#qid').val()),
    	    loadimg = $('<span class="vote-img"></span>');

        if(pid > 0){
            $.ajax({
                type:'POST',
                url: baseUrl+'qa/ajax-remove-question',
                data: {pid:pid},
                beforeSend: function(){
                    loadimg.css({'display':'block'});
                    $(e).parent().prev('.feedback').prepend(loadimg);
                },
                success:function(data){
                    window.location.replace(baseUrl+'qa');
                },
                complete:function(){
                    loadimg.remove();
                },
                error:function(){
                    $(e).parent().text('An error occured! Please try again in some time.');
                }
            });
        }else{
            alert('Something went wrong.');
        }
    }
}
function acv(e,mode){
    var uvc = $(e).parent().find('.arrow-up');
    var dvc = $(e).parent().find('.arrow-down');
    var voteError = $(e).parent().find('.vote-error');
    if($(e).hasClass('vu') || $(e).hasClass('vd')){
        voteError.removeClass('hide').find('p').text('You already voted on this.');
        return false;
    }

    if($(e).hasClass('own')){
        voteError.removeClass('hide').find('p').text('You cannot vote on your own answer.');
        return false;
    }

    if($(e).hasClass('login')){
        voteError.removeClass('hide').find('p').text('You need to log in to vote.');
        return false;
    }

    if(mode === 1){
        if(!uvc.hasClass('vu'))
            uvc.addClass('vu');
        else
            return false;
        dvc.removeClass('vd');
    }else{
        if(!dvc.hasClass('vd'))
            dvc.addClass('vd');
        else
            return false;
        uvc.removeClass('vu');
    }
	var cid = $(e).parent().parent().parent().find('input').val();
    var vc = $(e).parent().find('.qv-count').find('p');
    var cv = parseInt(vc.text()) + parseInt(mode);
    vc.text(cv);
    $.ajax({
            type: 'POST',
            url: baseUrl+'qa/ajax-comment-vote',
            data: {cid : cid, mode:mode},
			dataType: 'json',
			success:function(data){
    			initFun();
			}
	});
    return false;
}

function qv(e,mode){
    var uvc = $(e).parent().find('.arrow-up');
    var dvc = $(e).parent().find('.arrow-down');
    var voteError = $(e).parent().find('.vote-error');
    if($(e).hasClass('vu') || $(e).hasClass('vd')){
        voteError.removeClass('hide').find('p').text('You already voted on this.');
        return false;
    }

    if($(e).hasClass('own')){
        voteError.removeClass('hide').find('p').text('You cannot vote on your own question.');
        return false;
    }

    if($(e).hasClass('login')){
        voteError.removeClass('hide').find('p').text('You need to log in to vote.');
        return false;
    }

    if(mode === 1){
        if(!uvc.hasClass('vu'))
            uvc.addClass('vu voted');
        else
            return false;
        dvc.removeClass('vd voted');
    }else{
        if(!dvc.hasClass('vd'))
            dvc.addClass('vd voted');
        else
            return false;
        uvc.removeClass('vu voted');
    }
    var pid = $(e).parent().parent().parent().find('input[type="hidden"]:first').val();
    var vc = $(e).parent().find('.qv-count').find('p');
    var cv = parseInt(vc.text()) + parseInt(mode);
    vc.text(cv);
    $.ajax({
        type: 'POST',
        url: baseUrl+'qa/ajax-question-vote',
        data: {pid : pid, mode:mode},
        dataType: 'json',
        success:function(data){
            initFun();
        }
    });
    return false;

}