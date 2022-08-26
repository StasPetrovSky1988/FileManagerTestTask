<?php

namespace app\controllers;

use app\models\ActiveRecords\File;
use app\models\CreateFolderForm;
use app\models\UploadFileForm;
use common\models\activeRecord\Documents;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class FileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@'], ],
                ],
            ],
        ];
    }

    //Create folder
    public function actionCreateFolder()
    {
        $folderForm = new CreateFolderForm();
        if ($folderForm->load(Yii::$app->request->post()) && $folderForm->save() ) {
            Yii::$app->session->addFlash('success' ,'Folder has been added');
        } else {
            Yii::$app->session->addFlash('danger' ,$folderForm->getFirstErrors()['name']);
        }

        return $this->redirect('/files-mngr/folder?id=' . $folderForm->idfolder);
    }

    //Upload file
    public function actionUploadFile()
    {
        $modelUpload = new UploadFileForm();

        $modelUpload->files = UploadedFile::getInstancesByName('files');
        $modelUpload->load(Yii::$app->request->post());

        if ($modelUpload->upload()) {
            Yii::$app->session->addFlash('success' ,'File downloaded');
        } else {
            Yii::$app->session->addFlash('danger' , $modelUpload->getErrorMessage());
        }

        if($modelUpload->idParent)  return $this->redirect('/files-mngr/folder?id=' . $modelUpload->idParent);
        return $this->goBack();
    }

    //Delete file or folder
    public function actionDeleteItem($id)
    {
        $model = $this->findModel($id);
        $parent_id = $model->id_parent;

        if ($model && $model->recursiveDelete()) {
            Yii::$app->session->addFlash('success' ,'Item has been removed');
        } else {
            Yii::$app->session->addFlash('danger' , 'Error');
        }
        if($parent_id)  return $this->redirect('/files-mngr/folder?id=' . $parent_id);
        return $this->goBack();
    }

    //Down load file
    public function actionDownload($id)
    {
        $file = File::findModel($id);
        if ($file->id_user != Yii::$app->user->id) throw new NotFoundHttpException('Access error');
        return $file->download();
    }

    //Share file
    public function actionShare($id)
    {
        $file = File::findModel($id);
        $file->share_date = (new DateTime())->getTimestamp() + File::TIME_SHARE_ACCESS;
        $file->save();
        Yii::$app->session->addFlash('success' ,'File will be available 1 hour. <a href="' . $file->getSharePath() . '">' . $file->getSharePath() . '</a>');

        return $this->redirect('/files-mngr/folder?id=' . $file->id_parent . '&file=' . $file->id);

    }

    //Find file or folder
    public function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) return $model;
        throw new NotFoundHttpException('Item not found');
    }
}