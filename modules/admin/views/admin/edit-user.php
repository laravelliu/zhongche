<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/21
 * Time: 16:48
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '更改用户信息';

//面包屑
$this->params['breadcrumbs'][] = '人员管理';
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

                    <?=$form->field($model,'name')->textInput(['disabled' => 'disabled'])->label('名称：');?>
                    <?=$form->field($model,'department_id')->dropDownList($department,['prompt'=>'请选择部门'])->label('部门：');?>

                    <?php if($isStaff):?>
                        <?=$form->field($model,'group_id')->dropDownList($group,['prompt'=>'请选择员工组'])->label('员工组：');?>
                    <?php endif;?>

                    <?php if($isNeedWorkshop):?>
                    <?=$form->field($model,'workshop_id')->dropDownList($workshop, ['prompt'=>'请选择车间'])->label('车间：');?>
                    <?php endif;?>

                    <?=$form->field($model,'is_admin')->radioList([0 => '否', 1 => '是'])->label('能否访问后台：');?>

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
