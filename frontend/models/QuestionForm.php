<?php

namespace frontend\models;

use common\models\Question;
use Yii;
use yii\base\Model;

/**
 * question form
 */
class QuestionForm extends Model
{
    public $title;
    public $detail;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 50],
            [['detail'], 'string', 'max' => 1024],
        ];
    }

    public function create()
    {
        $question = new Question();
        $question->setAttributes([
            'title'  => $this->title,
            'detail' => $this->detail,
        ]);

        $saveSuccess = $question->save();
        return $saveSuccess ? $question : false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title'  => '问题',
            'detail' => '补充说明',
        ];
    }
}
