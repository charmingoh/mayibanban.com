<?php
namespace common\models;

use Overtrue\Pinyin\Pinyin;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $avatar
 * @property string $gender
 * @property string $email
 * @property string $description
 * @property string $view_count
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['alias', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            [['alias', 'name'], 'required'],
            [['alias', 'name', 'email', 'description'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 512],
            [['gender'], 'string', 'max' => 8],
            //['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * Finds user by alias
     *
     * @param string $alias
     * @return static|null
     */
    public static function findByAlias($alias)
    {
        return static::findOne(['alias' => $alias, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Answer::className(), ['created_by' => 'id'])->orderBy('created_at desc');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function generateValue()
    {
        $this->generateAuthKey();
        $this->generateAlias();
    }

    /**
     * Generates "remember me" authentication key
     */
    protected function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    protected function generateAlias()
    {
        $alias = $this->onlyChineseAndLetters($this->name);
        $pinyin = new Pinyin();
        $alias = $pinyin->sentence($alias);
        $alias = str_replace(' ', '-', $alias) . '-' . rand(100, 999);
        $this->alias = $alias;
    }

    private function onlyChineseAndLetters($chars, $encoding = 'utf8')
    {
        //$pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u' : '/[\x80-\xFF]/';
        $pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z]/u' : '/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);
        return strtolower($temp);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'name'        => '用户姓名',
            'avatar'      => '用户头像',
            'gender'      => '性别',
            'email'       => 'Email',
            'description' => '自我描述',
            'auth_key'    => 'Auth Key',
            'status'      => 'Status',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }
}
