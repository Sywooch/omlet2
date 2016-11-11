<?php
use \yii\helpers\Url;

$imgPath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS. $num . '.jpg';
if (file_exists($imgPath)) { ?>
    <div class="row">
        <div class="col-xs-12 col-md-7">
            <h2>Не подобається фото? <a href="<?=Url::to(['image/delete', 'id' => $id, 'num' => $num])?>">Видаліть</a> або оновіть :</h2>
        </div>
    </div>
<?php  } ?>
<div class="row">
    <div class="col-xs-12 col-md-4">
        <?php
        echo \kartik\file\FileInput::widget([
            'model' => $stepPhotoModel,
            'attribute' => 'photo',
            'options' => [
                'accept' => 'image/*',
            ],
            'pluginOptions' => [
                'uploadUrl' => Url::to(['upload/photo']),
                'uploadExtraData' => [
                    'recipeId' => $id,
                    'photoNum' => $num,
                    'mainPhotoFlag' => 'false',
                ],
                'showBrowse' => false,
                'dropZoneEnabled' => true,
                'dropZoneTitle' => 'Затягніть сюди фото',
                'dropZoneClickTitle' => ', або клікніть щоб вибрати...',
                'showPreview' => true,
                'browseOnZoneClick' => true,
                'allowedFileTypes' => ['image' ],
                'maxFileSize' => 6000,
                'maxFileCount' => 1,
                'resizeImageQuality' => 0.75,
                'defaultPreviewContent' => file_exists($imgPath) ?
                    '<img src="' . Url::to(['image/steppreview', 'id' => $id, 'num' => $num]) . '">' : '',
            ]
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-4">
        <h4 style="text-align: center"><a href="<?=Url::to(['recipe/edit', 'alias' => $alias])?>">Назад до рецепту</a></h4>
    </div>
</div>
