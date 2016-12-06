<?php
namespace app\controllers;


use abeautifulsite\SimpleImage;
use Imagine\Image\Box;

class ImageController extends \yii\web\Controller
{
    const IMAGE_TEXT = ' omlet.kiev.ua';

    public function actionAvatar($id)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.'avatars'.DS.(int)$id.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $fontSize = ($image->get_original_info()['width']<300)?"30":'60';
        $image->text(self::IMAGE_TEXT, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", $fontSize, '#e7e516', 'bottom left' )->output();

        exit();
    }

    //todo поставить ограничение на авторизацию и автора
    public function actionRecipe($id, $num)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $fontSize = ($image->get_original_info()['width']<300)?"30":'60';
        $image->text(self::IMAGE_TEXT, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", $fontSize, '#e7e516', 'bottom left' )->output();

        exit();
    }

    //todo waterMark
    public function actionPreview($id, $num)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $fontSize = ($image->get_original_info()['width']<300)?"30":'60';
        $image->text(self::IMAGE_TEXT, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", $fontSize, '#e7e516', 'bottom left' )->output();


        exit();
    }
//todo waterMark
    public function actionSteppreview($id, $num)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $image->adaptive_resize(190, 160);
        $image->text(self::IMAGE_TEXT, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", 30, '#e7e516', 'bottom left' )->output();

        exit();
    }

    public function actionEdit($id, $num)
    {
        $recipe = \app\models\Recipe::findOne((int)$id);
        if (!$recipe) $this->goHome();
        $stepPhotoModel = new \app\models\UploadStepPhoto();

        return $this->render('edit', [
            'stepPhotoModel' => $stepPhotoModel,
            'id' => $id,
            'num' => $num,
            'alias' => $recipe->alias,
        ]);
    }

    public function actionDelete($id, $num)
    {
        $recipe = \app\models\Recipe::findOne((int)$id);
        if (!$recipe) $this->goHome();
        $imagePath = \Yii::getAlias('@app').DS.'media'.DS.(int)$id.DS.(int)$num . '.jpg';
        if (file_exists($imagePath)) unlink($imagePath);
        $this->redirect(\yii\helpers\Url::to(['recipe/edit', 'alias' => $recipe->alias]));
    }
}