<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use app\widgets\JsBlock;

$this->title = '角色管理';

//面包屑
$this->params['breadcrumbs'][] = '人员管理';
$this->params['breadcrumbs'][] = '其他管理';
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
                        <a href="<?=Url::to(['role/add-role'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>添加角色
                        </a>

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="station-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>角色名称</th>
                            <th>用户个数</th>
                            <th>拥有权限数</th>
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
    $('#station-table').DataTable({
        'paging'      : true,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        "scrollX"     : false,
        "aaSorting"   : [[ 0, "asc" ], [6, "desc"]],
        'autoWidth'   : false,
        "bLengthChange": true,
        ajax: {
            url: "<?=Url::to(['role/get-role'])?>",
            type: "post",
            dataType : "json"
        },
        'language':{
            "sProcessing": "处理中...",
            "sLengthMenu": "显示 _MENU_ 项结果",
            "sZeroRecords": "没有匹配角色信息",
            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "暂无角色信息",
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
            {data: "permission_num"},
            {data: "user_num"},
            {data: "create_time"},
            {data: "update_time"},
            {
                render:function (data,type,full) {
                    return '<a href="edit-role?id=' + full['id'] + '">编辑</a>&nbsp<a href="<?=Url::to(['role/distribution'])?>?id=' + full['id'] + '">分配权限</a>';
                }
            }
        ]
    })
</script>
<?php JsBlock::end();?>
