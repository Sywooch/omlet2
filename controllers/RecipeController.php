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
                'only' => ['add'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionShow()
    {
        return $this->render('show');
    }

    public function actionEdit($alias)
    {
        if (!$alias) $this->goHome();
        $recipe = Recipe::findOne((int)$alias);
        if (!$recipe) $this->goHome();
        $instructions = $recipe->getInstructions()->asArray()->all();

        $mainPhotoModel = new \app\models\UploadMainPhoto();
        $stepPhotoModel = new \app\models\UploadStepPhoto();

        return $this->render('edit', [
            'recipe' => $recipe,
            'sections' => \app\models\RecipeSection::getAllSections(),
            'mainPhotoModel' => $mainPhotoModel,
            'stepPhotoModel' => $stepPhotoModel,
            'instructions' => $instructions,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAdd()
    {
        if (\Yii::$app->request->post()) {
            $recipe = new Recipe();
            $recipe->load(\Yii::$app->request->post());
            $recipe->parent_section = \app\models\RecipeSection::findOne($recipe->section)->parent_id;
            $recipe->status = Recipe::STATUS_SCRATCH;
            $recipe->author = \Yii::$app->user->id;

            if ($recipe->validate()) {
                $recipe->save();
                mkdir(\Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $recipe->id);
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
