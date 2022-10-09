$(function(){
    var uit = $('#user-info-table'),
        nc = $('.notes');

    $('.notes-form').find('#cover_image').on('change', function () {
        $('.notes-form').find('#file_changed').val(1);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) {
            return;
        } // no file selected, or no FileReader support

        if (/^image/.test(files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function () { // set image data as background of div
                $("#preview-image").css({"background-image":"url(" + this.result + ")", 'height': '300px', 'background-size': 'cover'});
            };
        }
    });

    uit.find('.block_user').on('click', function (e) {
        e.preventDefault();
        var button = $(this),
            row  = button.parent().parent(),
            uid  = parseInt(row.data('id')),
            mode = button.data('mode').toLowerCase();

        if(typeof uid === undefined || uid === 0){
            alert("Something went wrong!");
            return false;
        }

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/block-user',
            data: {uid: uid, mode: mode},
            success: function (data) {
                d = JSON.parse(data);
                console.log(data);
                if(d.status == 1){
                    if(mode == 'block'){
                        row.addClass('bg-danger');
                        button.text('Unblock');
                        button.data('mode','Unblock');
                        console.log("mode block");
                    }
                    else{
                        row.removeClass('bg-danger');
                        button.text('block');
                        button.data('mode', 'block');
                        console.log("mode unblock");
                    }
                }
            }
        });

    });
    uit.find('.verify_user').on('click', function (e) {
        e.preventDefault();
        var button = $(this),
            row  = button.parent().parent(),
            uid  = parseInt(row.data('id')),
            mode = button.data('mode').toLowerCase();

        if(typeof uid === undefined || uid === 0){
            alert("Something went wrong!");
            return false;
        }

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/verify-user',
            data: {uid: uid, mode: mode},
            success: function (data) {
                d = JSON.parse(data);
                console.log(data);
                if(d.status == 1){
                    if(mode == 'verify'){
                        row.addClass('bg-warning');
                        button.text('Unverify');
                        button.data('mode', 'Unverify');
                        console.log("mode verify");
                    }
                    else{
                        row.removeClass('bg-warning');
                        button.text('verify');
                        button.data('mode', 'verify');
                        console.log("mode unverify");
                    }
                }
            }
        });

    });

    $('#year-sort-btn').on('click',function(){
       var year = $(this).next('select').val();
       uit.addClass('hide');
       $('#pagination').addClass('hide');
        getUsersByYear(year);
    });
    $('#all-sort-btn').on('click',function(){
       $('#user-info-table').removeClass('hide');
       $('#year-sort-table').addClass('hide');
        $('#pagination').removeClass('hide');
    });

    //Add Trivia
    $('#add-trivia').find('button').on('click',function(e){
        e.preventDefault();
        addTrivia($(this));
    });

    //Update Trivia
    $('#update-trivia').find('button').on('click',function(e){
        e.preventDefault();
        updateTrivia($(this));
    });

    //Delete Trivia
    $('.trivia').find('.delete-trivia').on('click',function(e){
        deleteTrivia($(this));
    });

    //Delete Subject
    $('.subjects').find('.delete-subject').on('click',function(e){
        deleteSubject($(this));
    });

    //Delete Note
    nc.find('.delete-note').on('click',function(e){
        deleteNote($(this));
    });

    //Delete Notes Content
    nc.find('.delete-notes-content').on('click',function(e){
        deleteNotesContent($(this));
    });

    //Delete Notes Category
    $('.notes_category').find('.delete-ncat').on('click',function(e){
        deleteNotesCategory($(this));
    });

    //Delete Notes SubCategory
    $('.notes_sub_category').find('.delete-nscat').on('click',function(e){
        deleteNotesSubCategory($(this));
    });

    //Delete Java Question
    $('.java-questions').find('.delete-question').on('click',function(e){
        deleteJavaQuestion($(this));
    });

    //Delete Riddle
    $('.riddles').find('.delete-riddle').on('click',function(e){
        deleteRiddle($(this));
    });


    //Add Quote
    $('#add-quote-form').find('button').on('click',function(e){
        e.preventDefault();
        addQuote($(this));
    });
    //Update quote
    $('#update-quote-form').find('button').on('click',function(e){
        e.preventDefault();
        updateQuote($(this));
    });
    //Delete Quote
    $('.quotes').find('.delete-quote').on('click',function(e){
        deleteQuote($(this));
    });

    var fs = $('.form_short');

    fs.find('#heading').on('keyup', function (e) {
        var ch = $(this).val(),
            slug = $(this).val().replace(/\W/g, "-");
        slug = slug.replace(/-+/g,'-');
        $('#slug').val(slug.toLowerCase());
    });

    fs.find('#topic').on('keyup', function (e) {
        var ch = $(this).val(),
            slug = $(this).val().replace(/\W/g, "-");
        slug = slug.replace(/-+/g,'-');
        $('#url_name').val(slug.toLowerCase());
    });


});

