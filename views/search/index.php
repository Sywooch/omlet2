<?php

/* @var $this yii\web\View */
use \yii\helpers\Url;

$homeLink = [
    'label' => 'Головна',
    'url' => '/',
];
echo '<br>';

echo \yii\widgets\Breadcrumbs::widget([
    'homeLink' => $homeLink,
    'links' => $breadcrumbs,

]);

//images for main categories
if (!empty($mainCats)) { ?>
    <div class="row">
        <?php
        foreach ($mainCats as $cat) { ?>
            <div class="col-xs-2 col-md-1">
                <a href="<?= Url::to(['search/category', 'alias' => $cat->alias]) ?>" title="<?= $cat->name ?>">
                    <?= $cat->getImageUrl() ?
                        '<img src="'.$cat->getImageUrl().'" title="'.$cat->name.'" alt="'.$cat->name.'" />'
                        : $cat['name'] ?>
                </a>
            </div>
        <?php } ?>
    </div>
<?php }

//subcategories
if (!empty($children)) { ?>
    <div class="row">
        <div class="col-xs-12 col-md-3">
            <table class="table table-condensed">
                <thead><tr><th>Бажаєте щось більш конкретне?</th></tr></thead>
                <tbody>
                <?php
                foreach ($children as $child) { ?>
                    <tr>
                        <td>
                            <a href="<?= Url::to(['search/category', 'alias' => $child->alias]) ?>" title="<?= $child->name ?>"><?= $child->name ?></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<div class="recipes">
    <?=\yii\widgets\ListView::widget([
        'dataProvider' => $recipesProvider,
        'itemView' => '_recipe',
        'summary' => '{count} з {totalCount} рецептів'
    ]); ?>
</div>


<script>
    $(document).ready(function(){
        $('.recipe-wrapper').hover(function(){
            $(this).find('.main-rec-content').animate({
                opacity: 0.2,
            },150);

            //ingridients
            var ings = $(this).find('.rec-ing');
            ings.css('display', 'block');
            ings.animate({
                bottom: '220px',
            },150);
        });

        $('.recipe-wrapper').mouseleave(function(){
            $(this).find('.main-rec-content').animate({
                opacity: 1,
            },150);

            //ingridients
            var ings = $(this).find('.rec-ing');
            ings.animate({
                bottom: '0px',
            },150, function(){
                ings.css('display', 'none');
            });

        });
    });
</script>
