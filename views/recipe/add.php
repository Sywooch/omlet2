<?php
$this->title = 'Новий рецепт';
?>

<h3>
    Додаємо новий рецепт:
</h3>
<form class="form-horizontal col-md-6 col-xs-12" role="form" method="post">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <div class="form-group">
        <label for="recipeName" class="col-sm-4 control-label">Назва страви</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="recipeName" placeholder="що готуємо?" required name="Recipe[name]">
        </div>
    </div>
    <div class="form-group">
        <label for="recipeSection" class="col-sm-4 control-label">Оберіть розділ</label>
        <div class="col-sm-8">
            <select class="form-control" id="recipeSection" required name="Recipe[section]" >
                <?php
                foreach ($sections as $p_section) { ?>
                    <optgroup label="<?=$p_section['name']?>">
                        <?php
                        foreach ($p_section['sections'] as $section) { ?>
                            <option value="<?=$section['id']?>"><?=$section['name']?></option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-8">
            <button type="submit" class="btn btn-default">Додати</button>
        </div>
    </div>
</form>
