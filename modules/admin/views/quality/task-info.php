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

?>

<section class="content">
    <?php for($i=0;$i<=$row; $i++):?>
    <div class="row">
        <div class="col-md-6 item-group" data-group="<?=$group[$i*2]['id']?>">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$group[$i*2]['name']?></h3>
                </div>
                <div class="box-body">

                </div>
            </div>
        </div>

        <?php if(isset($group[$i*2 + 1])):?>
        <div class="col-md-6 item-group" data-group="<?=$group[$i*2 +1]['id']?>">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$group[$i*2 + 1]['name']?></h3>
                </div>
                <div class="box-body">

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
        task.init();
    });

</script>
<?php JsBlock::end()?>
