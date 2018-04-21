<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/27
 * Time: 10:29
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '初始化职能工位';

//面包屑
$this->params['breadcrumbs'][] = '质检管理';
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
                        <a href="<?=Url::to(['quality/job-station'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>职能工位列表
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
                    <div class="form-group">
                        <div class="col-xs-3 col-sm-2 text-right">
                            <label class="control-label" for="jobstationar-pid">所在车间：</label></div>
                            <div class="col-xs-9 col-sm-7">
                                <input type="text" class="form-control"  value="<?=$workshop?>" disabled="disabled">
                            </div>
                    </div>
                    <?=$form->field($model,'name')->textInput(['placeholder' => '请输入职能工位名称'])->label('职能工位名称：');?>
                    <?=$form->field($model,'pid')->dropDownList($station)->label('上一职能工位：');?>

                </div>
                <div class="box-footer">
                    <div class="col-xs-3 col-sm-2 text-right"></div>
                    <div class="col-xs-9 col-sm-7">
                        <?=Html::submitButton('修改',['class' => 'btn btn-info'])?>
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
