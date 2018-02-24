<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/23
 * Time: 09:38
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '添加产线';

//面包屑
$this->params['breadcrumbs'][] = '厂房管理';
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
                        <a href="<?=Url::to(['workshop/work-area'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>产线列表
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

                    <?=$form->field($model,'name')->textInput(['placeholder' => '请输入产线名称：产线001'])->label('产线名称：');?>
                    <?=$form->field($model,'code')->textInput(['placeholder' => '请输入产线编号：123'])->label('产线编号：');?>
                    <?=$form->field($model,'workshop_id')->dropDownList($workshop, ['placeholder' => '请选择车间'])->label('所属车间：');?>

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
<!-- /.content -->
