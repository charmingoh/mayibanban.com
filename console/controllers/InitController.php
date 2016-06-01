<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/4/12
 * Time: 下午4:43
 */

namespace console\controllers;

use common\models\Question;
use common\models\SiteLog;
use common\models\User;
use common\models\UserAction;
use Faker\Factory;
use Overtrue\Pinyin\Pinyin;
use yii\console\Controller;
use yii\validators\UrlValidator;

class InitController extends Controller
{
    public function actionAlias()
    {
        $pinyin = new Pinyin();
        $users = User::find()->all();
        /* @var $user User */
        foreach ($users as $user) {
            $alias = $pinyin->sentence($this->filter_special_chars($user->name));
            $alias = str_replace(' ', '-', $alias) . '-' . rand(100, 999);
            echo "$alias\n";
            $user->alias = $alias;
            $user->save();
        }
    }

    /**
     * 只保留中文和字母
     * @param $chars
     * @param string $encoding
     * @return mixed
     */
    function filter_special_chars($chars, $encoding = 'utf8')
    {
        //$pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u' : '/[\x80-\xFF]/';
        $pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z]/u' : '/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);
        return strtolower($temp);
    }

    public function actionTestDo()
    {
        $userAction = new UserAction();
        $userAction->action = 'like';
        $userAction->target_type = 'answer';
        $userAction->target_id = 4;

        if ($userAction->targetExists()) {
            echo 'exists';
        } else {
            echo 'not exists';
        }
    }

    public function actionUrlTitle()
    {
        $url = $this->prompt('enter url:');
        $urlValidator = new UrlValidator();
        if (!$urlValidator->validate($url)) {
            return 1;
        }

        $beginTime = microtime();
        $htmlContent = file_get_contents($url);
        $notUTF8Charset = strripos($htmlContent, 'UTF-8') === false;
        if ($notUTF8Charset) {
            $htmlContent = iconv("GBK", "UTF-8", $htmlContent);
        }
        
        $titleBegin = strpos($htmlContent, '<title>') + 7;
        $titleEnd = strpos($htmlContent, '</title>');
        $titleLength = $titleEnd - $titleBegin;
        echo substr($htmlContent, $titleBegin, $titleLength);
        echo "\n";
        echo microtime() - $beginTime;
        return 0;
    }

    public function actionSiteLog()
    {
        $faker = Factory::create('zh_CN');

        for ($i = 2; $i <= 70; $i++) {
            $siteLog = new SiteLog();
            $siteLog->detail = $faker->realText(200);
            $siteLog->version = 'v0.1.' . $i;
            $siteLog->created_by = 1;
            $siteLog->save();
        }
    }

    public function actionQuestionViewCount()
    {
        $faker = Factory::create('zh_CN');

        for ($i = 1; $i <= 70; $i++) {
            if (($question = Question::findOne(1)) !== null) {
                $question->view_count = $faker->randomNumber();
                echo $i . ': ' . $question->view_count . ' | ';
                $question->save();
            }
        }
    }
    
    public function actionQuestion()
    {
        $faker = Factory::create('zh_CN');

        $count = $this->prompt("how many questions do you want to generate:");
        for ($i = 0; $i < $count; $i++) {
            $question = new Question();
            
            $question->title = '如何优雅地去' . $faker->address . ', 求比较好的攻略?';
            $question->detail = $faker->realText(140);
            $question->created_by = 1;
            $question->updated_by = 1;

            if ($question->validate()) {
                $question->save();
            }
        }
    }

    public function actionUser()
    {
        echo "create init user...\n";
        $username = $this->prompt('username: ');
        $email = $this->prompt('email for ' . $username . ': ');
        $password = $this->prompt('password for ' . $username . ': ');

        $model = new User();
        $model->username = $username;
        $model->email = $email;
        $model->password = $password;

        if (!$model->save()) {
            foreach ($model->getErrors() as $errors) {
                foreach ($errors as $error) {
                    echo "$error\n";
                }
            }
            return 1;
        }
        return 0;
    }
}