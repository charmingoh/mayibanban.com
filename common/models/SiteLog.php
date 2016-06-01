<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "{{%site_log}}".
 *
 * @property integer $id
 * @property string $detail
 * @property string $version
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $is_deleted
 */
class SiteLog extends \yii\db\ActiveRecord
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
        return '{{%site_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detail', 'version'], 'required'],
            [['detail'], 'string', 'max' => 255],
            [['version'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '更新次数',
            'detail' => '更新细节',
            'version' => '版本号',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return int|string
     */
    public function getTodayLogCount()
    {
        return self::find()->andWhere(['>=', 'created_at', strtotime('today')])->count();
    }
}
