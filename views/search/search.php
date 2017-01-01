<?php
use \yii\helpers\HtmlPurifier;
use \yii\widgets\ListView;
use \yii\data\ActiveDataProvider;

$this->title = $s . ' - пошук рецептів на ' . HOST;

?>

<?php
if ($byName->count() == 0 && $byIngridient->count() == 0 && $byDesc->count() == 0) { ?>
    <div class="row">
        <p style="font-size: 20px">
            Щось нічого не знайшлось ...<br>
            Точно шукаємо <span class="searchness"><?=  HTMLPurifier::process($s)?></span> ?
        </p>
    </div>
<?php } else { ?>
    <div class="row">
        <p style="font-size: 20px">
            Знайденo по <span class="searchness"><?= HtmlPurifier::process($s)?></span> :
        </p>
    </div>
<?php }

if ($byName->count() != 0) { ?>
    <div class="row">
        <div class="col-xs-12">
            <p style="font-size: 20px">
                Рецепти :
            </p>
        </div>
    </div>
    <div class="row">
        <?=  ListView::widget([
            'dataProvider' => new ActiveDataProvider(['query' => $byName]),
            'itemView' => '..'.DS.'search'.DS.'_recipe',
            'summary' => ''
        ])?>
    </div>
<?php }

if ($byIngridient->count() != 0) { ?>
    <div class="row">
        <div class="col-xs-12">
            <p style="font-size: 20px">
                Знайднео в інгрідієнтах:
            </p>
        </div>
    </div>
    <div class="row">
        <?=  ListView::widget([
            'dataProvider' => new ActiveDataProvider(['query' => $byIngridient]),
            'itemView' => '..'.DS.'search'.DS.'_recipe',
            'summary' => ''
        ])?>
    </div>
<?php }
if ($byDesc->count() != 0) { ?>
    <div class="row">
        <div class="col-xs-12">
            <p style="font-size: 20px">
                Знайднео по опису:
            </p>
        </div>
    </div>
    <div class="row">
        <?=  ListView::widget([
            'dataProvider' => new ActiveDataProvider(['query' => $byDesc]),
            'itemView' => '..'.DS.'search'.DS.'_recipe',
            'summary' => ''
        ])?>
    </div>
<?php }

