<?php

namespace app\models;

use app\models\ActiveRecords\File;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFileForm extends Model
{
    public $files;
    public $idParent;
    public $errMessage = 'Error';

    public function rules()
    {
        return [
            [['files'],  'file', 'extensions' => 'png, jpg, jpeg, mp3, vaw, bmp, exe, txt, doc, zip, rar, docx, tif, docm, dot, dotx, epub, fb2, ibooks, indd, xps, xltx, xltm, xlt, xlsx, xlsb, xls,  pdf, psd, gif',
                //'checkExtensionByMimeType' => false,
                'skipOnEmpty' => false,
                'maxFiles' => 5],
            ['idParent', 'integer'],
            ['idParent', 'checkAccess'],
        ];
    }

    //Check access
    public function checkAccess($attribute,$params)
    {
        if ($this->idParent != null) {
            $dir = File::findOne($this->idParent);
            if ($dir && $dir->id_user != Yii::$app->user->id) {
                $this->addError('files', 'No access');
            }
        }
    }

    //Upload files
    public function upload()
    {
        if (!$this->files instanceof UploadedFile) $this->files = UploadedFile::getInstances($this, 'files');
        if (!$this->validate()) return false;

        if (!$this->idParent) $this->idParent = null;

        foreach ($this->files as $file) {

            //Check on over size
            if (File::getTotalSize() + $file->size >= File::MAX_SIZE) {
                $this->errMessage = 'The storage is full. You need clear it.';
                return false;
            }

            $newFile = new File();
            $newFile->save();

            $newFile->title = File::getUniqueName($file->baseName, $this->idParent, 'file'); //$name, $idParent, $type
            $newFile->id_user = Yii::$app->user->id;
            $newFile->id_parent = $this->idParent;
            $newFile->size = $file->size;
            $newFile->type = File::TYPE_FILE;
            $newFile->extension = $file->extension;

            $pathStore = Yii::getAlias('@app') . '/web/store';
            $pathUser = Yii::getAlias('@app') . '/web/store/user' . Yii::$app->user->id;
            $fileName = 'file' . $newFile->id;
            $fullPath = $pathUser . '/' . $fileName . '.' . $newFile->extension;

            //Create necessary folders
            if (!is_dir($pathStore)) mkdir($pathStore);
            if (!is_dir($pathUser)) mkdir($pathUser);

            //Save file
            $file->saveAs( $fullPath );
            $newFile->save();
        }

        return true;
    }

    public function getErrorMessage()
    {
        if (isset($this->getFirstErrors()['files']) && $this->getFirstErrors()['files']) {
            return $this->getFirstErrors()['files'];
        } else {
            return $this->errMessage;
        }
    }
}