<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Document;

/**
 * DocumentSearch represents the model behind the search form about `app\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['repository', 'title', 'creator', 'date', 'full_date', 'place', 'subject_0', 'subject_1', 'subject_2', 'subject_3', 'type', 'path'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Document::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'repository', $this->repository])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'creator', $this->creator])
            ->andFilterWhere(['like', 'full_date', $this->full_date])
            ->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'subject_0', $this->subject_0])
            ->andFilterWhere(['like', 'subject_1', $this->subject_1])
            ->andFilterWhere(['like', 'subject_2', $this->subject_2])
            ->andFilterWhere(['like', 'subject_3', $this->subject_3])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
