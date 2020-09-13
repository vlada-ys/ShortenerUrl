<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ShortUrls;

/**
 * ShortUrlsSearch represents the model behind the search form of `app\models\ShortUrls`.
 */
class ShortUrlsSearch extends ShortUrls
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'counter'], 'integer'],
            [['long_url', 'short_code', 'time_create', 'time_end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = ShortUrls::find();

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
            'time_create' => $this->time_create,
            'time_end' => $this->time_end,
            'counter' => $this->counter,
        ]);

        $query->andFilterWhere(['ilike', 'long_url', $this->long_url])
            ->andFilterWhere(['ilike', 'short_code', $this->short_code]);

        return $dataProvider;
    }
}
