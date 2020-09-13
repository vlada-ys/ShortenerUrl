<?php
namespace app\models;

use yii\db\ActiveRecord;


class Statistic extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statistic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_url_id'], 'required'],
            [['short_url_id'], 'integer'],
            [['date'], 'safe'],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'count' => 'Count',
            'short_url_id' => 'Short URL id',
        ];
    }
}
