<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = Yii::t('app', 'Create Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <?php
            $path = (!empty($model->path)) ? 'documents/new.pdf' : 'documents/blank.pdf';
        ?>
        <div class="col-xs-12 col-md-6">
            <?= \yii2assets\pdfjs\PdfJs::widget([
                'url' => Url::base().$path,
                'height' => '625px',
                'buttons'=>[
                    'presentationMode' => false,
                    'openFile' => false,
                    'print' => false,
                    'download' => false,
                    'viewBookmark' => false,
                    'secondaryToolbarToggle' => false
                ]
            ]); ?>
        </div>
    </div>



    <?php include '_filetree_modal.php' ?>

</div>
