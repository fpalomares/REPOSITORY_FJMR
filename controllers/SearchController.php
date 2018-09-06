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
                        'actions' => ['index','readxml'],
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
            //throw new NotFoundHttpException("Pdf no encontrado. ".self::DISK_UNIT.$document->path,  404);
        }

        return $this->render('index');
    }

    public function actionReadxml(){

        set_time_limit("10000");

        $session = Yii::$app->session;
        //$session->removeAll();
        if (!$session->has('list')) {
            $session->set('list',$this->getDirContents('E:\Archivo JMR Actualizado Septiembre 2017\Descripciones', '/\.dc.xml/'));
        }

        foreach ($session->get('list') as $file_path) {

            $document = new Document();

            if (file_exists($file_path)) {

                try {

                    // get the pdf file of this xml
                    $file_name = str_replace(".dc.xml",".pdf", basename($file_path));

                    // get the pdf file found in COPIA PDF folder
                    /** @var SplFileInfo $pdf_path_object */
                    $pdf_path_object = self::rsearch("E:\Archivo JMR Actualizado Septiembre 2017\Copia PDF",$file_name);

                    // not found file, continue next iteration
                    if (empty($pdf_path_object)) {
                        echo "<pre>"; echo "not found:".$file_name;
                        continue;
                    }

                    // and save it without disk unit
                    $document->path = str_replace(self::DISK_UNIT,"",$pdf_path_object->getPathname());

                    /*
                    $file_name = DIRECTORY_SEPARATOR.basename($file_path);
                    // path to pdf folder
                    $document->path = str_replace('E:\Archivo JMR Actualizado Septiembre 2017\Descripciones','\Archivo JMR Actualizado Septiembre 2017\Copia PDF',$file_path);

                    if (!file_exists(self::DISK_UNIT . $document->path)){
                        $document->path = str_replace($file_name,'.pdf', $document->path);
                    } else {
                        echo "<pre>"; print_r("entro!");
                    }*/

                    /** @var SimpleXMLElement $xml */
                    $xml = simplexml_load_file($file_path);

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
                            echo "<pre>"; print_r($e->getMessage());
                        }
                    }

                    $document->save();

                } catch (\Throwable $e) {

                }

                //return $this->render('index', []);

            } else {
                exit('Failed to open xml.');
            }
        }


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
