<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\Recipe;
/* @var $this yii\web\View */
/* @var $model app\models\Recipe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recipe-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?php
    $items = [
        Recipe::STATUS_SCRATCH => Recipe::getAdminStatusTranslate(Recipe::STATUS_SCRATCH),
        Recipe::STATUS_PUBLISHED => Recipe::getAdminStatusTranslate(Recipe::STATUS_PUBLISHED),
        Recipe::STATUS_APPROVED => Recipe::getAdminStatusTranslate(Recipe::STATUS_APPROVED),
        Recipe::STATUS_MODIFIED => Recipe::getAdminStatusTranslate(Recipe::STATUS_MODIFIED),
        Recipe::STATUS_USER_DELETED => Recipe::getAdminStatusTranslate(Recipe::STATUS_USER_DELETED),
    ];
    echo $form->field($model, 'status')->radioList($items) ?>

    <?= $form->field($model, 'likes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'views')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
