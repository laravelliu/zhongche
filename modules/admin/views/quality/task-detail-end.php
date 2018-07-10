<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/6/13
 * Time: 17:17
 */
?>

<table class="table table-bordered" width="100%" border="1" cellpadding="0" cellspacing="0" style="table-layout: fixed;">
    <thead>
    <tr>
        <th>#</th>
        <th>质检项点</th>
        <th>质检标准</th>
        <th>检查结果</th>
        <th>专检员检查结果</th>
        <th>监造员检查结果</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list['answer'] as $k => $v):?>
        <tr>
            <td><?=$k?></td>
            <td><?=$v['name']?></td>
            <td><?= empty($v['standard']) ? '': implode("、",$v['standard'])?></td>
            <td><?=$v['answer']?></td>
            <td><?=$v['answer_computer'][0]?></td>
            <td><?=$v['answer_computer_re'][0]?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="2"><h5>整车质检员：<span style="color: red"><?=isset($list['user']['do_name']) ? $list['user']['do_name'] : ''?></span></h5></td>
        <td colspan="2"><h5>整车质检检查员：<span style="color: red"><?=isset($list['user']['do_computer']) ? $list['user']['do_computer'] : ''?></span></h5></td>
        <td colspan="2"><h5>整车质检监造人员：<span style="color: red"><?=isset($list['user']['do_computer_re']) ? $list['user']['do_computer_re'] : ''?></span></h5></td>
    </tr>
    </tbody>
</table>
