<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/5/24
 * Time: 18:48
 */

use app\widgets\JsBlock;

$this->title = '质检任务详情';

//面包屑
$this->params['breadcrumbs'][] = '质检任务管理';
$this->params['breadcrumbs'][] = $this->title;

$count = count($group);
$row = floor($count/2);

$arr = json_encode(['taskId' => $task->id]);

?>

<section class="content">
    <?php for($i=0; $i<$row; $i++):?>
    <div class="row">
        <div class="col-md-12 item-group" data-group="<?=$group[$i*2]['id']?>" data-split="<?=$group[$i*2]['is_split']?>" data-type="<?=$group[$i*2]['item_type']?>">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$group[$i*2]['name']?></h3>
                </div>
                <div class="box-body">
                    <h5>获取数据</h5>
                </div>
            </div>
        </div>

        <?php if(isset($group[$i*2 + 1])):?>
        <div class="col-md-12 item-group" data-group="<?=$group[$i*2 +1]['id']?>" data-split="<?=$group[$i*2 +1]['is_split']?>" data-type="<?=$group[$i*2 +1]['item_type']?>">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$group[$i*2 + 1]['name']?></h3>
                </div>
                <div class="box-body">
                    <h5>获取数据</h5>
                </div>
            </div>
        </div>
        <?php endif;?>
    </div>
    <?php endfor;?>
</section>
<?php JsBlock::begin();?>
<script>
    $(document).ready(function () {
        task.init('<?=$arr?>');
    });

</script>
<?php JsBlock::end()?>
