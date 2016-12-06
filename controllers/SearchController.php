<?php

namespace app\controllers;

use app\models\Recipe;
use app\models\RecipeSection;
use yii\data\ActiveDataProvider;

class SearchController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $recipes = Recipe::find()->where(['status' => Recipe::getActiveStatuses()])->orderBy('reputation DESC');

        if(!$recipes) $this->goHome();


        $recipesProvider = new ActiveDataProvider([
                'query' => $recipes,
                'pagination'=>['pageSize'=>12]
            ]
        );

        return $this->render('index', [
            'recipesProvider' => $recipesProvider,
        ]);
    }

    public function actionCategory($alias)
    {
        $recipes = RecipeSection::find()->where(['alias' => $alias])->one()
            ->getRecipes()->where(['status' => Recipe::getActiveStatuses()])->orderBy('reputation DESC');

        if(!$recipes) $this->goHome();

        $recipesProvider = new ActiveDataProvider([
                'query' => $recipes,
                'pagination'=>['pageSize'=>12]
            ]
        );
        return $this->render('index', [
            'recipesProvider' => $recipesProvider,
        ]);
    }
}
