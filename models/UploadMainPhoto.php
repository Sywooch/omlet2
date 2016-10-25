<?php
namespace app\models;

use Imagine\Image\Box;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadMainPhoto extends Model
{
    /**
     * @var UploadedFile
     */
    public $photo;

    public function rules()
    {
        return [
            [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($recipeId)
    {
        if ($this->validate()) {
            $filePath = \Yii::getAlias('@app') . DS .  'media' . DS .  $recipeId;
            $filePath .= DS . '0.jpg';
            //$this->photo->saveAs($filePath);
            //saving photo
            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($this->photo->tempName);
            $originalSize = $image->getSize();
            $proportion = $originalSize->getHeight() / $originalSize->getWidth();
            $image->resize(new Box(750 , 750*$proportion))->save($filePath);
            return true;
        } else {
            return false;
        }
    }
}