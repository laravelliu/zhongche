<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/5/2
 * Time: 21:21
 */
use app\widgets\JsBlock;
use yii\helpers\Url;

$this->title = '分配质检流程';

//面包屑
$this->params['breadcrumbs'][] = '质检管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">质检项组名称：<span><?=$group['name']?></span></h3>
                    <div class="box-tools">
                        <a href="<?=Url::to(['quality/quality-group'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>质检项组列表
                        </a>
                    </div>

                </div>

                <div class="box-body">
                    <div class="col-md-6 pull-left">
                        <h5>未选择质检流程</h5>
                        <select multiple="multiple" id="unSelect" style="width:100%;height: 350px;">
                            <?php if(!empty($qualityItem['unSelect'])):?>
                                <?php foreach ($qualityItem['unSelect'] as $k => $v):?>
                                    <option value="<?=$k?>"><?=$v?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                        <div style="margin-top: 20px;">
                            <div class="col-md-4 pull-left">
                                <a class="btn btn-block btn-success pull-left" id="add">选中添加&gt;&gt;</a>
                            </div>
                            <div class="col-md-4 pull-right">
                                <a class="btn btn-block btn-success" id="add_all" >全部添加&gt;&gt;</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 pull-left">
                        <h5>已选择质检流程</h5>
                        <select multiple="multiple" id="selected" style="width: 100%;height: 350px;">
                            <?php if(!empty($qualityItem['selected'])):?>
                                <?php foreach ($qualityItem['selected'] as $k => $v):?>
                                    <option value="<?=$k?>"><?=$v?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>

                        <div style="margin-top: 20px;">
                            <div class="col-md-4 pull-left">
                                <a class="btn btn-block btn-danger" id="remove">&lt;&lt;选中删除</a>
                            </div>
                            <div class="col-md-4 pull-right">
                                <a class="btn btn-block btn-danger" id="remove_all">&lt;&lt;全部删除</a>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 20px;" class="col-md-12 col-sm-offset-5">
                        <a id="save_info" style="width: 100px;" class="btn btn-block btn-success">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<?php JsBlock::begin();?>
<script>
    $(document).ready(function () {
        selectInfo.init('<?=json_encode($qualityItem)?>');
    });
</script>
<?php JsBlock::end();?>
