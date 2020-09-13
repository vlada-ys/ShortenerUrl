<?php

namespace app\models;

use linslin\yii2\curl\Curl;
use Yii;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "short_urls".
 *
 * @property int $id
 * @property string $long_url
 * @property string $short_code
 * @property string $time_create
 * @property string|null $time_end
 * @property int $counter
 */
class ShortUrls extends \yii\db\ActiveRecord
{
    /**
     * Cache duration
     */
    const CACHE_DURATION = 60;

    /**
     * Allowed characters for short urls
     */
    const ALLOWED_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'short_urls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['long_url'], 'required'],
            [['long_url'], 'url'],
            [['time_create', 'time_end'], 'safe'],
            [['counter'], 'integer'],
            [['short_code'], 'string', 'max' => 6],
            [['short_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'long_url' => 'Long Url',
            'short_code' => 'Short Code',
            'time_create' => 'Time Create',
            'time_end' => 'Time End',
            'counter' => 'Counter',
        ];
    }

    /**
     * @return string
     */
    public function generateShortCode()
    {
        do {
            $shortCode = substr(str_shuffle(self::ALLOWED_CHARS), 0, 6);
        } while (self::find()->where(['short_code' => $shortCode])->one());

        return $shortCode;
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function getTotalSumCounter()
    {
        return self::getDb()->cache(
            function () {
                return ShortUrls::find()->from(self::tableName())->sum('counter');
            },
            self::CACHE_DURATION
        );
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function getTotalUrls()
    {
        return self::getDb()->cache(
            function () {
                return ShortUrls::find()->from(self::tableName())->count();
            },
            self::CACHE_DURATION
        );
    }

    /**
     * @param $code
     *
     * @return array|null|\yii\db\ActiveRecord
     * @throws HttpException
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     */
    public static function validateShortCode($code)
    {
        //check is short_code is valid
        if (!preg_match('|^[0-9a-zA-Z]{6,6}$|', $code)) {
            throw new HttpException(400, 'ENTER_VALID_SHORT_CODE');
        }

        $url = self::find()->where(['short_code' => $code])->one();

        if ($url === null) {
            throw new NotFoundHttpException('SHORT_CODE_NOT_FOUND' . $code);
        }

        //check if short code was expired
        if (null !== $url['time_end'] && date('Y-m-d H:i:s') > $url['time_end']) {
            throw new NotAcceptableHttpException('SHORT_CODE_END_TIME' . $url['time_end']);
        }

        return $url;
    }
}
