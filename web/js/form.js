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

            // add subfolder
            var path = "/Copia PDF" + file;

            // replace slashes for backslashes (linux folder structure)
            path = path.replace(/\//g, '\\');

            // assing to value
            $Form.find('#document-path').val(path);


            // send request to copy file
            $.ajax({
                url: "/document/copypdf",
                type: "POST",
                data: {
                    'path' : path
                },
                success: function(data) {

                    if ( data['response'] !== undefined && data['response'] === 'OK' ) {

                        $Form.find('#valid-submit').val('0');
                        $FileTreeModal.modal('hide');
                        $Form.submit();

                    } else if (data['message'] !== undefined){
                        alert(data['message']);
                    } else {
                        alert("Something happened while selecting the pdf.");
                    }

                },
                error: function (jqXHR, exception) {
                    alert(exception)
                },
                complete:function(d){
                }
            });
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