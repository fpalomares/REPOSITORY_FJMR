<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin([
        'class' => 'js-document-form',
        'id'    => 'js-document-form'
    ]); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'creator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_date')->textInput(['maxlength' => true]) ?>

    <?php  echo $form->field($model, 'place')->dropDownList(ArrayHelper::map(\app\models\Document::find()
        ->groupBy('place')
        ->asArray()->all(), 'place', 'place'),['prompt'=>'']) ?>

    <?= $form->field($model, 'subject_0')->dropDownList(ArrayHelper::map(\app\models\Document::find()
        ->groupBy('subject_0')
        ->asArray()->all(), 'subject_0', 'subject_0'),['prompt'=>'']) ?>

    <?= $form->field($model, 'subject_1')->dropDownList(ArrayHelper::map(\app\models\Document::find()
        ->groupBy('subject_1')
        ->asArray()
        ->all(), 'subject_1', 'subject_1'),['prompt'=>'']) ?>

    <?= $form->field($model, 'subject_2')->dropDownList(ArrayHelper::map(\app\models\Document::find()
        ->groupBy('subject_2')
        ->asArray()
        ->all(), 'subject_2', 'subject_2'),['prompt'=>'']) ?>

    <?= $form->field($model, 'subject_3')->dropDownList(ArrayHelper::map(\app\models\Document::find()
        ->groupBy('subject_3')
        ->asArray()
        ->all(), 'subject_3', 'subject_3'),['prompt'=>'']) ?>

    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'path')->textInput(['maxlength' => true,'readonly'=>'readonly']) ?>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label>&nbsp;</label>
                <?= Html::button('Elige el documento',['id'=>'js-pick-document', 'class'=>'btn btn-primary', 'style' => 'width: 100%;']) ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::button('Comprobar ruta',['id'=>'check-path', 'class'=>'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
