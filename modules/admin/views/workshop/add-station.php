<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/23
 * Time: 21:02
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '添加工位';

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
                        <a href="<?=Url::to(['workshop/station'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>工位列表
                        </a>
                    </div>
                </div>
                <!-- /.box-header -->
                <?php $form = ActiveForm::begin([
                    'id' => 'add-workshop',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "<div class='col-xs-3 col-sm-2 text-right'>{label}</div><div class='col-xs-9 col-sm-7'>{input}</div><div class='col-xs-12 col-xs-offset-3 col-sm-3 col-sm-offset-0'>{error}</div>",
                    ]
                ])?>
                <div class="box-body">

                    <?=$form->field($model,'name')->textInput(['placeholder' => '请输入工位名称：除锈车间'])->label('工位名称：');?>
                    <?=$form->field($model,'code')->textInput(['placeholder' => '请输入工位编号：ba123'])->label('工位编号：');?>
                    <?=$form->field($model,'workshop_id')->dropDownList($wsModel->getWorkshop(),['prompt' =>'--请选择车间--', 'onchange' => '
                        $.post("'.yii::$app->urlManager->createUrl('admin/workshop/get-work-area-list').'?wsid="+$(this).val(),function(data){
                            if (data.code == 0) {
                                var htmlInfo = "<option value=\"\">--请选择产线--</option>";
                                for(var wp in data.data.workArea){
                                    htmlInfo = htmlInfo + "<option value=\""+wp+"\">"+data.data.workArea[wp]+"</option>"
                                }
                                $("select#stationar-work_area_id").html(htmlInfo);
                            } else {
                                alert(data.message)
                            } 
                            
                        });'
                    ])->label('所属车间：');?>
                    <?=$form->field($model,'work_area_id')->dropDownList($wsModel->getWorkArea($model->workshop_id),['prompt' =>'--请选择产线--','onchange' => '
                        $.post("'.yii::$app->urlManager->createUrl('admin/workshop/get-station-list').'?waid="+$(this).val(),function(data){
                            if (data.code == 0) {
                                var htmlInfo = "<option value=\"\">--请选择上一工位--</option>";
                                for(var wp in data.data.station){
                                    htmlInfo = htmlInfo + "<option value=\""+wp+"\">"+data.data.station[wp]+"</option>"
                                }
                                
                                $("select#stationar-pid").html(htmlInfo);
                            } else {
                                alert(data.message)
                            } 
                            
                        });'])->label('所属产线：');?>
                    <?=$form->field($model,'pid')->dropDownList($wsModel->getStation($model->work_area_id),['prompt' =>'--请选择上一工位--'])->label('上一工位：');?>

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