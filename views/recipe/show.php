<?php
use \yii\helpers\Html;
use \yii\helpers\Url;
/**
 * @var $recipe app\models\Recipe
 *
 *
 *
 */
$this->title = $recipe->name . \Yii::$app->settings->get('seo', 'recipeTitle');

$homeLink = [
    'label' => 'Головна',
    'url' => '/',
];
echo '<br>';

echo \yii\widgets\Breadcrumbs::widget([
    'homeLink' => $homeLink,
    'links' => $breadcrumbs,

]);
?>
    <div id="recipe" itemscope itemtype="//schema.org/Recipe">
        <span itemprop="recipeCategory" style="display: none"><?= $category ?></span>
        <div class="row"><h1 itemprop="name" style="text-align: center"><?= $recipe->name ?></h1></div>

        <div class="row">
            <div class="col-xs-12 col-md-6 recipe-ava-wrapper" id="main-image">
                <img  itemprop="image" src="<?= \yii\helpers\Url::to(['image/recipe', 'id' => $recipe->id, 'num' => 0]) ?>" title="<?= $recipe->name ?>" alt="<?= $recipe->name ?>" class="img-rounded"/>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="container">
                    <div class="row" id="nav-buttons">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-<?= $liked ? 'primary' : 'default' ?> btn-xs" title="Like!" id="like">
                                <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-<?= $saved ? 'primary' : 'default' ?> btn-xs" title="Зберегти" id="save">
                                <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
                            </button>
                            <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                            <script src="//yastatic.net/share2/share.js"></script>
                            <div class="ya-share2" data-services="vkontakte,facebook,twitter" style="display: inline-block; margin-top: 2px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <table class="table table-condensed">
                                <thead>
                                <tr><th>Інгрідієнти:</th></tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($recipe->getIngridients()->all() as $ing) {
                                    echo '<tr><td itemprop="ingredients">'.$ing->name.'</td></tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <p class="author-desc">
                                <a itemprop="author"
                                   href="<?= Url::to(['cabinet/profile', 'email' => $recipe->getAuthor()->one()->email]) ?>">
                                    Від автора:
                                </a>
                            </p>
                            <p itemprop="description"><?= $recipe->description ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <p style="font-size: 30px;text-align: center; font-weight: bold">
                <span itemprop="totalTime" style="display: none">PT<?= $recipe->cook_time?>M</span>
                Як готувати? (<?= $recipe->cook_time?> хв)
            </p>
        </div>

            <?php
            $steps = $recipe->getInstructions()->all();
            foreach ($steps as $step) {
                if ($step->step == 1 || ($step->step - 1) % 3 === 0)
                    echo '<div class="row">';
                ?>
                <div class="col-xs-12 col-md-4 instruction">
                    <div>
                        <?php
                        $imagePath = \Yii::getAlias('@app') . DS .  'media' . DS .  $recipe->id . DS . $step->step . '.jpg';
                        $imageUrl = false;
                        if (file_exists($imagePath)) {
                            $imageUrl = Url::to(['image/preview', 'id' => $recipe->id, 'num' => $step->step]);
                            echo Html::img($imageUrl, [
                                'title' => 'приготування '.$recipe->name,
                                'alt' => 'приготування '.$recipe->name,
                                'class' => 'img-rounded img-responsive',
                            ]);
                        }
                        ?>
                        <div class="step-id">
                            <img src="/web/img/step.png">
                            <span><?= $step->step ?></span>
                        </div>
                        <div class="step-ins">
                            <p  itemprop="recipeInstructions"><?=$step->instruction?></p>
                        </div>
                    </div>
                </div>
            <?php

            if ($step->step % 3 === 0)
            echo '</div>';
            }
            if (count($steps) % 3 !== 0)
                echo '</div>';
            ?>

        <div class="row">
            <div id="disqus_thread"></div>
            <script>
                /**
                 * RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                 * LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                 */
                /*
                 var disqus_config = function () {
                 this.page.url = PAGE_URL; // Replace PAGE_URL with your page's canonical URL variable
                 this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                 };
                 */
                (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');
                    s.src = '//omletdiplom.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
        </div>

        <script>
            $(document).ready(function(){
                <?php
                if (\Yii::$app->user->isGuest) { ?>
                $('#like, #save').click(function(){
                    window.location = '<?= Url::to(['cabinet/login']); ?>';
                });
                <?php } else { ?>
                $('#like').click(function(){
                    $.ajax({
                        url:'<?= Url::to(['ajax/like'])?>',
                        method:'POST',
                        data:{recipeId : '<?= $recipe->id ?>'},
                    });
                    $(this).toggleClass('btn-default btn-primary');
                });

                $('#save').click(function(){
                    $.ajax({
                        url:'<?= Url::to(['ajax/save'])?>',
                        method:'POST',
                        data:{recipeId : '<?= $recipe->id ?>'},
                    });
                    $(this).toggleClass('btn-default btn-primary');
                });
                <?php } ?>

            });
        </script>






