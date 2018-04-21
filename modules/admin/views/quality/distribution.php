<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/22
 * Time: 18:18
 */
use app\widgets\JsBlock;

$this->title = '选择可做此质检类型的工区';

//面包屑
$this->params['breadcrumbs'][] = '质检管理';
$this->params['breadcrumbs'][] = $this->title;

$obj = ['workshop' => $workshopJs, 'type' => Yii::$app->request->get('id'), 'chooseStation' => $chooseStation];
?>
<style>
    .color-palette {
        height: 105px;
        line-height: 35px;
        text-align: center;
    }

    .color-palette-set {
        margin-bottom: 15px;
    }

    .color-palette span {
        display: block;
        font-size: 12px;
    }

    .color-palette-box h4 {
        position: absolute;
        top: 100%;
        left: 25px;
        margin-top: -40px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 12px;
        display: block;
        z-index: 7;
    }
</style>
<!-- Main content -->
<section class="content">
    <div><span><h4><?=$type->name?></h4></span></div><div><button class="btn btn-info" id="saveJobStation" type="button">生成职能工位</button>
    </div>

    <?php foreach ($data as $k => $workshop):?>
    <!-- COLOR PALETTE -->
    <div class="box box-default color-palette-box" data-workshop="<?=$workshop['id']?>">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-home"></i><?=$workshop['name']?></h3>
        </div>

        <?php if(!empty($workshop['workArea'])):?>
            <?php foreach ($workshop['workArea'] as $workArea):?>
                <div class="box-body">
                    <input type="checkbox" <?php if($workArea['status']):?>checked="checked"<?php endif;?> class="work-area" value="<?=$workArea['id']?>"/>&nbsp;&nbsp;<label><?=$workArea['name']?></label>

                    <?php if(!empty($workArea['station'])):?>
                        <div class="row">
                            <?php foreach ($workArea['station'] as $station):?>
                                <div class="col-sm-4 col-md-2">
                                    <h4 class="text-center"><?=$station['code']?></h4>

                                    <div class="color-palette-set">
                                        <div class="bg-teal color-palette" title="选择工位"><span> <input class="station" type="checkbox" <?php if($station['status']):?>checked="checked"<?php endif;?>  value="<?=$station['id']?>"/>&nbsp;&nbsp;<?=$station['name']?></span></div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <hr>
                    <?php endif;?>

                </div>
            <?php endforeach;?>
        <!-- /.box-body -->
        <?php endif;?>
    </div>
    <?php endforeach;?>

    <div class="text-center"><button class="btn btn-info" id="save" type="button">保存</button></div>
</section>
<?php JsBlock::begin()?>
<script>
    choose.init('<?=json_encode($obj)?>');
</script>
<?php JsBlock::end();?>
