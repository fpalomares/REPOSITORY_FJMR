$(document).ready(function () {

    var $form = $('#js-document-form');

    $("#document-place").select2({
        tags: true
    });
    $("#document-subject_0").select2({
        tags: true
    });
    $("#document-subject_1").select2({
        tags: true
    });
    $("#document-subject_2").select2({
        tags: true
    });
    $("#document-subject_3").select2({
        tags: true
    });

    $('#check-path').on('click',function () {

        $.ajax({
            data:  {
                'path' : $('#document-path').val()
            },
            url:   '/document/checkpath',
            type:  'post',
            success:  function (response) {
                alert(response);
            }
        });
    });

    $form.find('#document-subject_0').on('change',function () {

        $.ajax({
            data:  {
                'data' : $form.serialize(),
                'field' : 'subject_0'
            },
            url:   '/document/getdata',
            type:  'post',
            dataType: 'json',
            success:  function (d) {
                if ( typeof d.subject_1 !== 'undefined') {
                    $("#document-subject_1").html('').select2({
                        tags: true,
                        data: d.subject_1
                    });
                }

                if ( typeof d.subject_2 !== 'undefined') {
                    $("#document-subject_2").html('').select2({
                        tags: true,
                        data: d.subject_2
                    });
                }

                if ( typeof d.subject_3 !== 'undefined') {
                    $("#document-subject_3").html('').select2({
                        tags: true,
                        data: d.subject_3
                    });
                }
            }
        });
    });

    $form.find('#document-subject_1').on('change',function () {

        $.ajax({
            data:  {
                'data' : $form.serialize(),
                'field' : 'subject_1'
            },
            url:   '/document/getdata',
            type:  'post',
            dataType: 'json',
            success:  function (d) {

                if ( typeof d.subject_2 !== 'undefined') {
                    $("#document-subject_2").html('').select2({
                        tags: true,
                        data: d.subject_2
                    });
                }

                if ( typeof d.subject_3 !== 'undefined') {
                    $("#document-subject_3").html('').select2({
                        tags: true,
                        data: d.subject_3
                    });
                }
            }
        });
    });

    $form.find('#document-subject_2').on('change',function () {

        $.ajax({
            data:  {
                'data' : $form.serialize(),
                'field' : 'subject_2'
            },
            url:   '/document/getdata',
            type:  'post',
            dataType: 'json',
            success:  function (d) {

                if ( typeof d.subject_3 !== 'undefined') {
                    $("#document-subject_3").html('').select2({
                        tags: true,
                        data: d.subject_3
                    });
                }
            }
        });
    });

});