function getUsersByYear(year){
    $.ajax({
        type: 'GET',
        url:  baseUrl+'admin/get-users-by-year',
        data: {year: year},
        success: function(data){
            var table = $('#year-sort-table');
            var rows ="";
            data = JSON.parse(data);
            $.each(data, function(index, element) {
                console.log(index+":"+element['total']+","+element['month']+'\n');
                rows += "<tr>";
                rows += "<td>"+(index+1)+"</td>";
                rows += "<td>"+element['month']+"</td>";
                rows += "<td>"+element['total']+"</td>";
                rows += "</tr>";
            });
            table.find('tbody').empty().append(rows);
            table.removeClass('hide');
        }
    });
}

function addTrivia(e){
    var button   = e,
        fs       = $('.form_short'),
        loading  = button.next('.loading'),
        value    = button.parent().find('#trivia'),
        success  = fs.find('.alert-success'),
        failure  = fs.find('.alert-danger');

    if(value.val() == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/create-trivia',
            data: {trivia: value.val()},
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1){
                    $('#trivia').focus();
                    success.removeClass('hide').animate({'opacity': 0}, 3000, function(){ $(this).addClass('hide').css('opacity', 1)});
                    value.val('');
                }
                else
                    failure.removeClass('hide').animate({'opacity': 0}, 3000, function(){ $(this).addClass('hide').css('opacity', 1)});
            },
            error: function(){
                failure.removeClass('hide').animate({'opacity': 0}, 3000, function(){ $(this).addClass('hide').css('opacity', 1)});
            }
        });
    }

}
function updateTrivia(e){
    var button  = e,
        fs      = $('.form_short'),
        id      = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading'),
        value   = button.parent().find('#trivia');

    if(value.val() == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/edit-trivia',
            data: {trivia: value.val(), id: id},
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1)
                    fs.find('.alert-success').removeClass('hide');
                else
                    fs.find('.alert-danger').removeClass('hide');
            }
        });
    }
}
function deleteTrivia(e){
    var button = e,
        id     = button.next('input[type="hidden"]').val(),
        row    = button.parent().parent();

    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else{
        if(confirm("Are you sure you want to delete this item?")){
            $.ajax({
                type: 'POST',
                url: baseUrl+'admin/delete-trivia',
                data: {id: id},
                beforeSend: function(){
                    button.text('Please wait...');
                },
                success: function(d){
                    if(d == 1){
                        row.animate({'opacity':0.0}, 2000, function(){
                            row.remove();
                        });
                    }else{
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function addQuote(e){
    var button  = e,
        form    = $('#add-quote-form'),
        fs      = $('.form_short'),
        loading = button.next('.loading'),
        quote   = button.parent().find('#quote'),
        author  = button.parent().find('#author'),
        success = fs.find('.alert-success'),
        failure = fs.find('.alert-danger');

    if(quote.val() == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/create-quote',
            data: form.serialize(),
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1){
                    success.removeClass('hide').animate({'opacity': 0}, 3000, function(){ $(this).addClass('hide').css('opacity', 1)});
                    quote.val('');
                    author.val('');
                }else
                    failure.removeClass('hide').animate({'opacity': 0}, 3000, function(){ $(this).addClass('hide').css('opacity', 1)});
            },
            error: function(){
                failure.removeClass('hide').animate({'opacity': 0}, 3000, function(){ $(this).addClass('hide').css('opacity', 1)});
            }
        });
    }
}
function updateQuote(e){
    var button  = e,
        form    = $('#update-quote-form'),
        fs      = $('.form_short'),
        loading = button.next('.loading'),
        quote   = button.parent().find('#quote'),
        author  = button.parent().find('#author');

    if(quote.val() == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/edit-quote',
            data: form.serialize(),
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1)
                    fs.find('.alert-success').removeClass('hide');
                else
                    fs.find('.alert-danger').removeClass('hide');
            }
        });
    }
}
function deleteQuote(e){
    var button  = e,
        id      = button.next('input[type="hidden"]').val(),
        row     = button.parent().parent();

    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-quote',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (d == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}


function deleteJavaQuestion(e){
    var button  = e,
        id      = button.next('input[type="hidden"]').val(),
        row     = button.parent().parent();

    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-java-question',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}


function deleteRiddle(e){
    var button = e;
    var id = button.next('input[type="hidden"]').val();
    var row = button.parent().parent();
    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-riddle',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function deleteNotesCategory(e){
    var button = e;
    var id = button.next('input[type="hidden"]').val();
    var row = button.parent().parent();
    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-notes-category',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function deleteNotesSubCategory(e){
    var button = e;
    var id = button.next('input[type="hidden"]').val();
    var row = button.parent().parent();
    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-notes-sub-category',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function deleteNote(e){
    var button = e;
    var id = button.parent().find('input[type="hidden"]').val();
    var row = button.parent().parent();
    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-note',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function deleteNotesContent(e){
    var button = e;
    var id = button.parent().find('input[type="hidden"]').val();
    var row = button.parent().parent();
    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-notes-content',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function deleteSubject(e){
    var button = e;
    var id = button.next('input[type="hidden"]').val();
    var row = button.parent().parent();
    if(id == undefined || id == ''){
        alert("Something went wrong.");
    }else {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                type: 'POST',
                url: baseUrl + 'admin/delete-subject',
                data: {id: id},
                beforeSend: function () {
                    button.text('Please wait...');
                },
                success: function (d) {
                    if (parseInt(d) == 1) {
                        row.animate({'opacity': 0.0}, 2000, function () {
                            row.remove();
                        });
                    } else {
                        button.text('Delete');
                        alert('An error occured!');
                    }
                }
            });
        }
    }
}

