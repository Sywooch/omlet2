<?php

namespace app\controllers;

use app\models\Ingridient;
use app\models\Instruction;
use app\models\Likes;
use app\models\Recipe;
use app\models\RecipeSection;
use app\models\SavedRecipe;
use yii\helpers\Url;

class AjaxController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->request->isAjax)
            die('nafig');

        return parent::beforeAction($action);
    }

    public function actionLike()
    {
        if (!isset($_POST['recipeId']) || \Yii::$app->user->isGuest) die();

        $alreadyLike = Likes::find()->where(['recipe_id' => (int)$_POST['recipeId'], 'user_id' => \Yii::$app->user->identity->id])->one();
        if (!empty($alreadyLike)) {
            $alreadyLike->delete();
            die();
        }

        $like = new Likes();
        $like->recipe_id = (int)$_POST['recipeId'];
        $like->user_id = \Yii::$app->user->identity->id;
        $like->save();
        die();
    }

    public function actionSave()
    {
        if (!isset($_POST['recipeId']) || \Yii::$app->user->isGuest) die();

        $alreadyLike = SavedRecipe::find()->where(['recipe_id' => (int)$_POST['recipeId'], 'user_id' => \Yii::$app->user->identity->id])->one();
        if (!empty($alreadyLike)) {
            $alreadyLike->delete();
            die();
        }

        $like = new SavedRecipe();
        $like->recipe_id = (int)$_POST['recipeId'];
        $like->user_id = \Yii::$app->user->identity->id;
        $like->save();
        die();
    }

    public function actionSaverecipeinfo()
    {
        $recipeInfo = isset($_POST['recipeInfo']) ? json_decode($_POST['recipeInfo']) : false;

        if (!$recipeInfo) exit('fail-input');
        
        $recipe = Recipe::findOne($recipeInfo->id);
        if (!$recipe) exit('fail-recipe-not-found');

        $recipe->name = !empty($recipeInfo->name) ? $recipeInfo->name : $recipe->name;
        $recipe->section = $recipeInfo->sectionId;
        $recipe->alias = \php_rutils\RUtils::translit()->slugify($recipe->id.'-'.$recipe->name);
        $recipe->description = !empty($recipeInfo->recipeDesc) ? $recipeInfo->recipeDesc : $recipe->description;
        $recipe->cook_time = !empty($recipeInfo->cookTime) ? $recipeInfo->cookTime : $recipe->cook_time;
        $recipe->edit_date = date('Y-m-d', time());
        $recipe->status = $recipe->status === Recipe::STATUS_SCRATCH ? Recipe::STATUS_SCRATCH : Recipe::STATUS_MODIFIED;

        $response = (object)array('error' => false);
        if ($recipe->save()) {
            foreach ($recipeInfo->ing as $ing){
                if (is_null($ing)) continue;

                if (empty($ing->name)) {
                    if($ing->id == 'new') continue;
                    Ingridient::findOne($ing->id)->delete();
                    continue;
                }

                if ($ing->id == 'new') {
                    $newIng = new Ingridient();
                    $newIng->recipe_id = $recipe->id;
                    $newIng->name = $ing->name;
                    $newIng->save();
                    continue;
                }

                $ingr = Ingridient::findOne($ing->id);
                $ingr->name = $ing->name;
                if (!$ingr->validate()) continue;
                $ingr->save();
            }

            foreach ($recipeInfo->steps as $instruction){
                if (is_null($instruction)) continue;

                if ($instruction->id == 'new') {
                    if (empty($instruction->name))
                        continue;
                    $step = new Instruction();
                    $step->step = $instruction->step;
                    $step->instruction = $instruction->name;
                    $step->recipe_id = $recipeInfo->id;
                    $step->save();
                    continue;
                }

                $step = Instruction::findOne($instruction->id);
                $step->step = $instruction->step;
                $step->instruction = $instruction->name;
                $step->save();
                continue;
            }

            $response->steps = $recipe->getInstructions()->asArray()->all();
            $response->ingridients = $recipe->getIngridients()->asArray()->all();
            echo json_encode($response);
            exit();
        }

        $response->error = true;
        echo json_encode($response);
        exit();
    }

    public function actionRemovestep()
    {
        if (empty($_POST['id'])) exit();
        $id = (int)$_POST['id'];
        $step = Instruction::findOne($id);
        $sql = 'SELECT * FROM instruction WHERE recipe_id=:rec_id AND step>:step';
        $steps = Instruction::findBySql($sql, [':rec_id' => $step->recipe_id, ':step' => $step->step])->all();

        $filePathToDelete = \Yii::getAlias('@app').DS.'media'.DS.$step->recipe_id.DS.$step->step.'.jpg';
        if (file_exists($filePathToDelete)) {
            unlink($filePathToDelete);
        }

        foreach ($steps as $s) {
            $filePathToUpdate = \Yii::getAlias('@app').DS.'media'.DS.$s->recipe_id.DS.$s->step.'.jpg';
            $s->step--;
            $s->save();
            $newFilePath = \Yii::getAlias('@app').DS.'media'.DS.$s->recipe_id.DS.$s->step.'.jpg';
            if (file_exists($filePathToUpdate)) {
                rename($filePathToUpdate, $newFilePath);
            }
        }

        $recipeId = $step->recipe_id;
        $step->delete();
        $response = Instruction::find()->where('recipe_id='.$recipeId)->asArray()->all();

        foreach ($response as &$item) {
            $item['url'] = Url::to(['image/edit', 'id' => $item['recipe_id'], 'num' => $item['step']]);
        }

        echo json_encode($response);
        die;
    }

    public function actionRemoveing()
    {
        if (empty($_POST['id'])) exit();
        $id = (int)$_POST['id'];
        Ingridient::findOne($id)->delete();
        exit();
    }
}