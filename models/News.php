<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $pubdate
 * @property string $img_path
 * @property string $link
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'pubdate', 'img_path', 'link'], 'required'],
            [['pubdate'], 'safe'],
            [['title', 'description','img_path', 'link'], 'string', 'max' => 255],
            [['link'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'pubdate' => 'Pubdate',
            'img_path' => 'Img Path',
            'link' => 'Link',
        ];
    }
}
