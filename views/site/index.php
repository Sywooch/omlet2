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
                       ут будет поиск
                   </div>
                   <?php
                   foreach ($mainCats as $cat) {
                       $imgPath = '/web/img/sections/' . $cat->id . '.png';
                       ?>
                       <div class="col-xs-3">
                           <a href="<?= Url::to(['search/category', 'alias' => $cat->alias]); ?>" title="">
                               <img src="<?= $imgPath ?>" title="" alt=""/>
                           </a>
                       </div>
                   <?php } ?>
               </div>
           </div>
           <div class="col-md-6 col-xs-12">
               <img src="<?= $imageUrl ?>" title="" alt="" />
           </div>
       </div>
    </div>
    <div class="body-content">
        <div class="row">
                <div class="col-xs- col-md-3">recipe</div>
                <div class="col-xs- col-md-3">recipe</div>
                <div class="col-xs- col-md-3">recipe</div>
                <div class="col-xs- col-md-3">recipe</div>
        </div>
    </div>
</div>
</div>
