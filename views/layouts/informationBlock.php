<?php
use app\models\ActiveRecords\File;

$model = File::findOne($fileId);
?>

<?php if ($model) :?>

    <div style="font-size: 200%; text-align: center; padding: 15px;">Information:</div>
    <div class="text-center">
        <hr>
        <div>Name: <?=$model->title;?></div>
        <div>Size: <?=$model->size;?> bytes</div>
        <div>Date of upload: <?= date("d-m-Y H:m:s",$model->created_at);?></div>
        <div>Share access: <?= $model->getSharePath() ? '<a href="' . $model->getSharePath() . '">' . $model->getSharePath() . '</a>' : 'None' ;?></div>
        <hr>
        <a type="button" class="btn btn-outline-dark d-block" href="/file/download?id=<?=$model->id;?>">Download</a>
        <a type="button" class="btn btn-outline-dark d-block" href="/file/share?id=<?=$model->id;?>" style="margin-top: 15px;">Share</a>
        <a type="button" class="btn btn-outline-danger d-block" href="/file/delete-item?id=<?=$model->id;?>" style="margin-top: 15px;">Delete</a>
    </div>

<?php else:?>

    <div style="font-size: 200%; text-align: center; padding: 15px;">Information:</div>
    <div class="text-center">
        Select file please
    </div>

<?php endif;?>