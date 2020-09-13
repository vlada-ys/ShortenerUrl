<?php

namespace app\controllers;

use app\models\ShortUrls;
use yii\web\Controller;
use yii\data\Pagination;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model_url = new ShortUrls();
        $query = ShortUrls::find();


        $pagination = new Pagination([
            'defaultPageSize' => 25,
            'totalCount' => $query->count(),
        ]);

        $short_urls = $query->addOrderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'short_urls' => $short_urls,
            'model_url' => $model_url,
            'pagination' => $pagination,
        ]);
    }
}