function updateRiddle(e){
    var button = $(e),
        id = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading'),
        riddle = $('#riddle').val().trim(),
        answer = $('#answer').val().trim();

    if(riddle == '' || answer == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/edit-riddle',
            data: {riddle: riddle, answer: answer, id: id},
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1)
                    $('.form_short').find('.alert-success').removeClass('hide');
                else
                    $('.form_short').find('.alert-danger').removeClass('hide');
            }
        });
    }
}

function updateNotesCategory(e){
    var button = $(e),
        id = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading'),
        category = $('#category').val().trim(),
        type = $('#type').val().trim();

    if(category == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/edit-notes-category',
            data: {id: id, category: category, type: type},
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1)
                    $('.form_short').find('.alert-success').removeClass('hide');
                else
                    $('.form_short').find('.alert-danger').removeClass('hide');
            }
        });
    }
}


function updateNotesSubCategory(e) {
    var button = $(e),
        id = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading'),
        categoryId = parseInt($('#category_id').val()),
        subCategory = $('#sub_category').val().trim();

    if (subCategory == '')
        alert('Please enter some data');
    else {

        $.ajax({
            type: 'POST',
            url: baseUrl + 'admin/edit-notes-sub-category',
            data: {id: id, category_id: categoryId, sub_category: subCategory},
            beforeSend: function () {
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function (d) {
                button.removeClass('hide');
                loading.addClass('hide');
                if (d == 1)
                    $('.form_short').find('.alert-success').removeClass('hide');
                else
                    $('.form_short').find('.alert-danger').removeClass('hide');
            }
        });
    }
}

function updateSubject(e){
    var button = $(e),
        id = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading'),
        name = $('#name').val(),
        url_name = $('#url_name').val().trim(),
        visible = $("#visible").is(":checked");

    console.log(id+":"+name+":"+url_name);
    if(name == '' || url_name == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/edit-subject',
            data: {id: id, name: name, url_name: url_name, visible: visible},
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1)
                    $('.form_short').find('.alert-success').removeClass('hide');
                else
                    $('.form_short').find('.alert-danger').removeClass('hide');
            }
        });
    }
}

function updateTopic(e){
    var button = $(e),
        id = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading'),
        topic = $('#topic').val(),
        url_name = $('#url_name').val().trim(),
        visible = $("#visible").is(":checked");

    if(topic == '' || url_name == '')
        alert('Please enter some data');
    else{

        $.ajax({
            type: 'POST',
            url: baseUrl+'admin/edit-topic',
            data: {id: id, topic: topic, url_name: url_name, visible: visible},
            beforeSend: function(){
                button.addClass('hide');
                loading.removeClass('hide');
            },
            success: function(d){
                button.removeClass('hide');
                loading.addClass('hide');
                if(d == 1)
                    $('.form_short').find('.alert-success').removeClass('hide');
                else
                    $('.form_short').find('.alert-danger').removeClass('hide');
            }
        });
    }
}


function updateYearlyQuestion(e){
    var button = $(e),
        //id = button.prev('input[type="hidden"]').val(),
        loading = button.next('.loading');

    $.ajax({
        type: 'POST',
        url: baseUrl+'admin/update-yearly-question',
        data: $('#yqForm').serialize(),
        success: function(data){
            button.removeClass('hide');
            loading.addClass('hide');
            if(data == 1)
                $('.form_short').find('.alert-success').removeClass('hide');
            else
                $('.form_short').find('.alert-danger').removeClass('hide');
        }
    });
}

