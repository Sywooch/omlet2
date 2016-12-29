<?php
namespace app\controllers;


use abeautifulsite\SimpleImage;
use app\models\Recipe;
use app\models\User;
use Imagine\Image\Box;

class ImageController extends \yii\web\Controller
{

    public function actionAvatar($id)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.'avatars'.DS.(int)$id.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $fontSize = ($image->get_original_info()['width']<300)?"30":'60';
        $image->text(HOST, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", $fontSize, '#e7e516', 'bottom left' )->output();

        exit();
    }

    public function actionRecipe($id, $num)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $fontSize = ($image->get_original_info()['width']<300)?"30":'60';
        $image->text(HOST, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", $fontSize, '#e7e516', 'bottom left' )->output();

        exit();
    }

    public function actionPreview($id, $num)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $fontSize = ($image->get_original_info()['width']<300)?"30":'60';
        $image->text(HOST, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", $fontSize, '#e7e516', 'bottom left' )->output();


        exit();
    }

    public function actionSteppreview($id, $num)
    {
        $filePath = \Yii::getAlias('@app').DS.'media'.DS.$id.DS.$num.'.jpg';
        if (!file_exists($filePath)) exit();

        $image = new SimpleImage($filePath);
        $image->adaptive_resize(190, 160);
        $image->text(HOST, \Yii::getAlias('@app') . DS . 'assets' . DS . 'fonts' . DS . "Favorit.ttf", 30, '#e7e516', 'bottom left' )->output();

        exit();
    }

    public function actionEdit($id, $num)
    {
        $recipe = \app\models\Recipe::findOne((int)$id);
        if (!$recipe) return $this->show404();
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
        User::checkAuthorByRecId($id);


        $recipe = \app\models\Recipe::findOne((int)$id);
        if (!$recipe) return $this->show404();
        $imagePath = \Yii::getAlias('@app').DS.'media'.DS.(int)$id.DS.(int)$num . '.jpg';
        if (file_exists($imagePath)) unlink($imagePath);
        $this->redirect(\yii\helpers\Url::to(['recipe/edit', 'alias' => $recipe->alias]));
    }
}