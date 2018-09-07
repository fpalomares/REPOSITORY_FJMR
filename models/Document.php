<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property string $repository
 * @property string $title
 * @property string $creator
 * @property string $date
 * @property string $full_date
 * @property string $place
 * @property string $subject_0
 * @property string $subject_1
 * @property string $subject_2
 * @property string $subject_3
 * @property string $type
 * @property string $path
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'subject_0', 'subject_1', 'subject_2', 'subject_3'], 'string'],
            [['date'], 'safe'],
            [['title','path','subject_0'],'required'],
            [['repository', 'creator', 'full_date'], 'string', 'max' => 250],
            [['place'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 45],
            [['path'], 'string', 'max' => 500],
            [['repository'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'repository' => Yii::t('app', 'Repository'),
            'title' => Yii::t('app', 'Title'),
            'creator' => Yii::t('app', 'Creator'),
            'date' => Yii::t('app', 'Date'),
            'full_date' => Yii::t('app', 'Full Date'),
            'place' => Yii::t('app', 'Place'),
            'subject_0' => Yii::t('app', 'Subject 0'),
            'subject_1' => Yii::t('app', 'Subject 1'),
            'subject_2' => Yii::t('app', 'Subject 2'),
            'subject_3' => Yii::t('app', 'Subject 3'),
            'type' => Yii::t('app', 'Type'),
            'path' => Yii::t('app', 'Path'),
        ];
    }
}
