"use strict";
function afr(e,r){
    var parent = e.parent().parent();
    $.ajax({
        type: 'POST',
        url: baseUrl+'profile/afr',
        data: {i:e.parent().find('input[type="hidden"]').val(), r: r},
        beforeSend: function(){
          parent.css('opacity', '.5');
        },
        success: function(d){
            if(d == -1){
                alert('You must be logged in to continue.');
                location.reload();
            }else{
                if(r == 1){
                    e.parent().empty().append('<a href="javascript:void(0)" class="btn btn-primary btn-sm disabled"><span class="glyphicon glyphicon-ok"></span>&nbsp; &nbsp;Friends</a>');
                    parent.css('opacity', '1');
                }else{
                    parent.animate({'opacity': '0'}, 3000, function(){$(this).remove();})
                }
            }
        }
    })
}
function uploadDp(e){

    $('input[type="file"]').change(function (e) {
        e.stopPropagation();
        e.preventDefault();
        var formData = new FormData($(this).closest('form')[0]);

        $.ajax({
            url: baseUrl + "profile/change-dp",
            type: 'POST',
            beforeSend: function(){
                $('#dp-cont').css('opacity','.5');
            },
            success: function(d) {
                if(d == -1){
                    alert('You must be logged in to continue.');
                    location.reload();
                }else if(d == 1){
                    alert("Image cannot exceed 5MB in size.");
                }else if(d == 2){
                    alert("Only JPEG and PNG images are allowed");
                }else if(d == -999){
                    alert('Something went wrong.');
                    location.reload();
                }else{
                    var date = new Date(),
                        seconds = date.getTime(),
                        dpCont = $('#dp-cont');

                    dpCont.find('img').removeAttr('src').attr('src', '/users/images/'+d+'?time='+seconds);
                    $('.ux-cont').find('.user-link').find('img').removeAttr('src').attr('src', '/users/thumbs/'+d+'?time='+seconds);
                    dpCont.css('opacity','1');
                }
            },
            error: function() {
                alert('An error occurred. Please try again later.');
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }, 'json');
    });
    return false;
}

function checkAdScroll(){
    var navOffset = $('#sidebar_right').height();
    var headerHeight = $('#header').outerHeight();
    var winWidth = $(window).width();

    $(window).scroll(function(){
        var stickyNav = $('#sidebar_right').find('.qc'),
//            stickyGad = $('#sidebar_right').find('.google-ad'),
            scroll = $(window).scrollTop();
        if(winWidth > 995){
            if (scroll >= navOffset-stickyNav.height()){
//                stickyGad.addClass('qlnks').css({'top': headerHeight-20, 'margin-top': '25px', 'margin-bottom': '5px'});
                stickyNav.addClass('qlnks').css('top', headerHeight+20);
            } else {
                stickyNav.removeClass('qlnks');
//                stickyGad.removeClass('qlnks');
            }
        }else if(winWidth > 768 && winWidth <=995){
            if (scroll >= navOffset){
//                stickyGad.addClass('qlnks').css({'top': headerHeight-20, 'margin-top': '25px', 'margin-bottom': '5px'});
            } else {
//                stickyGad.removeClass('qlnks');
            }
        }
    });
}

function stickAccordionOnScroll(){
    var navOffset = $('#accordion').height();
    var headerHeight = $('#header').outerHeight();
    var winWidth = $(window).width();

    $(window).scroll(function(){
        var stickyNav = $('#accordion'),
            scroll = $(window).scrollTop();
        if(winWidth > 995){
            if (scroll >= navOffset){
                stickyNav.addClass('qlnks').css('top', 20);
            } else {
                stickyNav.removeClass('qlnks');
            }
        }else if(winWidth > 768 && winWidth <=995){
            if (scroll >= navOffset){
//                stickyGad.addClass('qlnks').css({'top': headerHeight-20, 'margin-top': '25px', 'margin-bottom': '5px'});
            } else {
//                stickyGad.removeClass('qlnks');
            }
        }
    });
}

function quoteShare(desc, url) {
    FB.ui({
        method: 'feed',
        name: 'Quotes',
        caption: '',
        description: desc,
        link: url,
        picture: baseUrl+'images/logo_brand_med.png',
        display: 'dialog'
    }, function(response) {
        if (response && response.post_id) {
            console.log(response);
        } else {
            console.log("error: ");
            console.log(response);
        }
    });
}

function fbFactShare(desc, pid) {
    console.log(window.location);
    FB.ui({
        method: 'feed',
        name: 'Fun Fact',
        description: desc,
        link: window.location.href+'#'+pid,
        picture: baseUrl+'images/logo_brand_med.png',
        display: 'dialog'
    }, function(response) {
        if (response && response.post_id) {
        } else {
            console.log("error: ");
            console.log(response);
        }
    });
}


$(function(){
//    checkScroll();
//    if($('.iscpp-link')[0])
//        checkScroll();
//    stickAccordionOnScroll();

    $('#change-dp').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        $('#user-dp').click();
        uploadDp();
        return false;
    });
    $('.friend_requests').find('.friends_response').find('.unfriend').on('click', function(){
       var ele = $(this);
        var id = ele.prev('input[type="hidden"]').val();
        $.ajax({
            type: 'POST',
            url: baseUrl+'profile/unfriend',
            data: {id: id},
            beforeSend: function(){
                ele.attr('disabled', 'disabled');
            },
            success: function(e){
                if(e == 1){
                    ele.parent().parent().animate({'opacity': '0'}, 2000, function(){
                        $(this).remove();
                    })
                }else if(e == -1){
                    alert('You are not logged in.');
                    location.reload();
                }else{
                    alert('An error occurred.');
                    location.reload();
                }
            }
        })
    });

    $('.req-cont').find('.request_response').find('.accept').on('click', function(){
        afr($(this),1);
    });
    $('.req-cont').find('.request_response').find('.reject').on('click', function(){
        afr($(this),-1);
    });
    $('#profile_center').find('.interaction').find('#add-friend').on('click',function(){
        var btn = $(this);
        $.post(baseUrl+'profile/add-friend', {id: $('#dp-cont').find('input[type="hidden"]').val()}, function(d){
            if(d == -1){
                alert('You must be logged in to continue');
                location.reload();
            }else if(d ==0){
                alert('An error occured.');
            }else{
                btn.replaceWith('<a href="javascript:void(0);" class="edit editRequest btn btn-primary btn-sm disabled"><span class="glyphicon glyphicon-plus"></span>&nbsp; &nbsp;Friend Request Sent</a>');
            }
        });
    });
    $(".slide").on('click',function(){

        var guidelines = $('#guidelines');

        if(guidelines.hasClass('hide')){
            guidelines.removeClass('hide');
        }else{
            guidelines.addClass('hide');
        }
    });

    $('.afds').on('click',function(e){
        $.ajax({
            url: baseUrl+'index/aj-a-dwns',
            type: 'POST',
            data: {a: $(this).attr('title')}
        });
//            return false;
    });

    $('#icp, #guess_questions').find('.qdesc').find('.vhs').on('click',function(){
        var solution = $(this).next('.solution');
        if(solution.hasClass('hide'))
            solution.removeClass('hide');
        else
            solution.addClass('hide');
    });

    $('.programming.topicsList').find('.questions-list').find('li').find('button').on('click',function(){
        var solution = $(this).next('.solution');
        if(solution.hasClass('hide'))
            solution.removeClass('hide');
        else
            solution.addClass('hide');
    });

    $('#practicals').find('.years').find('li>h4').on('click',function(){
        var solution = $(this).next('.qdesc');
        if(solution.hasClass('hide'))
            solution.removeClass('hide');
        else
            solution.addClass('hide');
    });
    $('#riddles').find('li').find('button').on('click',function(){
        var ans = $(this).next('.quiz_answer');
        console.log('clicked');
        if(ans.hasClass('hide'))
            ans.removeClass('hide');
        else
            ans.addClass('hide');
    });

    function fbShare(name, desc, pic, pid) {
        FB.ui({
            method: 'feed',
            name: name,
            caption: '',
            description: desc,
            link: baseUrl+'videos?page='+pid,
            picture: pic,
            display: 'dialog'
        }, function(response) {
            if (response && response.post_id) {
                console.log(response);
            } else {
                console.log("error: ");
                console.log(response);
            }
        });
    }

    $("#fb-publish").on('click',function() {
        var iframe = $(this).parent().parent().prev('.video-page').find('iframe').attr('src');
        var uvid = iframe.substr(iframe.lastIndexOf('/')+1);
        var image = 'http://i1.ytimg.com/vi/'+uvid+'/sddefault.jpg';
        var heading = $(this).parent().parent().parent().find('h1').text();
        var description = $(this).parent().parent().next('.video-desc').text();
        var pid = $(this).parent().prev('input[type="hidden"]').val();
        FB.login(function(response) {
            if (response.authResponse) {
                fbShare(heading, description, image, pid);
            }
        }, {scope: 'publish_actions'});
    });

    $(".fb-quote-publish").on('click',function() {

        var quote   =   $(this).parents('.quotes').find('.qtext').text().trim(),
            author  =   $(this).parents('.quotes').find('.author').text().trim(),
            url     =   $(this).parents('.quotes').data('url');

        //console.log("quote: #"+quote+"#");
        //console.log("author: #"+author+"#");
        //console.log("slug: #"+slug+"#");
        //return false;
        FB.login(function(response) {
            if (response.authResponse) {
                quoteShare(quote+' '+author, url);
            }
        }, {scope: 'publish_actions'});
    });


    $(".fb-fact-publish").on('click',function() {
        var description = $(this).parent().prev('.fact').text();
        var pid = $(this).parent().parent().attr('id');
        FB.login(function(response) {
            if (response.authResponse) {
                fbFactShare(description, pid);
            }
        }, {scope: 'publish_actions'});
    });


    $('#tutorials').find('.note_desc').find('img').each(function (e, k) {
        //console.log(k);
        var image = $(k).attr('src').replace(/^.*[\\\/]/, '');
        console.log(image.replace(/^.*[\\\/]/, ''));
        $(k).attr('src', baseUrl+'images/content/'+image);
    });

});

