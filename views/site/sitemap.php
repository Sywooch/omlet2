<?php
use \yii\helpers\Url;

$outPut = '<?xml version="1.0" encoding="UTF-8"?>';
$outPut .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// main
$outPut .= '<url><loc>' . FULL_HOST .  '</loc></url>';

// main search
$outPut .= '<url><loc>' . Url::to(['search/index']) .  '</loc></url>';

// categories
foreach ($categories as $cat) {
    $outPut .= '<url><loc>' . Url::to(['search/category', 'alias' => $cat['alias']]) .  '</loc></url>';
}

// recipes
foreach ($recipes as $recipe) {
    $outPut .= '<url><loc>' . Url::to(['recipe/show', 'alias' => $recipe['alias']]) .  '</loc></url>';
}


$outPut .= '</urlset>';

header("Content-Type: text/xml");
echo $outPut;
die;