<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_section".
 *
 * @property string $id
 * @property string $name
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
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
}
