<?php

namespace app\controllers;

use app\models\Recipe;
use app\models\RecipeSection;
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
            'pagination'=>['pageSize'=>6]
        ]);

        $mainCats = RecipeSection::find()->where(['parent_id' => '0'])->all();

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
        $images = array_map(function($img){return end(explode(DS, $img));}, $imagePathes);

        return '/web/img/landing/' . $images[array_rand($images)];
    }

    public function actionMaintenance()
    {
        return $this->render('maintenance', [
            'imageUrl' => self::getLandingImageUrl(),
        ]);
    }

}
