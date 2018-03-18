<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/18
 * Time: 14:43
 */
use app\widgets\JsBlock;

$this->title = '分配质检项';

//面包屑
$this->params['breadcrumbs'][] = '质检管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4><?=$group['name']?></h4>
            </div>
            <div class="box-body">
                <div class="col-md-6 pull-left">
                    <h5>未选择</h5>
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
                    <h5>已选择</h5>
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
                    <a style="width: 100px;" class="btn btn-block btn-success">保存</a>
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
