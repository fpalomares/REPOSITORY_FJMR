<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = Yii::t('app', 'Update Document: ') . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="document-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php include '_filetree_modal.php' ?>

</div>
