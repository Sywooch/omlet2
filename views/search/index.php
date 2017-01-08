<?php

/* @var $this yii\web\View */
use \yii\helpers\Url;
use \yii\helpers\HtmlPurifier;

$this->title = !empty($category->title) ? $category->title :
    (!empty($category->name) ?
        $category->name . \Yii::$app->settings->get('seo', 'categoryPage-title') :
        \Yii::$app->settings->get('seo', 'mainCategoryPage-title'));

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
    <br>
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
<h1>
    <?= !empty($category->h1) ?
        $category->h1 :
        (!empty($category->name) ?
        $category->name . \Yii::$app->settings->get('seo', 'categoryPage-h1', '') :
            \Yii::$app->settings->get('seo', 'mainCategoryPage-h1', '')) ?>
</h1>
<div class="recipes">
    <?=\yii\widgets\ListView::widget([
        'dataProvider' => $recipesProvider,
        'itemView' => '_recipe',
        'summary' => '{count} з {totalCount} рецептів'
    ]); ?>
</div>
<?php

if ($showSeoTextCondition) {
    $text = !empty($category) ? $category->seo_text : \Yii::$app->settings->get('seo', 'mainCategoryPage-seo');
    if (!empty($text))
        echo "<p class='seo-text'>" . HTMLPurifier::process($text) . "</p>";
}
