<?php

namespace app\controllers;

use app\assets\AppAsset;
use app\assets\FileTreeAsset;
use app\models\User;
use Yii;
use app\models\Document;
use app\models\DocumentSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','checkpath','getdata','view','create','update','delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (
            !Yii::$app->user->isGuest &&
            Yii::$app->user->identity->username != 'admin' &&
            in_array($action->id,['checkpath','getdata','view','create','update','delete'])
        ) {
            throw new \Exception('No es posible realizar esta acción');
        }

        $this->view->registerAssetBundle(AppAsset::className());

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new Document();

        $this->view->registerAssetBundle(FileTreeAsset::className());

        $this->view->registerJsFile(
            '@web/js/form.js',
            ['depends' => [FileTreeAsset::className()]]
        );

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->view->registerAssetBundle(FileTreeAsset::className());

        $this->view->registerJsFile(
            '@web/js/form.js',
            ['depends' => [FileTreeAsset::className()]]
        );

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCheckpath()
    {
        $result = "Archivo no encontrado, ajusta la ruta por favor.";
        $path = Yii::$app->request->post('path','');

        if (!empty($path) && file_exists(SearchController::DISK_UNIT . $path)){
            $result = "Archivo encontrado!";
        }

        echo $result;
    }

    public function actionGetdata()
    {
        $response['subject_1'][] = ['id' => '','text' => ''];
        $response['subject_2'][] = ['id' => '','text' => ''];
        $response['subject_3'][] = ['id' => '','text' => ''];

        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (
                !empty( $serialized_data = Yii::$app->request->post('data')) &&
                !empty( $field = Yii::$app->request->post('field'))
            ) {
                parse_str($serialized_data, $post_data);
                $data = $post_data['Document'];
                $query = Document::find()
                    ->andFilterWhere(['like','subject_0',$data['subject_0']]);

                switch ($field) {

                    case 'subject_0':
                        $subject_1 = ArrayHelper::map(
                            $query
                                ->groupBy('subject_1')
                                ->asArray()
                                ->all(), 'subject_1', 'subject_1');

                        foreach ($subject_1 as $item) {
                            if (!empty($item)) {
                                $response['subject_1'][] = [
                                    'id' => $item,
                                    'text' => $item
                                ];
                            }
                        }

                        $subject_2 = ArrayHelper::map(
                            $query->groupBy('subject_2')
                                ->asArray()
                                ->all(), 'subject_2', 'subject_2');

                        foreach ($subject_2 as $item) {
                            if (!empty($item)) {
                                $response['subject_2'][] = [
                                    'id' => $item,
                                    'text' => $item
                                ];
                            }
                        }

                        $subject_3 = ArrayHelper::map(
                            $query->groupBy('subject_3')
                                ->asArray()
                                ->all(), 'subject_3', 'subject_3');

                        foreach ($subject_3 as $item) {
                            if (!empty($item)) {
                                $response['subject_3'][] = [
                                    'id' => $item,
                                    'text' => $item
                                ];
                            }
                        }

                        break;

                    case 'subject_1':

                        $subject_2 = ArrayHelper::map(
                            $query
                                ->andFilterWhere(['like','subject_1',$data['subject_1']])
                                ->groupBy('subject_2')
                                ->asArray()
                                ->all(), 'subject_2', 'subject_2');

                        foreach ($subject_2 as $item) {
                            if (!empty($item)) {
                                $response['subject_2'][] = [
                                    'id' => $item,
                                    'text' => $item
                                ];
                            }
                        }

                        $subject_3 = ArrayHelper::map(
                            $query
                                ->andFilterWhere(['like','subject_1',$data['subject_1']])
                                ->groupBy('subject_3')
                                ->asArray()
                                ->all(), 'subject_3', 'subject_3');

                        foreach ($subject_3 as $item) {
                            if (!empty($item)) {
                                $response['subject_3'][] = [
                                    'id' => $item,
                                    'text' => $item
                                ];
                            }
                        }

                        break;

                    case 'subject_2':

                        $subject_3 = ArrayHelper::map(
                            $query
                                ->andFilterWhere(['like','subject_1',$data['subject_1']])
                                ->andFilterWhere(['like','subject_2',$data['subject_2']])
                                ->groupBy('subject_3')
                                ->asArray()
                                ->all(), 'subject_3', 'subject_3');

                        foreach ($subject_3 as $item) {
                            if (!empty($item)) {
                                $response['subject_3'][] = [
                                    'id' => $item,
                                    'text' => $item
                                ];
                            }
                        }

                        break;

                    case 'subject_3':
                        break;
                }

            }
        }

        return $response;
    }
}
