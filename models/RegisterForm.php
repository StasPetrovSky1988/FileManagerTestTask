<?php

namespace app\models;

use app\models\ActiveRecords\User;
use Yii;
use yii\base\Model;

/* Form validation for users register */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['email'], 'email'],
            [['email'], 'checkUniqueEmail'],
            [['password'], 'string', 'min' => 8],
        ];
    }

    //Check unique email
    public function checkUniqueEmail($attribute,$params) {
        if (User::findByEmail($this->email)) {
            $this->addError('email', 'This email is already registered');
        }
    }

    //Register
    public function register()
    {
        if (!$this->validate()) return false;

        $newUSer = new User();

        $newUSer->username = $this->username;
        $newUSer->auth_key = Yii::$app->security->generateRandomString();
        $newUSer->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $newUSer->status = User::STATUS_ACTIVE;
        $newUSer->email = $this->email;

        if (!$newUSer->save()) {
            dump($newUSer->getFirstErrors()); die;
            Yii::$app->session->addFlash('danger' ,'You are already registered', false);
            return false;
        }

        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($this->email)
            ->setSubject('Registration on File Manager')
            //->setTextBody('Thanks for registration on my File Manager')
            ->setHtmlBody('<b>Thanks for registration on my File Manager</b>')
            ->send();

        return true;
    }
}