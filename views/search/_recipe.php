<?php
use \yii\helpers\Url;
use \yii\helpers\HtmlPurifier;
?>


<a href="<?=Url::to(['recipe/show', 'alias' => $model->alias])?>" title="<?=$model->name?>" class="recipe-wrapper">
    <div class="main-rec-content">
        <div class="recipe-ava-wrapper">
            <?=\yii\helpers\Html::img(Url::to(['image/preview', 'id' => $model->id, 'num' => '0']), ['class' => 'img-rounded'])?>
        </div>
        <div class="rec-name">
            <p><?= HTMLPurifier::process($model->name) ?></p>
        </div>
        <div class="row">
            <div class="col-xs-4 time-span">
                <div><img src="/web/img/time.png" class="time-img"></div>
                <div><span><?= HTMLPurifier::process($model->cook_time) ?> хв</span></div>
            </div>
            <div class="col-xs-8 mini-description">
                <p><?= HTMLPurifier::process($model->description) ?></p>
            </div>
        </div>
    </div>
    <div class="rec-ing" style="display: none">
        <table class="table table-condensed">
            <thead>
                <tr><th>Інгрідієнти:</th></tr>
            </thead>
            <tbody>
            <?php
            foreach ($model->getIngridients()->all() as $ing) {
                echo '<tr><td>'. HTMLPurifier::process($ing->name) .'</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

</a>
