var emoticons = {  smile: '<img src="/images/emoticons/smile.png" />',  mute: '<img src="/images/emoticons/mute.png" />',  teeth: '<img src="/images/emoticons/teeth.png" />',  tongue: '<img src="/images/emoticons/tongue.png" />',  sad:  '<img src="/images/emoticons/sad.png" />',  wink:  '<img src="/images/emoticons/wink.png" />'};
var patterns = {
    smile: /:-\)/gm,
    smile1: /:\)/gm,
    mute: /:-\|/gm,
    mute1: /:\|/gm,
    sad: /:-\(/gm,
    sad1: /:\(/gm,
    wink: /;-\)/gm,
    wink1: /;\)/gm,
    tongue: /:-P/gm,
    tongue1: /:P/gm,
    tongue2: /:p/gm,
    teeth: /:-\D/gm,
    teeth1: /:\D/gm
};
var fId = [], fUsername = [];
function getFriends(){
    $.ajax({
        type: 'GET',
        url: baseUrl+'profile/ajax-filter-friends',
        success: function(d){
            if( d != -1){
                var data = JSON.parse(d);
                $.each(data, function(i,k){
                    fId[i] = k['id'];
                    fUsername[i] = k['username'];
                });
            }
            console.log(fId);
            console.log(fUsername);
            initFriends();
        }
    });
}
function initFriends(){
    $("#new-msg-modal").find('#touser').select2({
        createSearchChoice: function() { return null; },
        tags: fUsername
    });
//    $('#new-msg-modal').on('hidden.bs.modal', function () {
//        $("#new-msg-modal").find('#touser').select2({
//            clearSearch: function(){}
//        });
//    });
}

function sendMessage(ids, body, msgModal){
    $.ajax({
        type: 'POST',
        url: baseUrl+'profile/send-message',
        data: {to: ids, message: body},
        success: function(e){
            if(msgModal !== undefined){
                if(e == 1){
                    msgModal.find('.alert-info').removeClass('hide');
                    setTimeout(function(){
                        msgModal.modal('hide');
                        msgModal.find('.alert-info').addClass('hide');
                    },2000);
                }else{
                    msgModal.find('.alert-danger').removeClass('hide');
                    setTimeout(function(){
                        msgModal.modal('hide');
                        msgModal.find('.alert-danger').addClass('hide');
                    },2000);
                }
            }
        }

    });
}

$(function(){
    $('#stcon').on('click', function(){
        var dp = $(this).prev('input[type="hidden"]:first').val();
        var username = $('#sidebar_left').find('.head').find('input[type="hidden"]').val();
        var id = $('#inbox').find('input[type="hidden"]:first').val();
        var messageCont = $(this).parent().prev('.msgbox').find('#message');
        var message = messageCont.val();
        if(message.trim() != ''){
            var addMessage =
                '<div class="replies clearfix">'+
                    '<aside class="col-xs-2 col-sm-2">'+
                        '<img src="'+dp+'" height="30" width="30" />'+
                    '</aside>'+
                    '<div class="reply col-xs-10 col-sm-10">'+
                        '<div class="msgTime"><p>just now</p></div>'+
                        '<p class="name"><a href="'+baseUrl+'profile/'+username+'">'+username+'</a></p>'+
                        '<p class="reply-text">'+ message+'</p>'+
                    '</div>'+
                '</div>'
            ;
            $('#inbox').find('.mCSB_container').append(addMessage);
            $("#inbox").mCustomScrollbar("scrollTo","bottom");
            messageCont.val('');

            sendMessage(id, message);
        }
    });
    var msgModal = $("#new-msg-modal");
    msgModal.find('#sendmsg').on('click',function(){
        var username = msgModal.find('#touser').val();
        var body = (msgModal.find('#msgbody').val()).trim();
        if(body != ''){
            var usernames = username.split(",");
            var ids = [];
            for(var i in usernames){
                var iofu = fUsername.indexOf(usernames[i]);
                ids[i] = fId[iofu];
            }
            sendMessage(ids, body, msgModal);
       }
    });

    var windowHeight = window.innerHeight;
    $('.profileCont #sidebar_left, .profileCont #sidebar_right').height((windowHeight-100)+'px');
    $('.profileCont .profile').height((windowHeight-110)+'px');
    $('#inbox').height($('.profileCont .profile').height()-120+'px');

    $("#inbox").mCustomScrollbar({
        theme: "dark-thick",
        scrollInertia: 0,
        autoHideScrollbar: true
    });
    setTimeout(function(){
        $("#inbox").mCustomScrollbar("scrollTo","bottom");
    },100);
    $('#new-msg-modal').on('hidden.bs.modal', function () {
//        $('.select2-results').css('display','none');
        $('.select2-drop-active').select2('close');
    })
//    if($('#inbox')[0])
//        $('#inbox').scrollTop($('#inbox')[0].scrollHeight);
//    $("html, body").animate({ scrollTop: $('#main').height()-400 }, 0);
//    if($('#inbox').hasClass('indi')){
//        $("html, body").animate({ scrollTop: $('#inbox').height() }, 1000);
//        $("#inbox").scrollTop($('#inbox').height());
//    }

    $('p').each(function() {var $p = $(this);var html = $p.html();
        $p.html(html.replace(patterns.smile, emoticons.smile).replace(patterns.smile1, emoticons.smile)
            .replace(patterns.sad, emoticons.sad).replace(patterns.sad1, emoticons.sad)
            .replace(patterns.mute, emoticons.mute).replace(patterns.mute1, emoticons.mute)
            .replace(patterns.tongue, emoticons.tongue).replace(patterns.tongue1, emoticons.tongue)
            .replace(patterns.tongue2, emoticons.tongue).replace(patterns.teeth, emoticons.teeth)
            .replace(patterns.teeth1, emoticons.teeth).replace(patterns.wink, emoticons.wink).replace(patterns.wink1, emoticons.wink));});

    getFriends();

});