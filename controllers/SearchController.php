<?php

namespace app\controllers;

use app\models\Recipe;
use app\models\RecipeSection;
use yii\data\ActiveDataProvider;
use \yii\helpers\Url;

class SearchController extends \yii\web\Controller
{
    public function actionSearch($s)
    {

        return $this->render('search', [
            's' => $s,
        ]);
    }

    public function actionIndex()
    {
        $recipes = Recipe::find()->where(['status' => Recipe::getActiveStatuses()])->orderBy('reputation DESC');
        if(!$recipes) $this->goHome();

        //breadcrumbs
        $breadcrumbs = [];
        $recipesLink = ['label' => 'Вибір рецептів'];
        array_unshift($breadcrumbs, $recipesLink);

        //main categories
        $mainCats = RecipeSection::find()->where(['parent_id' => '0'])->all();

        $recipesProvider = new ActiveDataProvider([
                'query' => $recipes,
                'pagination'=>['pageSize'=>12]
            ]
        );

        return $this->render('index', [
            'recipesProvider' => $recipesProvider,
            'breadcrumbs' => $breadcrumbs,
            'mainCats' => $mainCats,
        ]);
    }

    public function actionCategory($alias)
    {
        $category = RecipeSection::find()->where(['alias' => $alias])->one();
        if (empty($category) || $alias !== $category->alias)
            return $this->show404();

        //breadcrumbs
        $breadcrumbs = [];
        $recipesLink = [
            'label' => 'Вибір рецептів',
            'url' => Url::to(['search/index']),
        ];
        array_push($breadcrumbs, $recipesLink);
        $parentCat = $category->getParent()->one();
        if (!empty($parentCat)) {
            $parentLink = [
                'label' => $parentCat->name,
                'url' => Url::to(['search/category', 'alias' => $parentCat->alias]),
            ];
            array_push($breadcrumbs, $parentLink);
        }
        array_push($breadcrumbs, $category->name);

        //берем категории 2го уровня
        $children = [];
        $childs = $category->getChilds()->all();
        if (!empty($childs)) $children = $childs;

        $recipes = Recipe::find()->where(['section' => $category->getChilds()->column(),'status' => Recipe::getActiveStatuses()])
            ->orderBy('reputation DESC');

        if(!$recipes) $this->goHome();


        $recipesProvider = new ActiveDataProvider([
                'query' => $recipes,
                'pagination'=>['pageSize'=>12]
            ]
        );
        return $this->render('index', [
            'recipesProvider' => $recipesProvider,
            'breadcrumbs' => $breadcrumbs,
            'children' => $children,
        ]);
    }

}
