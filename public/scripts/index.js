
var done = 0;
//baseUrl = 'http://localhost/wethementors/public/';


window.fbAsyncInit = function () {
    FB.init({
        appId: APP_ID,
//        channelUrl: apiUrl + 'channel',
        oauth: true,
        frictionlessRequests: true,
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true  // parse XFBML
    });

    FB.getLoginStatus(function (response) {
        if (response.status === 'connected') {
            // the user is logged in and connected to your
            // app, and response.authResponse supplies
            // the user’s ID, a valid access token, a signed
            // request, and the time the access token
            // and signed request each expire
        } else if (response.status === 'not_authorized') {
            // the user is logged in to Facebook,
            //but not connected to the app
            return 0;
        } else {
            // the user isn't even logged in to Facebook.
            return 0;
        }
    });


    // Additional initialization code here
};

$(function(){
//    $('#usernameModal').modal();
//    getUsername();
    $('#fb-login').on('click', (function (e){
        console.log("APP_ID: "+ APP_ID);
        login();
    }));
    $('#gmail-login').on('click', (function (e){
//        $('#openid-login-cont').find('.processing').removeClass('hide');
//        $('.login-button').addClass('hide');

        loginGooglePrepare();
    }));
});

function loginGooglePrepare()
{
    (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client.js?onload=loginGoogle';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
}
function loginGoogle(){
    gapi.client.setApiKey(GOOGLE_API_KEY); //set your API KEY
    gapi.client.load('plus', 'v1',function(){});//Load Google + API

    var myParams = {
        'clientid' : GOOGLE_CLIENT_ID, //You need to set client id
        'cookiepolicy' : 'single_host_origin',
        'callback' : 'loginCallback', //callback function
        'accesstype' : 'offline',
//        'approvalprompt':'force',
        'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
    };
    gapi.auth.signIn(myParams);

}
function logoutGoogle()
{
    gapi.auth.signOut();
    location.reload();
}
function loginCallback(result)
{
    var data = {};

    console.log("Login callback result: ");
    console.log(result);
    data.code = result['code'];
    if(result['status']['signed_in'])
    {
        var request = gapi.client.plus.people.get(
            {
                'userId': 'me'
            });
        request.execute(function (resp)
        {
            if(resp['emails'])
            {
                for(i = 0; i < resp['emails'].length; i++)
                {
                    if(resp['emails'][i]['type'] == 'account')
                    {
                        data.email = resp['emails'][i]['value'];
                    }
                }
                console.log('resp:');
                console.log(resp);
                data.gender =  resp['gender'];
                data.dp =  resp['image']['url'];
                data.first_name =  resp['name']['givenName'];
                data.last_name =  resp['name']['familyName'];

                check_registration(data,'google');
            }
        });
        console.log(data);
    }

}
function login() {
    FB.login(function (response) {
        if (response.authResponse) {
            FB.api('/me', function (response) {
//                access_token = FB.getAuthResponse()['accessToken'];
                var data = response;
                FB.api(
                    "/me/picture",{
                      "type": "large"
                    },
                    function (response) {
                        if (response && !response.error) {
                            /* handle the result */
                            data['dp'] = response['data']['url'];
                            console.log(data);
                            check_registration(data, 'facebook');
                        }
                    }
                );
            });

        } else {
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {scope: "email, publish_actions"});
}

function check_registration(data,type){

    $.ajax({
       type: 'POST',
        url: baseUrl+'user/user-exists',
        data: { email: data['email'], type: type},
        success: function(d){
            if(d == -999)
                alert('An error occurred');
            else if(d == 1){
                register_user(data, type, null,null);
            }else if(d == -1){
                $('#usernameModal').modal();
                getUsername(data,type);
            }else{
                login_user(data, type);
            }
        }
    });
}
function login_user(data, type){
    var processing = $('#openid-login-cont').find('.processing');
    var linkGroup = $('.login-button');

    $.ajax({
        type: 'GET',
        url: baseUrl+'user/social-login',
        data: {email: data['email']},
        beforeSend: function(){
            processing.removeClass('hide');
            linkGroup.addClass('hide');
        },
        success: function(d){
            processing.addClass('hide');
            linkGroup.removeClass('hide');
            console.log("ret: "+d);
            if(d != -1)
                window.location.replace(baseUrl+'profile/index/user/'+d);
            else
                alert('An error occurred.');
        }
    });
    return false;
}
function getUsername(data, type){
    $('#submit-username').on('click', function(){
     var errorCont = $('.field-error'),
         successCont = $('.field-success'),
         username = $('#username').val(),
         password1 = $('#up1').val(),
         password2 = $('#up2').val(),
         usernameExists = 'Username already exists. Please choose a different username.',
         emptyMessage = 'Username/Password cannot be empty.',
         noMatch = 'Passwords do not match',
         invalidUsername = 'Only letters and digits are permitted.',
         invalidPwdLen = 'Password must be at least 6 characters in length.',
         pattern = new RegExp("^[A-Za-z0-9]*$");
        if(username == '' || password1 == '' || password2 == '')
            errorCont.removeClass('hide').find('p').text(emptyMessage);
        else if(!pattern.test(username))
            errorCont.removeClass('hide').find('p').text(invalidUsername);
        else if(password1 != password2)
            errorCont.removeClass('hide').find('p').text(noMatch);
        else if(password1.length < 6)
            errorCont.removeClass('hide').find('p').text(invalidPwdLen);
        else{
            $.ajax({
                type: 'POST',
                url: baseUrl+'user/username-exists',
                data: {username: username},
                success: function(d){
                    if(d == 1){
                        errorCont.removeClass('hide').find('p').text(usernameExists);
                    }else{
                        successCont.removeClass('hide');
                        errorCont.addClass('hide').find('p').text('');
                        register_user(data,type,username, password1);
                    }
                }
            });
        }
    });
}
function register_user(data, type, username, key) {
    if(username != null)
        data['username'] = username;

    if(key != null)
        data['key'] = key;

    data['type'] = type;

    var processing = $('#openid-login-cont').find('.processing');
    var linkGroup = $('.login-button');

    $.ajax({
        type: 'POST',
        url: baseUrl+'user/register-social',
        beforeSend: function(){
            processing.removeClass('hide');
            linkGroup.addClass('hide');
        },
        data: data,
        success: function(d){
            processing.addClass('hide');
            linkGroup.removeClass('hide');
            if(d == -999)
                alert('An error occurred.');
            else
                window.location.replace(baseUrl+'u/'+d);
        }
    });
    return false;
}
function onLoadCallback(){
    console.log('gplus sdk loaded');
    gapi.client.setApiKey(GOOGLE_API_KEY); //set your API KEY
    gapi.client.load('plus', 'v1',function(){});//Load Google + API
}
(function (d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

