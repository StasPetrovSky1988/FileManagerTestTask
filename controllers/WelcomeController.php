<?php

namespace app\controllers;

use app\models\ActiveRecords\File;
use app\models\ForgotForm;
use app\models\LoginForm;
use app\models\RegisterForm;
use DateTime;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class WelcomeController extends Controller
{
    public $layout = 'welcome';

    public function beforeAction($action)
    {
        if ($action->id == 'error' && !Yii::$app->user->isGuest) {
            $this->layout = 'main';
        }

        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['files-mngr/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    //Recovery password via email
    public function actionForgot()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->addFlash('warning' ,'You are already registered', false);
            return $this->goHome();
        }

        $model = new ForgotForm();
        if ($model->load(Yii::$app->request->post()) && $model->send()) {
            //Yii::$app->session->addFlash('success' ,'Check you email and set new password', false);
            //return $this->goHome();
        }

        Yii::$app->session->addFlash('danger' ,'This function is under development. Sorry.', false);

        return $this->render('forgot', [
            'model' => $model,
        ]);
    }

    //Register new user
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->addFlash('warning' ,'You are already registered', false);
            return $this->goBack();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->addFlash('success' ,'You can login with your email/password', false);
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    //Logout
    public function actionLogout() {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        Yii::$app->user->logout();
        Yii::$app->session->addFlash('success' ,'You\'re logout' , false);
        return $this->goHome();
    }

    //Get shared file
    public function actionGetSharedFile($token) {
        $file = File::findModel($token);

        if ($file && $file->share_date && $file->share_date > (new DateTime())->getTimestamp()) {
            return $file->download();
        }

        throw new NotFoundHttpException('Time is over');
    }
}