<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-sm-6">
            <?php //$form->field($model, 'id') ?>

            <?php //$form->field($model, 'repository') ?>

            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'creator') ?>

            <?php echo $form->field($model, 'full_date') ?>

            <?php //$form->field($model, 'date') ?>

            <?php  echo $form->field($model, 'place')->dropDownList(ArrayHelper::map(\app\models\Document::find()
                ->groupBy('place')
                ->asArray()->all(), 'place', 'place'),['prompt'=>'']) ?>

        </div>

        <div class="col-sm-6">


            <?php echo $form->field($model, 'subject_0')->dropDownList(ArrayHelper::map(\app\models\Document::find()
                ->groupBy('subject_0')
                ->asArray()->all(), 'subject_0', 'subject_0'),['prompt'=>'']) ?>

            <?php echo $form->field($model, 'subject_1')->dropDownList(ArrayHelper::map(\app\models\Document::find()
                ->groupBy('subject_1')
                ->asArray()
                ->all(), 'subject_1', 'subject_1'),['prompt'=>'']) ?>

            <?php echo $form->field($model, 'subject_2')->dropDownList(ArrayHelper::map(\app\models\Document::find()
                ->groupBy('subject_2')
                ->asArray()
                ->all(), 'subject_2', 'subject_2'),['prompt'=>'']) ?>

            <?php echo $form->field($model, 'subject_3')->dropDownList(ArrayHelper::map(\app\models\Document::find()
                ->groupBy('subject_3')
                ->asArray()
                ->all(), 'subject_3', 'subject_3'),['prompt'=>'']) ?>

            <?php // echo $form->field($model, 'type') ?>

            <?php // echo $form->field($model, 'path') ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
