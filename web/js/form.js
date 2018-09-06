var $Form;
var $FileTreeModal;


$(function () {

    $Form = $('#js-document-form');

    $FileTreeModal = $('#js-filetree-modal');

    $Form.find("#document-place").select2({
        tags: true
    });
    $Form.find("#document-subject_0").select2({
        tags: true
    });
    $Form.find("#document-subject_1").select2({
        tags: true
    });
    $Form.find("#document-subject_2").select2({
        tags: true
    });
    $Form.find("#document-subject_3").select2({
        tags: true
    });

    $Form.find('#check-path').on('click',function () {

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

    $Form.find('#js-pick-document').on('click',function () {

        $FileTreeModal.modal('show');

        $FileTreeModal.find('#js-file-tree').fileTree({

            root: '/',
            script: '/document/displaydirectory'
            //folderEvent: 'dblclick',
            //expandSpeed: 1,
            //collapseSpeed: 1

        }, function(file) {
            //alert(file);
            $Form.find('#document-path').val(file);
            $FileTreeModal.modal('hide');
        });
    });

    $Form.find('#document-subject_0').on('change',function () {

        $.ajax({
            data:  {
                'data' : $Form.serialize(),
                'field' : 'subject_0'
            },
            url:   '/document/getdata',
            type:  'post',
            dataType: 'json',
            success:  function (d) {
                if ( typeof d.subject_1 !== 'undefined') {
                    $Form.find("#document-subject_1").html('').select2({
                        tags: true,
                        data: d.subject_1
                    });
                }

                if ( typeof d.subject_2 !== 'undefined') {
                    $Form.find("#document-subject_2").html('').select2({
                        tags: true,
                        data: d.subject_2
                    });
                }

                if ( typeof d.subject_3 !== 'undefined') {
                    $Form.find("#document-subject_3").html('').select2({
                        tags: true,
                        data: d.subject_3
                    });
                }
            }
        });
    });

    $Form.find('#document-subject_1').on('change',function () {

        $.ajax({
            data:  {
                'data' : $Form.serialize(),
                'field' : 'subject_1'
            },
            url:   '/document/getdata',
            type:  'post',
            dataType: 'json',
            success:  function (d) {

                if ( typeof d.subject_2 !== 'undefined') {
                    $Form.find("#document-subject_2").html('').select2({
                        tags: true,
                        data: d.subject_2
                    });
                }

                if ( typeof d.subject_3 !== 'undefined') {
                    $Form.find("#document-subject_3").html('').select2({
                        tags: true,
                        data: d.subject_3
                    });
                }
            }
        });
    });

    $Form.find('#document-subject_2').on('change',function () {

        $.ajax({
            data:  {
                'data' : $Form.serialize(),
                'field' : 'subject_2'
            },
            url:   '/document/getdata',
            type:  'post',
            dataType: 'json',
            success:  function (d) {

                if ( typeof d.subject_3 !== 'undefined') {
                    $Form.find("#document-subject_3").html('').select2({
                        tags: true,
                        data: d.subject_3
                    });
                }
            }
        });
    });

});