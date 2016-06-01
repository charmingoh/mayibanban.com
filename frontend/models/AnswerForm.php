<?php

namespace frontend\models;

use common\models\Answer;
use yii\base\Model;

/**
 * Answer form
 */
class AnswerForm extends Model
{
    public $question_id;
    public $url;
    public $title;
    public $digest;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'url', 'title'], 'required'],
            [['question_id'], 'integer'],
            [['url'], 'string', 'max' => 512],
            [['url'], 'url'],
            [['title'], 'string', 'max' => 64],
            [['digest'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @return bool|Answer
     */
    public function create()
    {
        $answer = new Answer();
        $answer->setAttributes([
            'question_id' => $this->question_id,
            'url'         => $this->url,
            'title'       => $this->title,
            'digest'      => $this->digest,
        ]);

        $saveSuccess = $answer->save();
        return $saveSuccess ? $answer : false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url'    => '相关资源链接',
            'title'  => '标题',
            'digest' => '值得推荐的地方',
        ];
    }
}
