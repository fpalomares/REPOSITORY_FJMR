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
use yii\helpers\Url;
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
                        'actions' => ['index','checkpath','getdata','view','create','update','delete','displaydirectory'],
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
            in_array($action->id,['checkpath','getdata','view','create','update','delete','displaydirectory'])
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
        $this->layout = 'main-fluid';

        $model = new Document();

        $this->view->registerAssetBundle(FileTreeAsset::className());

        $this->view->registerJsFile(
            '@web/js/form.js',
            ['depends' => [FileTreeAsset::className()]]
        );

        if ($model->load(Yii::$app->request->post())) {

            if ( Yii::$app->request->post('valid-submit') && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
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
        $this->layout = 'main-fluid';

        $model = $this->findModel($id);

        $this->view->registerAssetBundle(FileTreeAsset::className());

        $this->view->registerJsFile(
            '@web/js/form.js',
            ['depends' => [FileTreeAsset::className()]]
        );

        if ($model->load(Yii::$app->request->post())) {

            if ( Yii::$app->request->post('valid-submit') && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
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

        if (!empty($path)) {
            $path = str_replace('\\','/',$path);

            if (file_exists(SearchController::DISK_UNIT . $path)){
                $result = "Archivo encontrado!";
            }
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


    public function actionDisplaydirectory() {

        $root = '/home/fundacio/archivo/repository/Copia PDF';

        try {

            $_POST['dir'] = $this->replaceAccents($_POST['dir']);

            $_POST['dir'] = urldecode($_POST['dir']);

            if( file_exists($root . $_POST['dir']) ) {
                $files = scandir($root . $_POST['dir']);

                natcasesort($files);
                if( count($files) > 2 ) { /* The 2 accounts for . and .. */
                    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                    // All dirs
                    foreach( $files as $file ) {
                        if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
                            echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file,0, "UTF-8") . "/\">" . htmlentities($file,0, "UTF-8") . "</a></li>";
                        }
                    }
                    // All files
                    foreach( $files as $file ) {
                        if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) ) {
                            $ext = preg_replace('/^.*\./', '', $file);
                            echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file,0, "UTF-8") . "\">" . htmlentities($file,0, "UTF-8") . "</a></li>";
                        }
                    }
                    echo "</ul>";
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function replaceAccents($string) {

        $conversion = [
            '%E1' => 'á',
            '%E9' => 'é',
            '%ED' => 'í',
            '%F3' => 'ó',
            '%FA' => 'ú',
            '%C1' => 'Á',
            '%C9' => 'É',
            '%CD' => 'Í',
            '%D3' => 'Ó',
            '%DA' => 'Ú',
            '%FC' => 'ü',
            '%DC' => 'Ü',
            '%BA' => 'º',
            '%AA' => 'ª',
            '%7E' => '~'

        ];

        foreach ($conversion as $ascii => $char) {
            $string = str_replace($ascii,$char,$string);
        }

        return $string;
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCopypdf()
    {
        $response['response'] = 'NOK';

        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            if (Yii::$app->request->isAjax) {

                if ( !empty( $path = Yii::$app->request->post('path')) ) {

                    // linux server path separator
                    $path = str_replace('\\','/',$path);

                    if (file_exists(SearchController::DISK_UNIT . $path)){
                        copy(SearchController::DISK_UNIT . $path,Url::base().'documents/new.pdf');
                    } else {
                        throw new NotFoundHttpException("Pdf no encontrado. ".SearchController::DISK_UNIT.$path,  404);
                    }
                }
            }
        } catch (\Exception $exception) {
            $response['response'] = 'NOK';
            $response['message'] = $exception->getMessage();
        }

        return $response;
    }
}
