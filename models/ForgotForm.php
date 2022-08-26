<?php

namespace app\models;

use yii\base\Model;

class ForgotForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    //Send email with token for change password
    public function send()
    {
        //TODO make the function
        return true;
    }
}