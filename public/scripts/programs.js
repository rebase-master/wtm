/* global $, console, alert, baseUrl */

$(function(){

    $('#aqform').on('submit', function (e) {
        e.preventDefault();
        var cf      = $(this).find('#comment'),
            sb      = $(this).find('input[type="submit"]'),
            comment = cf.val().trim(),
            ucc     = $('#user-comments'),
            cctr    = ucc.find('.cctr'),
            id      = parseInt($('#ccomments').find('input[type="hidden"]').val());

        if(comment == '' || isNaN(id)){
            alert('Comment cannot be blank.');
        }else{

            $.ajax({
                type:    'POST',
                url:     baseUrl+'programs/ajax-add-comment',
                data:    {comment: comment, pid: id},
                beforeSend: function(){
                  sb.attr('disabled', true);
                },
                success: function(data){
                            data = JSON.parse(data);
                            if(data.status == 1){
                                var c = $('<div class="comment"><a href="'+baseUrl+'u/'+data.username+'">'+data.username+'</a><p>'+data.comment+'<span class="ctime">a few seconds ago</span></p></div>');
                                ucc.find('.cc').removeClass('hide').prepend(c);
                                cf.val('');
                                cctr.text(parseInt(cctr.text())+1);
                            }else{
                                alert('Something went wrong!');
                            }
                },
                complete: function(){
                    sb.removeAttr('disabled');
                }
            });

        }

    });

});

