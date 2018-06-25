<?php
use yii\helpers\Url;

?>

<?= \yii2assets\pdfjs\PdfJs::widget([
    'url' => Url::base().'documents/Documento.pdf',
    'height' => '650px'
]); ?>