<?php
namespace app\controllers;


use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class ImageController extends \yii\web\Controller
{
    public function actionRecipe($id, $num)
    {
        if (!file_exists(\Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg')) exit();

        $imagine = new \Imagine\Gd\Imagine();
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.(int)$num.'.jpg';
        $image = $imagine->open($filePath);
        $originalSize = $image->getSize();
        $proportion = $originalSize->getHeight() / $originalSize->getWidth();
        $image->resize(new Box(750 , 750*$proportion))->show('jpg');
        exit();
    }

    public function actionPreview($id, $num)
    {
        if (!file_exists(\Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg')) exit();

        $imagine = new \Imagine\Gd\Imagine();
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS .(int)$num.'.jpg';
        $image = $imagine->open($filePath);
        $originalSize = $image->getSize();
        $proportion = $originalSize->getHeight() / $originalSize->getWidth();
        switch (400*$proportion <= 375) {
            case true: $width = 400; $height = 400 * $proportion; break;
            case false: $height = 375; $width = 375 / $proportion; break;
        }
        $image->resize(new Box($width , $height))->show('jpg');
        exit();
    }

    public function actionSteppreview($id, $num)
    {
        if (!file_exists(\Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg')) exit();

        $imagine = new \Imagine\Gd\Imagine();
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS .(int)$num.'.jpg';
        $image = $imagine->open($filePath);
        $originalSize = $image->getSize();
        $proportion = $originalSize->getHeight() / $originalSize->getWidth();
        switch (250*$proportion <= 188) {
            case true: $width = 250; $height = 250 * $proportion; break;
            case false: $height = 188; $width = 188 / $proportion; break;
        }
        $image->resize(new Box($width , $height))->show('jpg');
        exit();
    }
}