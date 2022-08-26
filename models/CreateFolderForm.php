<?php
namespace app\models;

use app\models\ActiveRecords\File;
use Yii;
use yii\base\Model;

class CreateFolderForm extends Model
{
    public $idfolder;
    public $name;

    public function rules()
    {
        return [
            [['idfolder'], 'checkAccess'],
            [['idfolder'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    //check access
    public function checkAccess($attribute,$params)
    {
        if ($this->idfolder != null) {
            $folder = File::findOne($this->idfolder);
            if ($folder && $folder->id_user != Yii::$app->user->id) {
                $this->addError('name', 'No access');
            }
        }
    }

    //Create folder function
    public function save()
    {
        if (!$this->validate()) return false;

        $model = new File();

        $model->title = File::getUniqueName($this->name, $this->idfolder, File::TYPE_FOLDER);
        $model->id_user = Yii::$app->user->id;
        $model->id_parent = $this->idfolder;
        $model->size = 0;
        $model->type = 'folder';
        $model->extension = null;

        if ($model->save()) return true;
    }
}