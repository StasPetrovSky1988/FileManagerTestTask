<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body class="d-flex flex-column h-100">

<?php $this->beginBody() ?>

<header id="header">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a href="/files-mngr/folder" class="text-white" style="font-size: 150%;">File Manager</a>
            <div style="display: block; text-align: right;Q">
                <div class="text-white"><?= Yii::$app->user->identity->username ?></div>
                <a class="" href="/logout">Log out</a>
            </div>
        </div>
    </nav>
</header>

<?php if (!empty(Yii::$app->params['breadcrumbs'])): ?>

<div style="background-color: #cdcdcd;">
    <div class="container fst-italic">
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'My storage',
                'url' => '/files-mngr',
            ],
            'links' => Yii::$app->params['breadcrumbs']
        ])
        ?>
    </div>
</div>

<?php endif ?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <br>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-dark">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My test task <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end">By Stas Petrov</div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>
