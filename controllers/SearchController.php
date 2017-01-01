<?php

namespace app\controllers;

use app\models\Ingridient;
use app\models\Instruction;
use app\models\Recipe;
use app\models\RecipeSection;
use yii\data\ActiveDataProvider;
use \yii\helpers\Url;

class SearchController extends \yii\web\Controller
{
    public function actionSearch($s)
    {
        //search in name
        $byName = Recipe::find()->where(['like', 'name', $s]);

        //search in ingridients
        $byIngridient = Recipe::find()->where([
            'id' => Ingridient::find()->where(['like', 'name', $s])->select('recipe_id')->distinct()->column()
        ]);

        //search in desc
        $byMainDesc = Recipe::find()->where(['like', 'description', $s])->select('id')->distinct()->column();
        $byInstructionDesc = Instruction::find()->where(['like', 'instruction', $s])->select('recipe_id')->distinct()->column();
        $byDesc = Recipe::find()->where(['id' => array_merge($byMainDesc, $byInstructionDesc)]);

        \Yii::$app->view->registerMetaTag([
            'name' => 'robots',
            'content' => 'noindex,nofollow'
        ]);

        return $this->render('search', [
            's' => $s,
            'byName' => $byName,
            'byIngridient' => $byIngridient,
            'byDesc' => $byDesc,

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
