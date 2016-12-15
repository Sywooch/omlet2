<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "recipe_section".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $h1
 * @property string $title
 * @property string $description
 * @property string $parent_id
 */
class RecipeSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recipe_section';
    }

    public function getImageUrl()
    {
        $filePath = \Yii::getAlias('@app') . DS .  'web' . DS .'img'.DS.  'sections' . DS. $this->id . '.png';
        if (file_exists($filePath))
            return '/web/img/sections/' . $this->id . '.png';

        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['parent_id'], 'integer'],
            [['name','alias'], 'string', 'max' => 100],
            [['title','h1', 'description'], 'string', 'max' => 10000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'h1' => 'H1',
            'title'=> 'Title',
            'description' => 'Description',
            'parent_id' => 'Parent ID',
        ];
    }

    public static function getAllSections()
    {
        $p_sections = self::find()->where(['parent_id' => 0])->orderBy('id')->asArray()->all();
        $d_sections = self::find()->where(['>', 'parent_id', 0])->asArray()->all();
        $allSections = [];
        foreach ($p_sections as $p_section) {
            $p_section['sections'] = [];
            foreach ($d_sections as $d_section) {
                if ($d_section['parent_id'] == $p_section['id']) $p_section['sections'][] = $d_section;
            }
            $allSections[] = $p_section;
        }

        return $allSections;
    }

    public function getRecipes()
    {
        return $this->hasMany(Recipe::className(), ['section' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    public function getChilds()
    {
        return $this->hasOne(self::className(), ['parent_id' => 'id']);
    }
}
