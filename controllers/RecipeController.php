<?php

namespace app\controllers;

use app\models\Likes;
use app\models\SavedRecipe;
use yii\data\ActiveDataProvider;
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
        if (!$alias) return $this->show404();
        $recipe = Recipe::findOne((int)$alias);

        if (!$recipe || $alias !== $recipe->alias || !in_array($recipe->status, Recipe::getActiveStatuses()))
            return $this->show404();

        $recipe->views++;
        $recipe->last_view_date = date('Y-m-d', time());
        $recipe->save();
        //breadcrumbs
        $breadcrumbs = [];

        $cat = $recipe->getSection()->one();
        if (!empty($cat)) {
            $parentLink = [
                'label' => $cat->name,
                'url' => Url::to(['search/category', 'alias' => $cat->alias]),
            ];
            array_push($breadcrumbs, $parentLink);
        }
        $catP = $cat->getParent()->one();
        if (!empty($catP)) {
            $parentLink = [
                'label' => $catP->name,
                'url' => Url::to(['search/category', 'alias' => $catP->alias]),
            ];
            array_push($breadcrumbs, $parentLink);
        }
        array_push($breadcrumbs, $recipe->name);

        $liked = $saved = false;
        if (!\Yii::$app->user->isGuest) {
            $alreadySaved = SavedRecipe::find()->where(['recipe_id' => $recipe->id, 'user_id' => \Yii::$app->user->identity->id])->one();
            if (!empty($alreadySaved)) $saved = true;

            $alreadyLiked = Likes::find()->where(['recipe_id' => $recipe->id, 'user_id' => \Yii::$app->user->identity->id])->one();
            if (!empty($alreadyLiked)) $liked = true;
        }

        return $this->render('show',[
            'recipe' => $recipe,
            'breadcrumbs' => $breadcrumbs,
            'liked' => $liked,
            'saved' => $saved,
            'category' => $cat->name,
        ]);
    }

    public function actionEdit($alias)
    {
        if (!$alias) return $this->show404();
        $recipe = Recipe::findOne((int)$alias);

        if (!$recipe
            || ($recipe->author != \Yii::$app->user->id)
        ) {
            if (\Yii::$app->user->identity->role !== \app\models\User::ADMIN_ROLE)
                return $this->show404();
        }

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
