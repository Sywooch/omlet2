<?php

use \yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Omlet - Поиск рецептов';
?>
<div class="site-index">
    <div class="jumbotron">
       <div class="row">
           <div class="col-md-6 col-xs-12">
               <div class="row" id="main-search-block">
                   <div class="col-xs-12">
                      <form role="form" action="<?= Url::to(['search/search']) ?>">
                          <div class="form-group" id="search-input">
                              <label for="main-search-input">Що будемо готувати?</label>
                              <input type="text" name="s" class="form-control" id="main-search-input">
                          </div>
                      </form>
                   </div>
                   <div id="hr" class="col-xs-12"></div>
                   <?php
                   foreach ($mainCats as $cat) {
                       $imgPath = '/web/img/sections/' . $cat->id . '.png';
                       ?>
                       <div class="col-xs-3">
                           <a href="<?= Url::to(['search/category', 'alias' => $cat->alias]); ?>" title="">
                               <img src="<?= $imgPath ?>" title="<?= $cat->name ?>" alt="<?= $cat->name ?>"/>
                           </a>
                       </div>
                   <?php } ?>
               </div>
           </div>
           <div class="col-md-6 hidden-xs">
               <img src="<?= $imageUrl ?>" title="" alt="" />
           </div>
       </div>
    </div>
    <div class="body-content">
        <div class="row">
            <p>Найпопулярніші рецепти:</p>
        </div>
        <div class="row">
            <?=\yii\widgets\ListView::widget([
                'dataProvider' => $recipesProvider,
                'itemView' => '..'.DS.'search'.DS.'_recipe',
                'summary' => ''
            ]);?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                seo-text
            </div>
        </div>
    </div>
</div>



