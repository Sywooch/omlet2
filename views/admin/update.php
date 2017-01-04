<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Setting */

$this->title = 'Update Setting: ' . $model->category;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category, 'url' => ['view', 'category' => $model->category, 'key' => $model->key]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
