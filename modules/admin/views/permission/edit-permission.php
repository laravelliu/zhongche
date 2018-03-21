<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/15
 * Time: 18:00
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '编辑权限';

//面包屑
$this->params['breadcrumbs'][] = '人员管理';
$this->params['breadcrumbs'][] = '其他管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <a href="<?=Url::to(['permission/index'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>权限列表
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

                    <?=$form->field($model,'name')->textInput(['placeholder' => '请输入权限路径'])->label('权限路径：');?>
                    <?=$form->field($model,'display_name')->textInput(['placeholder' => '请输入权限名称'])->label('权限名称：');?>
                    <?=$form->field($model,'parent_id')->dropDownList($permissionList, ['prompt' => '请选择'])->label('所属权限组：');?>

                </div>
                <div class="box-footer">
                    <div class="col-xs-3 col-sm-2 text-right"></div>
                    <div class="col-xs-9 col-sm-7">
                        <?=Html::submitButton('编辑',['class' => 'btn btn-info'])?>
                    </div>
                </div>
                <?php ActiveForm::end();?>

                <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->