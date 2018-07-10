<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/7/10
 * Time: 09:26
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '添加人员';

//面包屑
$this->params['breadcrumbs'][] = '人员管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <a href="<?=Url::to(['admin/index'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>人员列表
                        </a>
                    </div>
                </div>
                <!-- /.box-header -->
                <?php $form = ActiveForm::begin([
                    'id' => 'add-work-area',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "<div class='col-xs-3 col-sm-2 text-right'>{label}</div><div class='col-xs-9 col-sm-7'>{input}</div><div class='col-xs-12 col-xs-offset-3 col-sm-3 col-sm-offset-0'>{error}</div>",
                    ]
                ])?>
                <div class="box-body">

                    <?=$form->field($model,'username')->textInput(['placeholder' => '用户登录的用户名称'])->label('用户名：');?>
                    <?=$form->field($model,'password_hash')->textInput(['placeholder' => '用于登录的密码'])->label('密码：');?>
                    <?=$form->field($model,'name')->textInput(['placeholder' => '名称'])->label('姓名：');?>
                    <?=$form->field($model,'phone')->textInput(['placeholder' => '手机号码'])->label('电话：');?>
                    <?=$form->field($model,'email')->textInput(['placeholder' => '邮箱'])->label('邮箱：');?>
                    <?=$form->field($model,'is_admin')->radioList([0 => '否', 1 => '是'])->label('是否可以登录后台：');?>

                </div>
                <div class="box-footer">
                    <div class="col-xs-3 col-sm-2 text-right"></div>
                    <div class="col-xs-9 col-sm-7">
                        <?=Html::submitButton('添加',['class' => 'btn btn-info'])?>
                    </div>
                </div>
                <?php ActiveForm::end();?>

                <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
</section>
