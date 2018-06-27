<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/6/13
 * Time: 17:17
 */

$first = current($list['answer']);

$count=9;

$zj = $jz = 0;

if (isset($first['answer_computer'])) {
    $zj = 1;
    $count++;
}

if (isset($first['answer_computer_re'])) {
    $jz = 1;
    $count++;
}

if ($jz && $zj) {
    $fjCoount = ceil($count/3);
    $zjCount = ceil(($count-$fjCoount)/2);
    $jzCount = $count-$zjCount-$fjCoount;
}elseif ($jz){
    $fjCoount = ceil($count/2);
    $jzCount = $count-$fjCoount;
}elseif ($zj){
    $fjCoount = ceil($count/2);
    $zjCount = $count-$fjCoount;
}else{
    $fjCoount = $count;
}


?>

<table class="table table-bordered" width="100%" border="1" cellpadding="0" cellspacing="0" style="table-layout: fixed;">
    <thead>
    <tr>
        <th>#</th>
        <th>质检项点</th>
        <th>质检标准</th>
        <th>分解检查结果</th>
        <th>分解处理方法</th>
        <th>自检结果</th>
        <th>自检员工</th>
        <th>互检结果</th>
        <th>互检员工</th>

        <?php if($zj):?>
            <th>专检员检查结果</th>
        <?php endif;?>

        <?php if($jz):?>
            <th>监造员检查结果</th>
        <?php endif;?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list['answer'] as $k => $v):?>
        <tr>
            <td><?=$k?></td>
            <td><?=$v['name']?></td>
            <td><?= implode("、",$v['standard'])?></td>
            <td><?=$v['answer_fj']?></td>
            <td><?=$v['answer_fj_do']?></td>
            <td><?=$v['answer']?></td>
            <td><?=$v['answer_name']?></td>
            <td><?=$v['answer_each']?></td>
            <td><?=$v['answer_each_name']?></td>
            <td><?=$v['answer_computer'][0]?></td>
            <td><?=$v['answer_computer_re'][0]?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="<?=$fjCoount?>"><h5>分解员：<span style="color: red"><?=isset($list['user']['do_name']) ? $list['user']['do_name'] : ''?></span></h5></td>

        <?php if($zj):?>
        <td colspan="<?=$zjCount?>"><h5>专检员：<span style="color: red"><?=isset($list['user']['do_computer']) ? $list['user']['do_computer'] : ''?></span></h5></td>
        <?php endif;?>

        <?php if($jz):?>
        <td colspan="<?=$jzCount?>"><h5>监造人员：<span style="color: red"><?=isset($list['user']['do_computer_re']) ? $list['user']['do_computer_re'] : ''?></span></h5></td>
        <?php endif;?>

    </tr>
    </tbody>
</table>