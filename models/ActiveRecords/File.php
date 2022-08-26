<?php
namespace app\models\ActiveRecords;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $status
 * @property int|null $id_user
 * @property int|null $id_parent
 * @property int|null $size
 * @property string|null $type
 * @property string|null $extension
 * @property int|null $share_date
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property File[] $files
 * @property File $parent
 * @property User $user
 */
class File extends \yii\db\ActiveRecord
{
    const TYPE_FOLDER = 'folder';
    const TYPE_FILE = 'file';
    const TIME_SHARE_ACCESS = 3600; //Seconds when file will be access to other
    const MAX_SIZE = 100000000; //Size of storage

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'id_user', 'id_parent', 'size', 'share_date', 'created_at', 'updated_at'], 'integer'],
            [['title', 'type', 'extension'], 'string', 'max' => 255],
            [['id_parent'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['id_parent' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status' => 'Status',
            'id_user' => 'Id User',
            'id_parent' => 'Id Parent',
            'size' => 'Size',
            'type' => 'Type',
            'extension' => 'Extension',
            'share_date' => 'Share Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['id_parent' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(File::className(), ['id' => 'id_parent']);
    }

    public function getFolder()
    {
        return self::getParent();
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    //Remove file from storage
    public function removeFile()
    {
        if (is_file(self::getHardPath())) unlink(self::getHardPath());
        return true;
    }

    //Recursively delete item
    public function recursiveDelete()
    {
        if ($this->type == self::TYPE_FOLDER) {
            $files = $this->getFilesHere();
            foreach($files as $file) {
                $file->removeFile();
                $file->delete();
            }

            $folders = $this->getFoldersHere();
            foreach($folders as $dir) {
                if (count(self::getFoldersHere()) > 0) {
                    $dir->recursiveDelete();
                } else {
                    $dir->removeFile();
                    $dir->delete();
                }
            }
        }

        $this->removeFile();
        $this->delete();

        return true;
    }

    //Get folders from current dir
    public function getFoldersHere()
    {
        return File::find()->where(['id_parent' => $this->id])->andWhere(['type' => self::TYPE_FOLDER])->andWhere(['id_user' => Yii::$app->user->id])->all();
    }

    //Get files from current dir
    public function getFilesHere()
    {
        return File::find()->where(['id_parent' => $this->id])->andWhere(['type' => self::TYPE_FILE])->andWhere(['id_user' => Yii::$app->user->id])->all();
    }

    //Download file
    public function download()
    {
        if ($this->id_user != Yii::$app->user->id) throw new NotFoundHttpException('Access error');
        return Yii::$app->response->sendFile(self::getHardPath());
    }

    //Get unique name for file(1) or folder(1)
    public static function getUniqueName($name, $idParent, $type, $i = 0)
    {
        if(!$idParent) $idParent = null;

        $folder = File::find()
            ->where(['id_parent' => $idParent])
            ->andWhere(['id_user' => Yii::$app->user->id])
            ->andWhere(['type' => $type])
            ->andWhere(['title' => $name])
            ->one();

        if (!$folder) {
            return $name;

        } else {
            preg_match('/\(([^)]+)\)+$/', $name, $match);

            if (!isset($match[0])) {
                return self::getUniqueName($name . '(1)', $idParent, $type, ++$i);
            } else {
                $сutLength = strlen((String) $match[0]);

                $nameLength = strlen($name);

                $uniqueName = mb_strimwidth($name, 0,$nameLength - $сutLength);
                $uniqueName .= '(' . ++$i . ')';

                return self::getUniqueName($uniqueName, $idParent, $type, $i);
            }
        }
    }

    //Get folders chain of parents
    public static function getParentsFolder($id) : array
    {
        $folders = [];
        $folder = File::find()->where(['id' => $id])->andWhere(['type' => 'folder'])->andWhere(['id_user' => Yii::$app->user->id])->one();

        if ($folder) {
            $folders[] = $folder;

            if (is_numeric($folder->id_parent) && count($folders) < 10) {
                $folders = array_merge($folders, self::getParentsFolder($folder->id_parent));
            }
        }
        return $folders;
    }

    //File path on server
    public function getHardPath()
    {
        return Yii::getAlias('@app') . '/web/store/user' . $this->id_user . '/file' . $this->id . '.' . $this->extension;
    }

    //Path by web interface
    public function getWebPath()
    {
        return Yii::$app->request->hostInfo . '/store/user' . $this->id_user . '/file' . $this->id . '.' . $this->extension;
    }

    //Get share path
    public function getSharePath()
    {
        if ($this->share_date && $this->share_date > (new DateTime())->getTimestamp()) {
            return Yii::$app->request->hostInfo . '/welcome/get-shared-file?token=' . $this->id;
        }
    }

    //Sum total size
    public static function getTotalSize()
    {
        $totalSize = 0;
        foreach(self::find()->all() as $file) {
            $totalSize += $file->size;
        }

        return $totalSize;
    }

    //Find model
    public static function findModel($id)
    {
        $model = self::findOne($id);
        if (!$model) throw new NotFoundHttpException('Not found');

        return $model;
    }
}
