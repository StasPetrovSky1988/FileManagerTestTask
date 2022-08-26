<?php if($model->extension == 'jpg' || $model->extension == 'jpeg' || $model->extension == 'png') : ?>
    <!--List item file-->
    <div class="col-md-2 col-sm-4 col-xs-6  d-flex justify-content-center" style="position:relative;">
        <a href="/file/delete-item?id=<?=$model->id;?>" class="folder-list-item-delete-href" style=""><i class="fa-solid fa-xmark"></i></a>
        <a href="/files-mngr/folder?id=<?=$currentIdFolder?>&file=<?=$model->id;?>" class="text-center" style="width: 100%;">
            <div style="min-height: 100px; width: 100%; background-position: center; background-size: cover; background-image: url(<?=$model->getWebPath()?>)"></div>
            <div style="max-width: 150px; overflow-wrap: break-word;"> <?=$model->title . '.' . $model->extension;?> </div>
        </a>
    </div>
<?php else :?>
    <!--List item file-->
    <div class="col-md-2 col-sm-4 col-xs-6  d-flex justify-content-center" style="position:relative;">
        <a href="/file/delete-item?id=<?=$model->id;?>" class="folder-list-item-delete-href" style=""><i class="fa-solid fa-xmark"></i></a>
        <a href="/files-mngr/folder?id=<?=$currentIdFolder?>&file=<?=$model->id;?>" class="text-center file-list-item-href">
            <i class="fa-regular fa-file-lines file-list-item-img"></i>
            <div style="max-width: 150px; overflow-wrap: break-word;"> <?=$model->title . '.' . $model->extension;?> </div>
        </a>
    </div>
<?php endif;?>