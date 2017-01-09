<?php

namespace app\controllers;

use app\models\Recipe;
use app\models\RecipeSection;
use php_rutils\Translit;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        $recipes = Recipe::find()->where(['status' => Recipe::getActiveStatuses()])
            ->orderBy('reputation DESC')->limit(6);


        $recipesProvider = new ActiveDataProvider([
            'query' => $recipes,
            'totalCount' => 6,
            'pagination'=>[
                'pageSize'=>6,
            ],
        ]);

        $mainCats = RecipeSection::find()->where(['parent_id' => '0'])->all();

        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => \Yii::$app->settings->get('seo', 'mainPage-description', 'Omlet - Поиск рецептов')
        ]);

        return $this->render('index', [
            'imageUrl' => self::getLandingImageUrl(),
            'mainCats' => $mainCats,
            'recipesProvider' => $recipesProvider,
        ]);
    }

    public static function getLandingImageUrl()
    {
        $imgPath = \Yii::getAlias('@app') . DS .  'web' . DS .'img'.DS. 'landing' . DS . '*';
        $imagePathes = glob($imgPath);

        $images = [];
        foreach ($imagePathes as $path) {
            $pathBlocks = explode(DS, $path);
            $images[] = end($pathBlocks);
        }

        return '/web/img/landing/' . $images[array_rand($images)];
    }

    public function actionMaintenance()
    {
        return $this->render('maintenance', [
            'imageUrl' => self::getLandingImageUrl(),
        ]);
    }

    public function actionSitemap()
    {
        $t = new Translit();
        $cats = RecipeSection::find()->all();
        foreach ($cats as $cat) {
            $cat->alias = $t->slugify($cat->name);
            $cat->save();
        }
        return $this->render('sitemap', [
            'categories' => RecipeSection::find()->where([
                'id' => Recipe::find()->select('section')->distinct()->column()
            ])->asArray()->all(),
            'recipes' => Recipe::find()->asArray()->all(),
        ]);
    }

}
