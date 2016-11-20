<?php
use \yii\helpers\Url;
//todo ingridients toolptip
?>


<a href="<?=Url::to(['recipe/show', 'alias' => $model->alias])?>" title="<?=$model->name?>" class="recipe-wrapper">
    <div class="main-rec-content">
        <div class="recipe-ava-wrapper">
            <?=\yii\helpers\Html::img(Url::to(['image/preview', 'id' => $model->id, 'num' => '0']))?>
        </div>
        <?=$model->name?>
        <br>
        лайки - <?=$model->likes?>
        <br>
        просмотрі - <?=$model->views?>
    </div>
    <div class="rec-ing" style="display: none">
        <table>
            <tbody>
            <?php
            foreach ($model->getIngridients()->all() as $ing) {
                echo '<tr><td>'.$ing->name.'</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

</a>
