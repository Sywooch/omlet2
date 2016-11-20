<?php
/* @var $this yii\web\View */

?>
<p>хлебные крошки</p>

<?php
if (\Yii::$app->controller->action->id == 'index'){ ?>
    <p>выбор категорий</p>

<?php } else { ?>
    <p>выбор drugoi category</p>

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
