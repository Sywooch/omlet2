<?php
$this->title = 'Omlet - редактируем рецепт';
?>

<div class="container">
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <button class="btn btn-primary scratch-btn">
                До чернеток
            </button>
            <button class="btn btn-success publish-btn">
                Опублікувати
            </button>
        </div>
        <div class="col-md-3 col-xs-6">
            <h1>Що готуємо?</h1>
        </div>
        <div class="col-md-4 col-xs-8">
            <label for="recipeName">Назва страви:</label>
            <input type="text" class="form-control" id="recipeName" name="Recipe[name]" value="<?=$recipe['name']?>">
        </div>
        <div class="col-md-3 col-xs-4">
            <label for="recipeSection">Категорія рецептів:</label>
            <select class="form-control" id="recipeSection" name="Recipe[section]" >
                <?php
                foreach ($sections as $p_section) { ?>
                    <optgroup label="<?=$p_section['name']?>">
                        <?php
                        foreach ($p_section['sections'] as $section) {
                            $selected = ($recipe['section'] == $section['id'])?' selected':'';?>
                            <option value="<?=$section['id']?>"<?=$selected?>><?=$section['name']?></option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
        </div>
    </div>
    <hr>
    <div class="row ">
        <div class="col-md-5 col-xs-12">
            <label>Фото страви:</label>
            <?php
            echo \kartik\file\FileInput::widget([
                'id' => 'main-photo-upload',
                'model' => $mainPhotoModel,
                'attribute' => 'photo',
                'options' => [
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'uploadUrl' => \yii\helpers\Url::to(['upload/photo']),
                    'uploadExtraData' => [
                        'recipeId' => $recipe['id'],
                        'photoNum' => '0',
                        'mainPhotoFlag' => 'true',
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
                    'defaultPreviewContent' =>
                        file_exists(\Yii::getAlias('@app').DS.'media'.DS.$recipe['id'].DS.'0.jpg')?
                        '<img src="'.\yii\helpers\Url::to(['image/preview', 'id' => $recipe['id'], 'num' => '0']).'"">':
                        '',
                ]
            ]);
            ?>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label for="description">Декілька слів про страву:</label>
            <textarea rows="15" class="form-control" id="description" name="Recipe[description]"><?=(!is_null($recipe['description']))?$recipe['description']:''?></textarea>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 ingridients-group">
            <label for="ing">Інгрідієнти:</label>
            <div class="row">
                <div class="col-xs-10">
                    <input type="text" class="form-control" placeholder="3 картоплинки..." id="ing">
                </div>
                <div class="col-xs-2">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-10">
                    <input type="text" class="form-control" placeholder="2 ст л цукру..." id="ing">
                </div>
                <div class="col-xs-2">
                </div>
            </div>
            <button class="btn btn-default" id="ingridient-add"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
        </div>
    </div>
    <h2>Як готуємо?</h2>
    <?php
    if (empty($instructions)) { ?>
        <div class="row step" id="steps">
            <div class="col-md-1 hidden-xs">
                <img class="step-img-holder" src="/web/img/step_img_def.jpeg">
            </div>
            <div class="col-md-8 col-xs-7">
                <textarea rows="13" class="step-description form-control" placeholder="...як ріжемо/паримо/варимо?"></textarea>
            </div>
            <div class="col-md-3 col-xs-5">
                <?php
                echo \kartik\file\FileInput::widget([
                    'model' => $stepPhotoModel,
                    'attribute' => 'photo',
                    'options' => [
                        'accept' => 'image/*',
                    ],
                    'pluginOptions' => [
                        'uploadUrl' => \yii\helpers\Url::to(['upload/photo']),
                        'uploadExtraData' => [
                            'recipeId' => $recipe['id'],
                            'photoNum' => '1',
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
                    ]
                ]);
                ?>
            </div>
        </div>
    <?php } else {
        foreach ($instructions as $instruction) { ?>
            <div class="row step" id="steps">
                <div class="col-md-1 hidden-xs">
                    <img class="step-img-holder" src="/web/img/step_img_def.jpeg">
                </div>
                <div class="col-md-8 col-xs-7">
                    <textarea rows="13" class="step-description form-control" placeholder="...як ріжемо/паримо/варимо?"><?=$instruction['instruction']?></textarea>
                </div>
                <div class="col-md-3 col-xs-5">
                    <?php
                    if (file_exists(\Yii::getAlias('@app').DS.'media'.DS.$recipe['id'].DS. $instruction['step'] . '.jpg')) {
                        echo '<img src="' . \yii\helpers\Url::to(['image/steppreview', 'id' => $recipe['id'], 'num' => $instruction['step']]) . '">';
                    } ?>
                </div>
            </div>
        <?php }
        if (isset($_POST['stepAdd'])) { ?>
            <div class="row step" id="steps">
                <div class="col-md-1 hidden-xs">
                    <img class="step-img-holder" src="/web/img/step_img_def.jpeg">
                </div>
                <div class="col-md-8 col-xs-7">
                    <textarea rows="13" class="step-description form-control" placeholder="...як ріжемо/паримо/варимо?"><?=$instruction['instruction']?></textarea>
                </div>
                <div class="col-md-3 col-xs-5">
                    <?php
                    echo \kartik\file\FileInput::widget([
                        'model' => $stepPhotoModel,
                        'attribute' => 'photo',
                        'options' => [
                            'accept' => 'image/*',
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => \yii\helpers\Url::to(['upload/photo']),
                            'uploadExtraData' => [
                                'recipeId' => $recipe['id'],
                                'photoNum' => '1',
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
                        ]
                    ]);
                    ?>
                </div>
            </div>
        <?php }
    }
    ?>

    <div class="row">
        <div class="col-xs-2">
            <form method="post">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input type="hidden" name="stepAdd" value="1">
                <input type="submit" value="Ще інструкцій" class="btn btn-default">
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#ingridient-add').on('click', function(){
            var addBlock = '';
            addBlock += '<div class="row">';
            addBlock += '<div class="col-xs-10">';
            addBlock += '<input type="text" class="form-control" placeholder="..." id="ing">';
            addBlock += '</div>';
            addBlock += '<div class="col-xs-2">';
            addBlock += '<span onclick="$(this).parent().parent().remove();" class="glyphicon glyphicon-remove ing-btn-remove" aria-hidden="true"></span>';
            addBlock += '</div>';
            addBlock += '</div>';
            $(this).before(addBlock);
        });

    });
</script>