<?php

namespace app\controllers;

use app\models\RecipeSection;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $mainCats = RecipeSection::find()->where(['parent_id' => '0'])->all();

        return $this->render('index', [
            'imageUrl' => $this->getLandingImageUrl(),
            'mainCats' => $mainCats,
        ]);
    }

    public function getLandingImageUrl()
    {
        $imgPath = \Yii::getAlias('@app') . DS .  'web' . DS .'img'.DS. 'landing' . DS . '*';
        $images = array_map(function($img){return end(explode(DS, $img));}, glob($imgPath));

        return '/web/img/landing/' . $images[array_rand($images)];
    }

}
