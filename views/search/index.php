<?php
use yii\helpers\Url;
?>

<button class="btn btn-primary" style="margin-bottom: 10px;" onclick="window.history.back()">
    <?= Yii::t('app', 'Go Back') ?>
</button>

<?= \yii2assets\pdfjs\PdfJs::widget([
    'url' => Url::base().'documents/Documento.pdf',
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