<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "{{%user_action}}".
 *
 * @property integer $id
 * @property string $action
 * @property string $target_type
 * @property integer $target_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $is_deleted
 *
 * @property User $createdBy
 */
class UserAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return parent::find()->where(['is_deleted' => false]);
    }


    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
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
        return '{{%user_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action', 'target_type', 'target_id'], 'required'],
            ['target_type', 'in', 'range' => ['question', 'answer']],
            ['action', 'in', 'range' => ['like', 'favorite', 'follow']],
            [['target_id'], 'integer'],
            [['action', 'target_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'action'      => '行为',
            'target_type' => '目标',
            'target_id'   => '目标ID',
            'created_by'  => 'Created By',
            'created_at'  => 'Created At',
            'is_deleted'  => 'Is Deleted',
        ];
    }

    /**
     * @return bool
     */
    public function targetExists()
    {
        switch ($this->target_type) {
            case 'question':
                return Question::findOne($this->target_id) !== null;
            case 'answer':
                return Answer::findOne($this->target_id) !== null;
            default:
                return false;
        }
    }

    /**
     * @return bool|int
     */
    public function hasDone()
    {
        $action = self::findOne([
            'action'      => $this->action,
            'target_type' => $this->target_type,
            'target_id'   => $this->target_id,
            'created_by'  => Yii::$app->user->id,
        ]);

        if ($action === null) {
            return false;
        } else {
            return $action->id;
        }
    }

    public function updateTargetCounter()
    {
        $this->followQuestion();
        $this->likeAnswer();
        $this->favoriteAnswer();
    }

    private function followQuestion()
    {
        if ($this->action === 'follow' && $this->target_type === 'question') {
            Question::updateAllCounters(['follow_count' => 1], ['id' => $this->target_id]);
        }
    }

    private function likeAnswer()
    {
        if ($this->action === 'like' && $this->target_type === 'answer') {
            Answer::updateAllCounters(['like_count' => 1], ['id' => $this->target_id]);
        }
    }

    private function favoriteAnswer()
    {
        if ($this->action === 'favorite' && $this->target_type === 'answer') {
            Answer::updateAllCounters(['favorite_count' => 1], ['id' => $this->target_id]);
        }
    }

    public function getTarget()
    {
        switch ($this->target_type) {
            case 'answer':
                return Answer::findOne($this->target_id);
            case 'question':
                return Question::findOne($this->target_id);
            default:
                return null;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }*/
}
