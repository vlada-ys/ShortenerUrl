<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="row">
        <div class="col-xs-12 col-lg-7 jumbotron">

            <?php $form = ActiveForm::begin(['action' => Url::to(['/short-urls/add'])]); ?>

            <?= $form->field($model_url, 'long_url')
                ->input('url', ['placeholder' => 'http://your-link-here.com/'])
                ->label('CREATE SHORT URL:')
            ?>
            <?= Html::label('Short url will be disabled after', 'ShortUrls[time_end]') ?>
            <?= Html::radioList(
                'ShortUrls[time_end]',
                '',
                [
                    '' => 'Never',
                    date('Y-m-d H:i:s', strtotime('+1 week')) => 'One Week',
                    date('Y-m-d H:i:s', strtotime('+1 month')) => 'One Month'
                ],

                ['tag' => 'div id="ShortUrls[time_end]"'])
            ?>
            <div class="form-group">
                <?= Html::submitButton('Shorten URL', ['class' => 'btn btn-info']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
            <div class="hidden-xs hidden-sm hidden-md col-lg-5 jumbotron">
                <p><?= 'Statistic' ?></p>
                <div class="row text-lowercase">
                    <div class="col-lg-6 text-center">
                        <p class="text-center"><?= (int) $model_url->totalUrls?></p>
                        <p class="text-center"><small><?= 'total short urls' ?></small></p>
                    </div>
                    <div class="col-lg-6 text-center">
                        <p class="text-center"><?= (int) $model_url->totalSumCounter?></p>
                        <p class="text-center"><small><?= 'total url visits' ?></small></p>
                    </div>
                </div>
            </div>

            <div class="body-content">
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table cellspacing="0" class="table table-hover">
                            <caption>
                                The last public URLs:
                            </caption>
                            <thead>
                            <tr class="text-uppercase">
                                <th><?= 'ORIGINAL URL' ?></th>
                                <th><?= 'CREATED' ?></th>
                                <th class="text-center"><?= 'CLICKS' ?></th>
                                <th class="text-center"><?= 'SHORT URL' ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($short_urls)): ?>
                                    <?php foreach ($short_urls as $url): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= Html::encode($url->long_url) ?>" target="_blank" rel="nofollow">
                                                    <?= mb_strimwidth(Html::encode("{$url->long_url}"), 0, 50, "...") ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div><?= $url->time_create ?></div>
                                            </td>
                                            <td class="text-center">
                                                <div><?= $url->counter ?></div>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?=  Url::to(['short-urls/forward', 'code' => $url->short_code]) ?>" target="_blank">
                                                    <?=  Url::to(['short-urls/f', 'c' => $url->short_code], true) ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <?= 'HERE WILL BE CREATED LINKS' ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    </div>
</div>
<?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>