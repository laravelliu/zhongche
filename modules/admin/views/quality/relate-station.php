<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/21
 * Time: 14:35
 */

use yii\helpers\Url;
use app\widgets\JsBlock;

$this->title = '关联工位';

//面包屑
$this->params['breadcrumbs'][] = '质检管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">职能工位：<span><?=$station->name?></span></h3>
                    <div class="box-tools">
                        <a href="<?=Url::to(['quality/job-station'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>职能工位列表
                        </a>
                    </div>

                </div>
                <form class="form-horizontal" action="<?=Url::to(['quality/edit-relate-station','id' => $id])?>" method="post">
                    <div class="box-body">

                        <?php foreach ($data as $k => $v):?>

                        <div class="form-group  required">
                            <div class="col-xs-3 col-sm-2 text-right">
                                <label class="control-label"><?=$workArea[$k]?>：</label>
                            </div>
                            <div class="col-xs-9 col-sm-7">
                                <select  class="form-control" name="choose[]" aria-required="true">
                                    <option value="">请选择关联工位</option>
                                    <?php foreach ($v as $m => $n):?>
                                        <?php if (in_array($n['id'],$chooseStations)):?>
                                            <option selected="selected" value="<?=$n['id']?>"><?=$n['name']?></option>
                                        <?php else:?>
                                            <option value="<?=$n['id']?>"><?=$n['name']?></option>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-xs-offset-3 col-sm-3 col-sm-offset-0">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <?php endforeach;?>
                    </div>
                    <div class="box-footer">
                        <div class="col-xs-3 col-sm-2 text-right"></div>
                        <div class="col-xs-9 col-sm-7">
                            <button type="submit" class="btn btn-info">编辑</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>

