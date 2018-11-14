<?php

namespace app\controllers;

use app\models\Document;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimpleXMLElement;
use SplFileInfo;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SearchController extends Controller
{

    const DISK_UNIT = WEBROOT . DIRECTORY_SEPARATOR . "repository";
    const LOCAL_DISK_UNIT = 'D:\Archivo JMR Actualizado Septiembre 2017';
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','readxml','savedocument'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
        /** @var Document $document */
        $document = Document::findOne($id);

        // linux server path separator
        $document->path = str_replace('\\','/',$document->path);

        if (file_exists(self::DISK_UNIT . $document->path)){
            copy(self::DISK_UNIT . $document->path,Url::base().'documents/Documento.pdf');
        } else {
            throw new NotFoundHttpException("Pdf no encontrado. ".self::DISK_UNIT.$document->path,  404);
        }

        return $this->render('index');
    }

    public function actionReadxml(){

        set_time_limit("10000");

        ini_set('memory_limit', '4095M');

        $session = Yii::$app->session;
        //$session->removeAll();
        if (!$session->has('list')) {
            $session->set('list',$this->getDirContents('D:\Archivo JMR Actualizado Septiembre 2017\Descripciones', '/\.dc.xml/'));
        }

        $descriptions = $session->get('list');

        $i = 0;

        foreach ($descriptions as $file_path) {

            if (file_exists($file_path)) {

                try {

                    // get the pdf file of this xml
                    $file_name = str_replace(".dc.xml",".pdf", basename($file_path));

                    $file_path_descr_pdf = str_replace(DIRECTORY_SEPARATOR .basename($file_path),'.pdf', $file_path);

                    $file_path_pdf = str_replace('D:\Archivo JMR Actualizado Septiembre 2017\Descripciones',
                        'D:\Archivo JMR Actualizado Septiembre 2017\Copia PDF',$file_path_descr_pdf);

                    $desc_pdf = [
                        'description' => $file_path,
                        'pdf' => $file_path_pdf
                    ];

                    if (file_exists($file_path_pdf)) {
                        $paths['YES'][] = $desc_pdf;
                    } else {
                        $paths['NO'][] = $desc_pdf;
                    }

                    $session->set('found_list',$paths['YES']);


                } catch (\Throwable $e) {
                    echo "<pre> 2 "; print_r($e->getMessage());
                }

            }
        }

        //echo "<pre>";print_r($paths);echo "</pre>";

        $found_by_recursivity = [];

        foreach ($paths['NO'] as $path_not_found) {

            $basename = basename($path_not_found['pdf']);

            /** @var SplFileInfo $new_path */
            if ( $new_path = $this->rsearch(self::LOCAL_DISK_UNIT. DIRECTORY_SEPARATOR . 'Copia PDF', $basename)) {

                $desc_pdf = [
                    'description' => $path_not_found['description'],
                    'pdf' => $new_path->getPathname()
                ];

                $found_by_recursivity['YES'][] = $desc_pdf;

            } else {
                $found_by_recursivity['NO'][] = $path_not_found;
            }
        }

        $found_list = $session->get('found_list');

        $list_merged = array_merge($found_list,$found_by_recursivity['YES']);

        $session->set('found_list',$list_merged);

        echo "<pre>";print_r($list_merged);echo "</pre>";die;
    }




    function rsearch($folder, $pattern) {
        $iti = new RecursiveDirectoryIterator($folder);
        foreach(new RecursiveIteratorIterator($iti) as $file){
            if(strpos($file , $pattern) !== false){
                return $file;
            }
        }
        return false;
    }



    public function actionSavedocument() {

        set_time_limit("10000");

        ini_set('memory_limit', '4095M');

        try {

            $session = Yii::$app->session;

            $paths = $session->get('found_list',[]);

            foreach ($paths as $file_path) {

                $document = new Document();

                /** @var SimpleXMLElement $xml */
                $xml = simplexml_load_file($file_path['description']);

                $index = 0;
                /** @var SimpleXMLElement $value */
                foreach ($xml as $k => $value) {

                    try {
                        switch ($k) {
                            case "subject":

                                $document->{$k . "_" . $index} = $value->__toString();
                                $index++;
                                break;

                            case "date":

                                $document->full_date = $value->__toString();

                                if ($value->__toString() == '[Desconocida]' ) break;
                                $date = $value->__toString();
                                $document->place = self::getPlace($date);
                                $document->date = date(MYSQL_DATE,strtotime(self::buildMysqlDate($date)));
                                break;

                            default:
                                $document->{$k} = $value->__toString();
                                break;
                        }

                    } catch (\Throwable $e) {
                        echo "<pre> 1 "; print_r($e->getMessage());
                    }
                }

                // and save it without disk unit
                $document->path = urlencode(str_replace(self::LOCAL_DISK_UNIT,"",$file_path['pdf']));

                if (!$document->save()) {
                    echo "<pre>"; print_r($document->getErrors());
                }
            }
        } catch (\Throwable $e) {

        }
    }

    public function  getPlace(&$date)
    {
        $place = '';
        if (strpos($date, ',') !== false) {
            $exploded_date = explode(",",$date);
            $place = !empty($exploded_date[0]) ? $exploded_date[0] : "";
            $date = trim($exploded_date[1]);
        }

        return $place;
    }

    public function buildMysqlDate($date) {
        $exploded_date = explode(" ",$date);
        $day = !empty($exploded_date[0]) ? $exploded_date[0] : '01';
        $month = self::getMonth(!empty($exploded_date[2]) ? $exploded_date[2] : '01');
        $year = !empty($exploded_date[4]) ? $exploded_date[4] : '1950';
        return $year."-".$month."-".$day;
    }

    public function getMonth($month_string) {
        $month = "";
        switch (strtolower(trim($month_string))) {
            case "enero":
                $month = "01";
                break;
            case "febrero":
                $month = "02";
                break;
            case "marzo":
                $month = "03";
                break;
            case "abril":
                $month = "04";
                break;
            case "mayo":
                $month = "05";
                break;
            case "junio":
                $month = "06";
                break;
            case "julio":
                $month = "07";
                break;
            case "agosto":
                $month = "08";
                break;
            case "septiembre":
                $month = "09";
                break;
            case "octubre":
                $month = "10";
                break;
            case "noviembre":
                $month = "11";
                break;
            case "diciembre":
                $month = "12";
                break;
        }
        return $month;
    }

    function getDirContents($dir, $filter = '', &$results = array()) {
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

            if(!is_dir($path)) {
                if(empty($filter) || preg_match($filter, $path)) $results[] = $path;
            } elseif($value != "." && $value != "..") {
                $this->getDirContents($path, $filter, $results);
            }
        }

        return $results;
    }


    public function actionTest() {

        $docuemtns = (new Query())
            ->select('subject_1')
            ->from('document')
            ->groupBy('subject_1')
            ->all();

        echo "<pre>"; print_r($docuemtns); die();
    }
}
