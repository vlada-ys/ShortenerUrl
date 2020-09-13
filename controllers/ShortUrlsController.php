<?php

namespace app\controllers;

use Yii;
use app\models\ShortUrls;
use app\models\ShortUrlsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShortUrlsController implements the CRUD actions for ShortUrls model.
 */
class ShortUrlsController extends Controller
{
    /**
     * {@inheritdoc}
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
        ];
    }


    public function actionAdd()
    {
        $model_url = new ShortUrls();
        if (Yii::$app->request->post() && $model_url->load(Yii::$app->request->post()) && $model_url->validate()) {

            $model_url->setAttributes(
                [
                    'short_code' => $model_url->generateShortCode(),
                    'time_create' => date("Y-m-d H:i:s")
                ]
            );
            $model_url->save();
            Yii::$app->session->setFlash('success', 'New short URL created');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * method redirecting user forward to url with short_code = $code
     * @param $code
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     * @throws \yii\web\NotAcceptableHttpException
     */
    public function actionForward($code)
    {
        $url = ShortUrls::validateShortCode($code);

        $url->updateCounters(['counter' => 1]);

        return $this->redirect($url['long_url']);
    }

    /**
     * @param $code
     * @return string
     * @throws \yii\web\HttpException
     * @throws \yii\web\NotAcceptableHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionStatistic($code)
    {
        $url = ShortUrls::validateShortCode($code);

        return $this->render('statistic', [
            'url' => $url
        ]);
    }
}
