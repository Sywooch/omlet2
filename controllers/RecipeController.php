<?php

namespace app\controllers;

use yii\filters\AccessControl;
use app\models\Recipe;
use yii\helpers\Url;

class RecipeController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add','edit'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['add','edit'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionShow($alias)
    {
        if (!$alias) $this->goHome();
        $recipe = Recipe::findOne((int)$alias);

        if (!$recipe || $alias !== $recipe->alias)
            $this->goHome();


        return $this->render('show',[
            'recipe' => $recipe,
        ]);
    }

    public function actionEdit($alias)
    {
        if (!$alias) $this->goHome();
        $recipe = Recipe::findOne((int)$alias);
        if (!$recipe
            || ($recipe->author != \Yii::$app->user->id)
            || \Yii::$app->user->identity->is_moderator != 1
        ) $this->goHome();

        $instructions = $recipe->getInstructions()->asArray()->all();
        $ingridients = $recipe->getIngridients()->asArray()->all();
        $mainPhotoModel = new \app\models\UploadMainPhoto();
        $stepPhotoModel = new \app\models\UploadStepPhoto();

        return $this->render('edit', [
            'recipe' => $recipe,
            'sections' => \app\models\RecipeSection::getAllSections(),
            'mainPhotoModel' => $mainPhotoModel,
            'stepPhotoModel' => $stepPhotoModel,
            'instructions' => $instructions,
            'ingridients' => $ingridients,
        ]);
    }

    public function actionIndex()
    {
        $this->goHome();
    }

    public function actionAdd()
    {
        if (\Yii::$app->request->post()) {
            $recipe = new Recipe();
            $recipe->load(\Yii::$app->request->post());
            $recipe->status = Recipe::STATUS_SCRATCH;
            $recipe->author = \Yii::$app->user->id;
            $recipe->add_date = date('Y-m-d', time());
            $recipe->likes = $recipe->views = 0;

            if ($recipe->validate()) {
                $recipe->save();
                $dirPath = \Yii::getAlias('@app') . DS . 'media' . DS . $recipe->id;
                if (!file_exists($dirPath))
                    mkdir($dirPath);
                $recipe->alias = \php_rutils\RUtils::translit()->slugify($recipe->id.'-'.$recipe->name);
                $recipe->save();
                $this->redirect(Url::to(['recipe/edit', 'alias' => $recipe->alias]));
            } else {
                throw new Exeption('smth wrong with recipe save');
            }
        }
        return $this->render('add', [
            'sections' => \app\models\RecipeSection::getAllSections(),
        ]);
    }

}
