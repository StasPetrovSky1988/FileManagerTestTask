<?php
namespace app\controllers;

use app\models\ActiveRecords\File;
use app\models\CreateFolderForm;
use app\models\UploadFileForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class FilesMngrController  extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    public function actionIndex()
    {
        return $this->redirect('/files-mngr/folder');
    }

    //Folder action
    public function actionFolder($id = null, $file = null)
    {
        if (!$id) $id = null;
        if (!$file) $file = null;

        if ($id) File::findModel($id);

        $createFolderForm = new CreateFolderForm();
        $parentFolders = array_reverse(File::getParentsFolder($id));

        foreach ($parentFolders as $folder) {
            Yii::$app->params['breadcrumbs'][] = [
                'label'=> $folder->title,
                'url'=>Url::toRoute('/files-mngr/folder?id='.$folder->id)
            ];
        }

        $totalSize = File::getTotalSize();

        $uploadFilesForm = new UploadFileForm();

        $folders = File::find()->where(['id_parent' => $id])->andWhere(['type' => File::TYPE_FOLDER])->andWhere(['id_user' => Yii::$app->user->id])->all();
        $files = File::find()->where(['id_parent' => $id])->andWhere(['type' => File::TYPE_FILE])->andWhere(['id_user' => Yii::$app->user->id])->all();

        return $this->render('index', [
            'createFolderForm' => $createFolderForm,
            'currentIdFolder' => $id,
            'folders' => $folders,
            'files' => $files,
            'currentId' => $id,
            'totalSize' => $totalSize,
            'fileId' => $file,
            'uploadFilesForm' => $uploadFilesForm,
        ]);
    }
}