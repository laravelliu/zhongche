<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/6/13
 * Time: 17:17
 */

$first = current($list['answer']);

$zj = $jz = 0;

$count=7;

if (isset($first['answer_computer'])) {
    $zj = 1;
    $count++;
}

if (isset($first['answer_computer_re'])) {
    $jz = 1;
    $count++;
}

if ($jz && $zj) {
    $zjCount = ceil($count/2);
    $jzCount = $count-$zjCount;
}elseif ($jz){
    $jzCount = $count;
}elseif ($zj){
    $zjCount = $count;
}

?>

<table class="table table-bordered" width="100%" border="1" cellpadding="0" cellspacing="0" style="table-layout: fixed;">
    <thead>
    <tr>
        <th>#</th>
        <th>质检项点</th>
        <th>质检标准</th>
        <th>自检结果</th>
        <th>自检员工</th>
        <th>互检结果</th>
        <th>互检员工</th>

        <?php if($zj):?>
        <th>专检结果</th>
        <?php endif;?>

        <?php if($jz):?>
        <th>监造结果</th>
        <?php endif;?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list['answer'] as $k => $v):?>
        <tr>
            <td><?=$k?></td>
            <td><?=$v['name']?></td>
            <td><?= empty($v['standard'])?$v['standard']:implode("、",$v['standard'])?></td>
            <td><?=$v['answer']?></td>
            <td><?=$v['answer_name']?></td>
            <td><?=isset($v['answer_each'])?$v['answer_each']:'无互检'?></td>
            <td><?=isset($v['answer_each_name'])?$v['answer_each_name']:'无互检人'?></td>

            <?php if(isset($v['answer_computer'])):?>
            <td>

                <?php
                foreach ($v['answer_computer'] as $m => $n){
                    echo ($m+1).'.'.$n.'<br>';
                }
                ?>

            </td>
            <?php endif;?>

            <?php if(isset($v['answer_computer_re'])):?>

            <td><?php
                foreach ($v['answer_computer_re'] as $ma => $na){
                    echo ($ma+1).'.'.$na.'<br>';
                }
                ?></td>
            <?php endif;?>
        </tr>
    <?php endforeach;?>
    <tr>
        <?php if($zj):?>
        <td colspan="<?=$zjCount?>"><h5>专检员：<span style="color: red"><?=isset($list['user']['do_computer']) ? $list['user']['do_computer'] : ''?></span></h5></td>
        <?php endif;?>

        <?php if($jz):?>
        <td colspan="<?=$jzCount?>"><h5>监造人员：<span style="color: red"><?=isset($list['user']['do_computer_re']) ? $list['user']['do_computer_re'] : ''?></span></h5></td>
        <?php endif;?>

    </tr>
    </tbody>
</table>

