<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/1/25
 * Time: 09:12
 */

use app\assets\PublicAsset;
use app\widgets\JsBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

PublicAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!-- Tell the browser to be responsive to screen width -->
    <?php $this->head() ?>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
<?php $this->beginBody() ?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?=Url::to(Url::home())?>"><?=Yii::$app->name?></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">登录页面</p>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'options' => [
                    'class' => 'form-group has-feedback'
                ]
            ],
        ]) ?>
        <?= $form->field($model,'username', ['template' => "{input}<span class=\"glyphicon glyphicon-envelope form-control-feedback\"></span>{error}"])->textInput(['placeholder' => '邮箱'])->label(false);?>
        <?= $form->field($model,'password', ['template' => "{input}<span class=\"glyphicon glyphicon-lock form-control-feedback\"></span>{error}"])->textInput(['placeholder' => '密码'])->label(false);?>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe',['options' => ['class' => 'checkbox icheck']])->checkbox(['label'=>'记住我'])->label(false) ?>
            </div>

            <div class="col-xs-4">
                <?= Html::submitButton('登录',['class' => 'btn btn-primary btn-block btn-flat'])?>
            </div>
        </div>

        <?php ActiveForm::end()?>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
                Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
                Google+</a>
        </div>
        <!-- /.social-auth-links -->

        <a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php JsBlock::begin()?>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
<?php JsBlock::end()?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
