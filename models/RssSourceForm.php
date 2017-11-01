<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RssSourceForm is the model behind the retrieve info form.
 */
class RssSourceForm extends Model
{
    public $link;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'link' => 'Link to a rss source',
        ];
    }


}
