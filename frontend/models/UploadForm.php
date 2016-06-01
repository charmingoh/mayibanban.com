<?php
/**
 * Created by PhpStorm.
 * User: charming
 * Date: 16/4/26
 * Time: 上午10:56
 */

namespace frontend\models;


use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            //$this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->imageFile->saveAs('uploads/' . \Yii::$app->security->generateRandomString(24) . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}