<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/5/15
 * Time: 下午3:02
 */

namespace common\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Profile model
 */
class Profile extends Model
{
    public $alias;
    public $name;
    public $avatar;
    public $gender;
    //public $email;
    public $description;
    public $view_count;
    public $created_at;

    private $_user;

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
        ];
    }

    /**
     * @param $alias
     * @return Profile
     */
    public static function createByAlias($alias)
    {
        $user = User::findByAlias($alias);
        if ($user == null) {
            return null;
        }

        $profile = new Profile([
            'alias' => $user->alias,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'description' => $user->description,
            'view_count' => $user->view_count,
            'created_at' => $user->created_at,
        ]);

        return $profile;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->getUser()->getAnswers();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->getUser()->getQuestions();
    }

    public function getFavoriteAnswers()
    {
        $user_id = $this->getUser()->id;
        $favoriteAnswerAction = UserAction::find()->andWhere([
            'created_by'  => $user_id,
            'action'      => 'favorite',
            'target_type' => 'answer'
        ])->orderBy(['created_at' => SORT_DESC])->asArray()->all();
        
        $favoriteAnswerIdArray = ArrayHelper::getColumn($favoriteAnswerAction, 'target_id');
        
        return Answer::find()->andWhere(['id' => $favoriteAnswerIdArray]);
    }

    public function getFavoriteAnswersCount()
    {
        $user_id = $this->getUser()->id;
        return UserAction::find()->andWhere([
            'created_by'  => $user_id,
            'action'      => 'favorite',
            'target_type' => 'answer'
        ])->count();
    }

    public function getUserLikeCount()
    {
        $answerArray = $this->getAnswers()->asArray()->all();
        $answerIdArray = ArrayHelper::getColumn($answerArray, 'id');

        return UserAction::find()->andWhere([
            'action' => 'like',
            'target_type' => 'answer',
            'target_id' => $answerIdArray,
        ])->count();
    }

    public function getUserFavoriteCount()
    {
        $answerArray = $this->getAnswers()->asArray()->all();
        $answerIdArray = ArrayHelper::getColumn($answerArray, 'id');

        return UserAction::find()->andWhere([
            'action' => 'favorite',
            'target_type' => 'answer',
            'target_id' => $answerIdArray,
        ])->count();
    }

    public function getActions()
    {
        $actions = $this->getUser()->getActions();
        return $actions;
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByAlias($this->alias);
        }

        return $this->_user;
    }

    public function addViewCount()
    {
        $this->getUser()->updateCounters(['view_count' => 1]);
    }
}