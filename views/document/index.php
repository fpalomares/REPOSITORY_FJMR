<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Buscador de Documentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <?= Yii::t('app', 'Ver campos de búsqueda')?>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
        </div>
    </div>


    <?php
    //echo "<pre>"; var_dump(Yii::$app->request->get('DocumentSearch')['place']);echo "</pre>";
    //echo "<pre>"; var_dump($_GET['DocumentSearch']['place']??null);echo "</pre>";
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
            'prevPageLabel' => 'Anterior',   // Set the label for the "previous" page button
            'nextPageLabel' => 'Siguiente',   // Set the label for the "next" page button
            'firstPageLabel'=>'Primero',   // Set the label for the "first" page button
            'lastPageLabel'=>'Último',
            'maxButtonCount'=>6,

        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'repository',
            'title:ntext',
            'creator',
            'full_date',
            [
                'attribute'=>'place',
                'filter'=>ArrayHelper::map(\app\models\Document::find()
                    ->andFilterWhere(['like','title',Yii::$app->request->get('DocumentSearch')['title']])
                    ->andFilterWhere(['like','creator',Yii::$app->request->get('DocumentSearch')['creator']])
                    ->andFilterWhere(['like','full_date',Yii::$app->request->get('DocumentSearch')['full_date']])
                    ->andFilterWhere(['like','subject_0',Yii::$app->request->get('DocumentSearch')['subject_0']])
                    ->andFilterWhere(['like','subject_1',Yii::$app->request->get('DocumentSearch')['subject_1']])
                    ->andFilterWhere(['like','subject_2',Yii::$app->request->get('DocumentSearch')['subject_2']])
                    ->andFilterWhere(['like','subject_3',Yii::$app->request->get('DocumentSearch')['subject_3']])
                    ->groupBy('place')
                    ->asArray()->all(), 'place', 'place'),
            ],
            [
                'attribute'=>'subject_0',
                'filter'=>ArrayHelper::map(\app\models\Document::find()
                    ->andFilterWhere(['like','title',Yii::$app->request->get('DocumentSearch')['title']])
                    ->andFilterWhere(['like','creator',Yii::$app->request->get('DocumentSearch')['creator']])
                    ->andFilterWhere(['like','full_date',Yii::$app->request->get('DocumentSearch')['full_date']])
                    ->andFilterWhere(['like','place',Yii::$app->request->get('DocumentSearch')['place']])
                    ->andFilterWhere(['like','subject_1',Yii::$app->request->get('DocumentSearch')['subject_1']])
                    ->andFilterWhere(['like','subject_2',Yii::$app->request->get('DocumentSearch')['subject_2']])
                    ->andFilterWhere(['like','subject_3',Yii::$app->request->get('DocumentSearch')['subject_3']])
                    ->groupBy('subject_0')
                    ->asArray()->all(), 'subject_0', 'subject_0'),
            ],
            [
                'attribute'=>'subject_1',
                'filter'=>ArrayHelper::map(\app\models\Document::find()
                    ->andFilterWhere(['like','title',Yii::$app->request->get('DocumentSearch')['title']])
                    ->andFilterWhere(['like','creator',Yii::$app->request->get('DocumentSearch')['creator']])
                    ->andFilterWhere(['like','full_date',Yii::$app->request->get('DocumentSearch')['full_date']])
                    ->andFilterWhere(['like','place',Yii::$app->request->get('DocumentSearch')['place']])
                    ->andFilterWhere(['like','subject_0',Yii::$app->request->get('DocumentSearch')['subject_0']])
                    ->andFilterWhere(['like','subject_2',Yii::$app->request->get('DocumentSearch')['subject_2']])
                    ->andFilterWhere(['like','subject_3',Yii::$app->request->get('DocumentSearch')['subject_3']])
                    ->groupBy('subject_1')
                    ->asArray()
                    ->all(), 'subject_1', 'subject_1'),
            ],
            [
                'attribute'=>'subject_2',
                'filter'=>ArrayHelper::map(\app\models\Document::find()
                    ->andFilterWhere(['like','title',Yii::$app->request->get('DocumentSearch')['title']])
                    ->andFilterWhere(['like','creator',Yii::$app->request->get('DocumentSearch')['creator']])
                    ->andFilterWhere(['like','full_date',Yii::$app->request->get('DocumentSearch')['full_date']])
                    ->andFilterWhere(['like','place',Yii::$app->request->get('DocumentSearch')['place']])
                    ->andFilterWhere(['like','subject_0',Yii::$app->request->get('DocumentSearch')['subject_0']])
                    ->andFilterWhere(['like','subject_1',Yii::$app->request->get('DocumentSearch')['subject_1']])
                    ->andFilterWhere(['like','subject_3',Yii::$app->request->get('DocumentSearch')['subject_3']])
                    ->groupBy('subject_2')
                    ->asArray()
                    ->all(), 'subject_2', 'subject_2'),
            ],
            [
                'attribute'=>'subject_3',
                'filter'=>ArrayHelper::map(\app\models\Document::find()
                    ->andFilterWhere(['like','title',Yii::$app->request->get('DocumentSearch')['title']])
                    ->andFilterWhere(['like','creator',Yii::$app->request->get('DocumentSearch')['creator']])
                    ->andFilterWhere(['like','full_date',Yii::$app->request->get('DocumentSearch')['full_date']])
                    ->andFilterWhere(['like','place',Yii::$app->request->get('DocumentSearch')['place']])
                    ->andFilterWhere(['like','subject_0',Yii::$app->request->get('DocumentSearch')['subject_0']])
                    ->andFilterWhere(['like','subject_1',Yii::$app->request->get('DocumentSearch')['subject_1']])
                    ->andFilterWhere(['like','subject_2',Yii::$app->request->get('DocumentSearch')['subject_2']])
                    ->groupBy('subject_3')
                    ->asArray()
                    ->all(), 'subject_3', 'subject_3'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('Resetear','/document/index', ['class' => 'btn btn-info','p-jax'=> 1,'style' => 'padding:4px 8px !important;']),
                'template' => '{view}{myButton}',  // the default buttons + your custom button
                'buttons' => [
                    'myButton' => function($url, $model, $key) {     // render your custom button
                        /** @var $model \app\models\Document */
                        return Html::a("PDF",'/search/index?id='.$model->id,['class'=>'btn btn-sm btn-primary','style'=>'margin-left:5px']);
                    }
                ],
                'visibleButtons' => [
                    'view' => function ($model) {
                        return !\Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'admin';
                    }
                ],
                'headerOptions' => ['style' => 'width:85px'],

            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>

<p>
    <?php
    if ( !Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'admin' ) {
        echo Html::a(Yii::t('app', 'Create Document'), ['create'], ['class' => 'btn btn-success pull-right']);
    }
    ?>

</p>