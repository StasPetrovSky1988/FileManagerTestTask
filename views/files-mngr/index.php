<section class="row">
    <div class="col-xl-9 col-lg-12">
        <div style="display:flex; align-items: flex-start;">
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" style="min-width: 190px;"
                    data-bs-target="#createFolderModal">Create new folder</button>
            <!-- Modal -->
            <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <?php
                        use yii\helpers\Html;
                        use yii\widgets\ActiveForm;
                        $form = ActiveForm::begin(['id' => 'contact-form', 'action' =>['file/create-folder']]); ?>
                        <div class="modal-header">
                            <h5 class="modal-title" >Name folder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div style="display: none;"><?= $form->field($createFolderForm, 'idfolder')->hiddenInput(['value' => $currentId]) ?></div>
                            <?= $form->field($createFolderForm, 'name')->textInput(['value' => 'New folder']) ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <?= Html::submitButton('Create folder', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <?php $contentForm = ActiveForm::begin(['action' =>['file/upload-file'], 'options' => ['class' => 'file-form-upload'],]); ?>
            <?= $form->field($uploadFilesForm, 'idParent')->hiddenInput(['value' => $currentId])->label(false) ?>
            <div class="file-input-btn" style="margin-left: 15px;">
                    <?php echo $contentForm->field($uploadFilesForm, 'files',  [
                        'template' => "<span class='d-none'>{input}</span>{label}{error}",
                        'labelOptions' => [ 'class' => 'btn btn-primary' ],
                        'inputOptions' => [ 'class' => 'fileInputFormInput' ],
                        'enableClientValidation' => false,
                    ])->fileInput([
                        'enctype' => 'multipart/form-data',
                        'multiple' => 'true',
                    ],)->label('Upload files');?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
    <div class="col" >
        <label style="display:block;" for="file">Resource used:</label>
        <progress style="width: 100%; " max="<?=\app\models\ActiveRecords\File::MAX_SIZE?>" value="<?=$totalSize?>" ></progress>
    </div>

</section>
<hr>
<div class="row">
    <div class="col-md-9">
        <?php if (count($folders) == 0 && count($files) == 0):?>
            <h1 class="fst-italic" style="color:gray;">Upload your files :)</h1>
        <?php endif;?>
        <section>
            <div class="row" style="padding: 15px;">
                <?php foreach ($folders as $folder):?>
                    <!--List item folder-->
                    <div class="col-md-2 col-sm-4 col-xs-6 d-flex justify-content-center" style="position:relative;">
                        <a href="/file/delete-item?id=<?= $folder->id;?>" class=" folder-list-item-delete-href" style=""><i class="fa-solid fa-xmark"></i></a>
                        <a href="/files-mngr/folder?id=<?= $folder->id;?>" class="text-center folder-list-item-href">
                            <i  style="" class="fa-regular fa-folder-closed folder-list-item-img"></i>
                            <div style="max-width: 250px;"> <?= $folder->title;?> </div>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        </section>
        <section>
            <div class="row">
                <?php foreach ($files as $file):?>
                    <?= $this->render('/layouts/fileItem', ['model' => $file, 'currentIdFolder' => $currentId]);?>
                <?php endforeach;?>
            </div>
        </section>
    </div>
    <div class="col" style="background-color: #dbdee5; padding: 15px; border-radius: 10px;">
        <?= $this->render('/layouts/informationBlock', ['fileId' => $fileId, 'currentIdFolder' => $currentId]);?>
    </div>
</div>