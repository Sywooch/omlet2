<?php

namespace app\controllers;

use app\models\Ingridient;
use app\models\Instruction;
use app\models\Recipe;
use app\models\RecipeSection;
use yii\helpers\Url;

class AjaxController extends \yii\web\Controller
{
    //todo: прописать ограничение только аякс на весь контроллер
    public function actionSaverecipeinfo()
    {
        $recipeInfo = isset($_POST['recipeInfo']) ? json_decode($_POST['recipeInfo']) : false;

        if (!$recipeInfo) exit('fail');
        
        $recipe = Recipe::findOne($recipeInfo->id);
        if (!$recipe) exit('fail');

        $recipe->name = $recipeInfo->name;
        $recipe->section = $recipeInfo->sectionId;
        $recipe->alias = \php_rutils\RUtils::translit()->slugify($recipe->id.'-'.$recipe->name);
        $recipe->description = $recipeInfo->recipeDesc;
        $recipe->edit_date = date('Y-m-d', time());
        $recipe->status = $recipe->status === Recipe::STATUS_SCRATCH ? Recipe::STATUS_SCRATCH : Recipe::STATUS_MODIFIED;

        $response = (object)array('error' => false);
        if ($recipe->save()) {
            foreach ($recipeInfo->ing as $ing){
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