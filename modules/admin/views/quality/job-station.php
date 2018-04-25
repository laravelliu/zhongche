<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/28
 * Time: 16:30
 */

use yii\helpers\Url;
use app\widgets\JsBlock;
$this->title = '职能工位列表';

//面包屑
$this->params['breadcrumbs'][] = '质检管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <!--<a href="<?/*=Url::to(['quality/add-quality-type'])*/?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>添加质检类型
                        </a>-->

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="job-station-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>职能工位名称</th>
                            <th>质检类型</th>
                            <th>所属车间</th>
                            <th>上一级职能工位</th>
                            <th>下一级职能工位</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<?php JsBlock::begin()?>
<script>
    $('#job-station-table').DataTable({
        'paging'      : true,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        "scrollX"     : false,
        "aaSorting"   : [[ 0, "asc" ]],
        'autoWidth'   : false,
        "bLengthChange": true,
        ajax: {
            url: "<?=Url::to(['quality/get-job-station'])?>",
            type: "post",
            dataType : "json"
        },
        'language':{
            "sProcessing": "处理中...",
            "sLengthMenu": "显示 _MENU_ 项结果",
            "sZeroRecords": "没有生成职能工位信息",
            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "暂无生成职能工位信息",
            "sLoadingRecords": "载入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "上页",
                "sNext": "下页",
                "sLast": "末页"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        },
        "rowId": 'id',
        'columns': [
            {data: "id"},
            {data: "name"},
            {data: "type"},
            {data: "workshop"},
            {data: "pName"},
            {data: "sName"},
            {data: "create_time"},
            {data: "update_time"},
            {
                render:function (data,type,full) {
                    if (full['name'] == '无' && full['pName'] == '无' && full['sName'] == '无') {
                        return '<a href="edit-job-station?id=' + full['id'] + '">初始职能工位</a>'
                    } else {
                        return '<a href="edit-job-station?id=' + full['id'] + '">编辑职能工位</a>&nbsp;&nbsp;<a href="distribution-process?id=' + full['id'] + '">分配质检流程</a>&nbsp;&nbsp;<br><a href="distribution-item?id=' + full['id'] + '">分配质检项</a>&nbsp;&nbsp;<a href="relate-station?id=' + full['id'] + '">关联物理工位</a>';
                    }
                }
            }
        ]
    })
</script>
<?php JsBlock::end();?>
