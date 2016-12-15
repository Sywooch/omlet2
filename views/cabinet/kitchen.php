<?php
use \yii\grid\GridView;
use \app\models\Recipe;
use \yii\helpers\Html;
use \yii\helpers\Url;

/* @var $this yii\web\View */
?>

<div class="container">
    <div class="row">
        <div role="tabpanel" class="col-xs-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#recipes" aria-controls="recipes" role="tab" data-toggle="tab">Мої рецепти</a></li>
                <li role="presentation"><a href="#saved" aria-controls="saved" role="tab" data-toggle="tab">Збережені</a></li>
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Мій профіль</a></li>
            </ul>
            <br>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade  in active" id="recipes">
                    <?= GridView::widget([
                        'dataProvider' => $userRecipes,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute'=>'name',
                                'label' => 'Назва',
                                'content'=>function($data){
                                    if (in_array($data->status, Recipe::getActiveStatuses()))
                                        return Html::a($data->name, Url::to(['recipe/show', 'alias' => $data->alias]));
                                    return $data->name;
                                }
                            ],
                            [
                                'attribute'=>'section',
                                'label' => 'Розділ',
                                'content'=>function($data){
                                    return $data->getSection()->one()->name;
                                }
                            ],
                            [
                                'attribute'=>'add_date',
                                'label' => 'Доданий',
                            ],
                            [
                                'attribute'=>'status',
                                'label' => 'Статус',
                                'content'=>function($data){
                                    return Recipe::getStatusTranslate($data->status);
                                }
                            ],
                            [
                                'attribute'=>'likes',
                                'label' => 'Лайки',
                            ],
                            [
                                'attribute'=>'views',
                                'label' => 'Перегляди',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                                'buttons' => [
                                    'update' => function ($url,$model) {
                                        $url = Url::to(['recipe/edit', 'alias' => $model->alias]);
                                        return Html::a(
                                            '<span class="glyphicon glyphicon-pencil"></span>',
                                            $url);
                                    },
                                    'delete' => function ($url,$model) {
                                        if (!in_array($model->status, Recipe::getActiveStatuses()))
                                        return Html::a(
                                            '<span class="glyphicon glyphicon-trash"></span>',
                                            $url);
                                    },
                                ],
                            ],

                        ]
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="saved">
                    <?= GridView::widget([
                        'dataProvider' => $savedRecipes,
                        'columns' => [
                            [
                                'label' => 'Рецепт',
                                'content'=>function($data){
                                    return Html::a($data->getRecipe()->one()->name, Url::to(
                                            [
                                                'recipe/show', 'alias' => $data->getRecipe()->one()->alias
                                            ]
                                        )
                                    );
                                }
                            ],
                            [
                                'label' => 'Розділ',
                                'options' => ['width' => '40%'],
                                'content'=>function($data){
                                    return $data->getRecipe()->one()->getSection()->one()->name;
                                }
                            ],
                        ]
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="profile">
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <?php

                            echo \kartik\file\FileInput::widget([
                                'id' => 'main-photo-upload',
                                'model' => new \app\models\UploadAvatar(),
                                'attribute' => 'photo',
                                'options' => [
                                    'accept' => 'image/*',
                                ],
                                'pluginOptions' => [
                                    'uploadUrl' => Url::to(['upload/avatar']),
                                    'uploadExtraData' => [
                                        'user_id' => \Yii::$app->user->identity->id,
                                    ],
                                    'showBrowse' => false,
                                    'dropZoneEnabled' => true,
                                    'dropZoneTitle' => 'Затягніть сюди аватарку',
                                    'dropZoneClickTitle' => ', або клікніть щоб вибрати...',
                                    'showPreview' => true,
                                    'browseOnZoneClick' => true,
                                    'allowedFileTypes' => ['image' ],
                                    'maxFileSize' => 6000,
                                    'maxFileCount' => 1,
                                    'resizeImageQuality' => 0.75,
                                    'defaultPreviewContent' =>
                                        file_exists(\Yii::getAlias('@app').DS.'media'.DS.'avatars'.DS.\Yii::$app->user->identity->id.'.jpg')?
                                            '<img src="'.Url::to(['image/avatar', 'id' => \Yii::$app->user->identity->id]).'"">':
                                            '',
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="col-xs-12 col-md-5">
                            <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'user-profile']) ?>
                            <table class="table table-striped">
                                <?php
                                $template = '<tr><td>{label}</td><td>{input}</td></tr>';
                                echo $form->field($user, 'username',['template' => $template]);
                                echo $form->field($user, 'birthday_date',['template' => $template])->input('date');
                                echo $form->field($user, 'email',['template' => $template])->input('text', ['disabled'=>'disabled']);
                                echo $form->field($user, 'registration_date',['template' => $template])->input('text', ['disabled'=>'disabled']);
                                echo $form->field($user, 'about_me',['template' => $template])->textarea(['rows' => 6, 'class'=>'no-resize form-control']);

                                ?>
                            </table>
                        </div>
                        <div class="col-xs-12 col-md-1">
                            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary save-profile-btn']) ?>
                            <?php \yii\widgets\ActiveForm::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>