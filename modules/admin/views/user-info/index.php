<?php
/* @var $this yii\web\View */

use app\widgets\JsBlock;
use yii\widgets\ActiveForm;

$this->title = '个人详情';

//面包屑
$this->params['breadcrumbs'][] = '个人中心';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .users-list .active{
        border:1px solid #0c0c0c;
        color: aquamarine;
    }
</style>
<section class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <img class="profile-user-img img-responsive img-circle" src="<?=$this->params['userInfo']['admin_photo']?>" alt="User profile picture">

                    <h3 class="profile-username text-center"><?=$this->params['userInfo']['name']?></h3>
                    <p class="text-muted text-center">最近登录时间：<?=date('Y-m-d H:i:s',$this->params['userInfo']['last_time'])?></p>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>所属部门</b> <a class="pull-right"><?=$department?></a>
                        </li>
                        <li class="list-group-item">
                            <b>所属角色</b> <a class="pull-right"><?=$roleName?></a>
                        </li>
                        <li class="list-group-item">
                            <b>拥有权限数量</b> <a class="pull-right">13,287</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-default"><b>更改头像</b></a>
                </div>

                <div class="col-md-9" style="margin-top: 50px;">
                    <div class="tab-pane" id="settings">
                        <?php $form = ActiveForm::begin([
                            'options' => [ 'class' => 'form-horizontal'],
                            'fieldConfig' => [
                                'template' => "{label}<div class='col-sm-10'>{input}</div>{error}",
                                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            ]
                        ])?>
                        <?=$form->field($this->params['userInfo'],'username')->textInput(['disabled' => 'disabled'])->label('用户名：');?>
                        <?=$form->field($this->params['userInfo'],'name')->textInput(['disabled' => 'disabled'])->label('姓名：');?>
                        <?=$form->field($this->params['userInfo'],'email')->textInput()->label('邮箱：');?>
                        <?=$form->field($this->params['userInfo'],'phone')->textInput()->label('电话：');?>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-danger">保存</button>
                            </div>
                        </div>
                        <?php ActiveForm::end()?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-info fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">选择头像</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <!-- USERS LIST -->
                            <ul class="users-list clearfix">
                                <li>
                                    <img src="/images/admin/user1-128x128.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user8-128x128.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user7-128x128.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user6-128x128.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user2-160x160.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user5-128x128.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user4-128x128.jpg" alt="User Image">
                                </li>
                                <li>
                                    <img src="/images/admin/user3-128x128.jpg" alt="User Image">
                                </li>
                            </ul>
                            <!--/.box -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">保存</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php JsBlock::begin()?>
    <script>
        $(document).ready(function () {
            var oldUrl = null;
            var url = null;

            $('#modal-default').find('.users-list li').click(function () {
                var $this = this;
                $('.users-list li').removeClass('active');
                $(this).addClass('active');
                url =  $(this).find('img').attr('src');
            });

            //保存
            $('.modal-footer button').click(function () {

                if (oldUrl == url) {
                    alert('更改成功');
                    return false;
                }

               $.ajax({
                   url : '/admin/user-info/change-photo',
                   type: 'post',
                   dataType: "json",
                   data:{'url':url},
                   success:function (d) {
                       if (d.code == 0) {
                           alert('更改成功');
                           location.reload();
                       } else {
                           alert('更改失败');
                       }
                   }
               });

            });


        })
    </script>
    <?php JsBlock::end()?>

</section>
