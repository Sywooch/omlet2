<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe".
 *
 * @property string $id
 * @property string $parent_section
 * @property string $section
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property string $add_date
 * @property integer $status
 * @property string $author
 * @property string $likes
 * @property string $views
 * @property string $reputation
 * @property string $last_view_date
 *
 * @property Ingridient[] $ingridients
 * @property Instruction[] $instructions
 * @property SavedRecipe[] $savedRecipes
 * @property User[] $users
 * @property Tags[] $tags
 */
class Recipe extends \yii\db\ActiveRecord
{
    const STATUS_SCRATCH = 1;
    const STATUS_DENIED = 2;
    const STATUS_PUBLISHED = 3;
    const STATUS_APPROVED = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recipe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_section', 'section', 'name', 'status', 'author'], 'required'],
            [['parent_section', 'section', 'status', 'author', 'likes', 'views', 'reputation'], 'integer'],
            [['add_date', 'last_view_date'], 'safe'],
            [['name', 'alias'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_section' => 'Parent Section',
            'section' => 'Section',
            'name' => 'Name',
            'alias' => 'Alias',
            'description' => 'Description',
            'add_date' => 'Add Date',
            'status' => 'Status',
            'author' => 'Author',
            'likes' => 'Likes',
            'views' => 'Views',
            'reputation' => 'Reputation',
            'last_view_date' => 'Last View Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngridients()
    {
        return $this->hasMany(Ingridient::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructions()
    {
        return $this->hasMany(Instruction::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSavedRecipes()
    {
        return $this->hasMany(SavedRecipe::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('saved_recipe', ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['recipe_id' => 'id']);
    }
}
