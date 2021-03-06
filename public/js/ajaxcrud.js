    $(document).on('click', 'a.page-link', function (event) {
        event.preventDefault();
        ajaxLoad($(this).attr('href'));
    });

    $(document).on('submit', '#editFileForm', function(event) {

        event.preventDefault();
        $('.loading').show();
        var form = $(this);
        var data = new FormData(this);
        var url = form.attr("action");
        var type = form.attr("method");

        $.ajax({
            type: type,
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Status: ' + jqXHR.status);
            },
            success: function(data){
                $('.is-invalid').removeClass('is-invalid');
                if (data.fail) {
                    $('.loading').hide();
                    if(data.errors){
                        for (control in data.errors) {
                            $('#' + control).addClass('is-invalid');
                            $('#error-' + control).html(data.errors[control]);
                        }
                    }else if(data.errorParse){
                        alert(data.errorParse);
                    }
                } else {
                    $('#editFile').modal('hide');

                    //Modal Bug Fix
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    ajaxLoad(data.redirect_url);
                }
            }
        });
        //$('.loading').hide();
        return false;
    });

    //THIS GETS THE WEBPAGE AND SENDS IT TO 'ajax.blade.php' (dataType: html)
    function ajaxLoad(filename, content) {
        content = typeof content !== 'undefined' ? content : 'content';
        $('.loading').show();
        $.ajax({
            type: 'GET',
            url: filename,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#" + content).html(data);
                ajaxDivisionGenerate('division');
                $('.loading').hide();
            }
        })
    }

    function ajaxDelete(filename, token, content) {
        content = typeof content !== 'undefined' ? content : 'content';
        $('.loading').show();
        $.ajax({
            type: 'POST',
            data: {_method: 'DELETE', _token: token},
            url: filename,
            success: function() {
                $("#deleteMsg").show();

                setTimeout(function() {
                    $("#deleteMsg").slideUp(200);
                }, 2000);

                ajaxLoad('');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Status: ' + jqXHR.status);
            }
        });
    }

    function ajaxDivisionGenerate(filename) {
        $('.loading').show();
        $.ajax({
            type: 'GET',
            url: filename,
            success: function (data) {
                var division = '';

                for(var i = 0; i < data.divisions.length; i++){
                    var dataDiv = data.divisions[i].div_name;
                    division += "<option value='"+ (data.divisions[i].id) +"'>"+ dataDiv +"</option>";
                }
                $('#editDivision').append(division);
                $('.loading').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Status: ' + jqXHR.status);
            }
        });
    }

    function ajaxDivisionGenerateForSearch(filename, Division) {
        $('.loading').show();
        $.ajax({
            type: 'GET',
            url: filename,
            success: function (data) {
                var division = '';

                for(var i = 0; i < data.divisions.length; i++){
                    var dataDiv = data.divisions[i].div_name;
                    division += "<option value='"+ (data.divisions[i].id) +"'>"+ dataDiv +"</option>";
                }
                $('#division').append(division);
                $('#division').val(Division);
                console.log('division val: ' + Division);
                $('.loading').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Status: ' + jqXHR.status);
            }
        });
    }

    function ajaxDivisionGenerateForViewFiles(filename, number) {
        $('.loading').show();
        $.ajax({
            type: 'GET',
            url: filename,
            success: function (data) {
                var divisionZero = '';
                var division = '';
                var allDivision = '';
                divisionZero += "<option value=0>Can't Detect Division</option>";
                allDivision += "<option value=0>Manual</option>";
                for(var i = 0; i < data.divisions.length; i++){
                    var dataDiv = data.divisions[i].div_name;
                    division += "<option value='"+ (data.divisions[i].id) +"'>"+ dataDiv +"</option>";
                    allDivision += "<option value='"+ (data.divisions[i].id) +"'>"+ dataDiv +"</option>";
                    divisionZero += "<option value='"+ (data.divisions[i].id) +"'>"+ dataDiv +"</option>";
                    console.log(data.divisions[i].id);
                }

                $('#allDivision').append(allDivision);
                //$('#allDivision').trigger('change');

                console.log(number);

                for(var i = 0; i < number.length; i++){
                    if(number[i] == 0){
                        $('#saveDivision'+i).append(divisionZero);
                        $('#saveDivision'+i).val(0).trigger('change');
                        $('#saveDivision'+i).css({"border": "1px red solid"});
                    }else{
                        $('#saveDivision'+i).append(division);
                        $('#saveDivision'+i).val(number[i]).trigger('change');
                    }
                    console.log(number[i] + "a");
                }
                $('.loading').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Status: ' + jqXHR.status);
            }
        });
    }

    function ajaxEdit(filename) {
        $('.loading').show();
        $.ajax({
            type: 'GET',
            url: filename,
            success: function (data) {
                $('#editDivision').val(data.division);
                $('#editFileName').val(data.file.file_name);
                $('#editDate').val(data.file.date);
                $('#editFileForm').attr('action', 'update/'+data.file.id);

                $('.loading').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Status: ' + jqXHR.status);
            }
        });
    }
