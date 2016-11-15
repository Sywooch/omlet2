<?php
namespace app\models;

use Imagine\Image\Box;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadAvatar extends Model
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

    public function upload($userId)
    {
        if ($this->validate()) {
            $filePath = \Yii::getAlias('@app') . DS .  'media' . DS .  'avatars' . DS. (int)$userId . '.jpg';
            //saving photo
            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($this->photo->tempName);
            $originalSize = $image->getSize();
            $proportion = $originalSize->getHeight() / $originalSize->getWidth();
            $image->resize(new Box(300 , 300*$proportion))->save($filePath);
            return true;
        } else {
            return false;
        }
    }
}