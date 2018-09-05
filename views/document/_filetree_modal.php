<?php
/**
 * Created by PhpStorm.
 * User: Francisco Palomares
 * Date: 05/09/2018
 * Time: 17:13
 */
?>

<div class="modal fade filetree-modal" id="js-filetree-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Selecciona el documento en el explorador</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="js-file-tree" class="file-tree col-xs-12">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>