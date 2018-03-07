<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/6
 * Time: 11:00
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '添加质检项组';

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
                        <a href="<?=Url::to(['quality/index'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>质检项列表
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

                    <?=$form->field($model,'name')->textInput(['placeholder' => '请输入质检项组名称'])->label('质检项组：');?>
                    <?=$form->field($model,'type_id')->dropDownList($qualityType)->label('质检项类别：');?>

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

