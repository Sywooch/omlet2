<?php
use \yii\helpers\Url;
use \app\models\Recipe;
$this->title = 'Omlet - редактируем рецепт';
$this->registerJsFile(Yii::getAlias('@web/js/recipe-edit.js'));
?>
<br>
<script>
    window.ImageEditUrls = [];
</script>
<div class="container">
    <div class="row">
        <div class="col-md-2 col-xs-12">
            <a href="<?=Url::to(
                ['cabinet/status',
                    'id' => $recipe['id'],
                    'status' => $recipe['status'] == Recipe::STATUS_SCRATCH ? Recipe::STATUS_SCRATCH : Recipe::STATUS_MODIFIED
                ])?>" class="btn btn-primary scratch-btn control-btn">
                До чернеток
            </a>
            <a href="<?=Url::to(
                ['cabinet/status',
                    'id' => $recipe['id'],
                    'status' =>Recipe::STATUS_PUBLISHED
                ])?>" class="btn btn-success publish-btn control-btn">
                Опублікувати
            </a>
        </div>
        <div class="col-md-3 col-xs-12">
            <h1>Що готуємо?</h1>
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="recipeName">Назва страви:</label>
            <input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
            <input type="text" class="form-control recipe-info" id="recipeName" name="Recipe[name]" value="<?=$recipe['name']?>">
        </div>
        <div class="col-md-3 col-xs-12">
            <label for="recipeSection">Категорія рецептів:</label>
            <select class="form-control recipe-info" id="recipeSection" name="Recipe[section]" >
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
                    'uploadUrl' => Url::to(['upload/photo']),
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
                        '<img class="photo-mobile" src="'.Url::to(['image/preview', 'id' => $recipe['id'], 'num' => '0']).'"">':
                        '',
                ]
            ]);
            ?>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label for="description">Декілька слів про страву:</label>
            <textarea rows="15" class="form-control recipe-info" id="description" name="Recipe[description]"><?=(!is_null($recipe['description']))?$recipe['description']:''?></textarea>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 ingridients-group">
            <label for="ing">Інгрідієнти:</label>
            <?php
            if (empty($ingridients)) { ?>
                <div class="row">
                    <div class="col-xs-10">
                        <input type="text" class="form-control recipe-info" value="...">
                    </div>
                    <div class="col-xs-2">
                    </div>
                </div>
            <?php } else {
                foreach ($ingridients as $ing) {?>
                    <div class="row">
                        <div class="col-xs-10">
                            <input type="text" data-id="<?=$ing['id']?>" class="form-control recipe-info" value="<?=$ing['name']?>">
                        </div>
                        <div class="col-xs-2">
                            <span onclick="removeIng($(this));" class="glyphicon glyphicon-remove ing-btn-remove" aria-hidden="true"></span>
                        </div>
                    </div>
            <?php } } ?>
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
                <textarea rows="17" class="step-description form-control recipe-info" placeholder="...як ріжемо/паримо/варимо?"></textarea>
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
                        'uploadUrl' => Url::to(['upload/photo']),
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
            <script>
                window.ImageEditUrls[<?=$instruction['id']?>] =
                '<?=Url::to(['image/edit', 'id' => $recipe['id'], 'num' => $instruction['step']])?>';
            </script>
            <div class="row step" id="steps">
                <div class="col-md-1 hidden-xs">
                    <img class="step-img-holder" src="/web/img/step_img_def.jpeg">
                </div>
                <div class="col-md-8 col-xs-12">
                    <span onclick="removeStep($(this));" class="glyphicon glyphicon-remove step-btn-remove" aria-hidden="true"></span>
                    <textarea rows="11" data-id="<?=$instruction['id']?>" class="step-description form-control recipe-info" placeholder="...як ріжемо/паримо/варимо?"><?=$instruction['instruction']?></textarea>
                </div>
                <div class="col-md-3 col-xs-12">
                    <button class="btn btn-default photo-edit control-btn"
                            data-stepID="<?=$instruction['id']?>"
                            onclick="goToUrl($(this));">Додати/редагувати фото</button>
                    <?php
                    if (file_exists(\Yii::getAlias('@app').DS.'media'.DS.$recipe['id'].DS. $instruction['step'] . '.jpg')) {
                        echo '<img class="photo-preview mob-margin" src="' . Url::to(['image/steppreview', 'id' => $recipe['id'], 'num' => $instruction['step']]) . '">';
                    } ?>
                </div>
            </div>
            <div class="visible-xs-block hor-line"></div>
        <?php }
        if (isset($_POST['stepAdd'])) { ?>
            <div class="row step mob-margin" id="steps">
                <div class="col-md-1 hidden-xs">
                    <img class="step-img-holder" src="/web/img/step_img_def.jpeg">
                </div>
                <div class="col-md-8 col-xs-12">
                    <textarea rows="17" class="step-description form-control recipe-info" placeholder="...як ріжемо/паримо/варимо?"></textarea>
                </div>
                <div class="col-md-3 col-xs-12 mob-margin">
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
                                'recipeId' => $recipe->id,
                                'photoNum' => count($instructions)+1,
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
            <div class="visible-xs-block hor-line"></div>
            <script>
                $(window).scrollTop($(document).height());
            </script>
        <?php }
    }
    ?>

    <div class="row">
        <div class="col-xs-2">
            <form method="post">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input type="hidden" name="stepAdd" value="1">
                <input type="submit" value="Ще інструкцій" class="btn btn-default control-btn   ">
            </form>
        </div>
    </div>
    <div id="showSave" style="display:none">
        <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
    </div>
</div>

