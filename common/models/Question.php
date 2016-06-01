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
 * This is the model class for table "{{%question}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $detail
 * @property integer $view_count
 * @property integer $answer_count
 * @property integer $follow_count
 * @property string $status
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $is_deleted
 *
 * @property Answer[] $answers
 * @property User $createdBy
 * @property User $updatedBy
 */
class Question extends \yii\db\ActiveRecord
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
                'updatedByAttribute' => 'updated_by',
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
        return '{{%question}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 64],
            [['detail'], 'string', 'max' => 1024],
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
            'id'           => 'ID',
            'title'        => '问题',
            'detail'       => '详细描述',
            'view_count'   => '浏览数量',
            'answer_count' => '回答数量',
            'follow_count' => '关注数量',
            'status'       => '状态',
            'updated_by'   => 'Updated By',
            'updated_at'   => 'Updated At',
            'created_by'   => 'Created By',
            'created_at'   => 'Created At',
            'is_deleted'   => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * 用户是否对问题点过赞或者收藏
     * @param $user_id
     * @return array
     */
    public function getUserActions($user_id)
    {
        $userAction = UserAction::find()->andWhere([
            'target_type' => 'question',
            'target_id'   => $this->id,
            'created_by'  => $user_id,
        ])->asArray()->all();

        return ArrayHelper::getColumn($userAction, 'action');
    }
}
