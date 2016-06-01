<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "{{%answer}}".
 *
 * @property integer $id
 * @property integer $question_id
 * @property string $url
 * @property string $title
 * @property string $digest
 * @property string $content
 * @property integer $view_count
 * @property integer $like_count
 * @property integer $favorite_count
 * @property integer $comment_count
 * @property string $status
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $is_deleted
 *
 * @property User $createdBy
 * @property Question $question
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }


    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
            //see https://github.com/yii2tech/ar-softdelete
            [
                'class'                     => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true
                ],
                'replaceRegularDelete'      => true // mutate native `delete()` method
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'url', 'title'], 'required'],
            [['url'], 'string', 'max' => 512],
            [['url'], 'url'],
            [['title'], 'string', 'max' => 64],
            [['digest'], 'string', 'max' => 1024],
            [['content'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 16],
            ['status', 'default', 'value' => 'active'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'question_id'    => '问题ID',
            'url'            => '链接',
            'title'          => '链接标题',
            'digest'         => '推荐语',
            'content'        => '链接内容',
            'view_count'     => '浏览数量',
            'like_count'     => '点赞数量',
            'favorite_count' => '收藏数量',
            'comment_count'  => '评论数量',
            'status'         => '状态',
            'updated_at'     => 'Updated At',
            'created_by'     => 'Created By',
            'created_at'     => 'Created At',
            'is_deleted'     => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * 用户是否对答案点过赞或者收藏
     * @param $user_id
     * @return array
     */
    public function getUserActions($user_id)
    {
        $userAction = UserAction::find()->andWhere([
            'target_type' => 'answer',
            'target_id'   => $this->id,
            'created_by'  => $user_id,
        ])->asArray()->all();

        return ArrayHelper::getColumn($userAction, 'action');
    }
}
