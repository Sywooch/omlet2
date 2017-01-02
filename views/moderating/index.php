<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Recipe;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'section',
                'label' => 'Розділ',
                'content'=>function($data){
                    return $data->getSection()->one()->name;
                }
            ],
            [
                'attribute'=>'name',
                'label' => 'Назва',
                'content'=>function($data){
                    return Html::a($data->name, Url::to(['recipe/show', 'alias' => $data->alias]));
                }
            ],
            'add_date',
            'edit_date',
            [
                'attribute'=>'status',
                'label' => 'Статус',
                'content'=>function($data){
                    return Recipe::getAdminStatusTranslate($data->status);
                }
            ],
            [
                'attribute'=>'author',
                'label' => 'Автор',
                'content'=>function($data){
                    $author = $data->getAuthor()->one();
                    return Html::a($author->username, Url::to(['cabinet/profile', 'email' => $author->email]));
                }
            ],
            'likes',
            'views',
            'last_view_date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {moderate}',
                'buttons' => [
                    'update' => function ($url,$model) {
                        $url = Url::to(['recipe/edit', 'alias' => $model->alias]);
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url, ['title' => 'подкорректировать рецепт']);
                    },
                    'moderate' => function ($url,$model) {
                        $url = Url::to(['moderating/update', 'id' => $model->id]);
                        return Html::a(
                            '<span class="glyphicon glyphicon-alert"></span>',
                            $url, ['title' => 'модерация']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
