<?php

use yii\helpers\Url;
use app\widgets\JsBlock;

$this->title = '人员列表';

//面包屑
$this->params['breadcrumbs'][] = '人员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="user-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>姓名</th>
                            <th>手机号</th>
                            <th>所在部门</th>
                            <th>所在员工组</th>
                            <th>是否能访问后台</th>
                            <th>创建时间</th>
                            <th>更改时间</th>
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
        $('#user-table').DataTable({
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
                url: "<?=Url::to(['admin/get-users'])?>",
                data:{type:1},
                type: "post",
                dataType : "json"
            },
            'language':{
                "sProcessing": "处理中...",
                "sLengthMenu": "显示 _MENU_ 项结果",
                "sZeroRecords": "没有人员信息",
                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                "sInfoPostFix": "",
                "sSearch": "搜索:",
                "sUrl": "",
                "sEmptyTable": "暂无人员信息",
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
                {data: "phone"},
                {data: "department"},
                {data: "group"},
                {data: "is_admin", render:function (data,type,full) {
                   if (data == 0) {
                       return '否';
                   }

                   return '是';
                }},
                {data: "create_time"},
                {data: "update_time"},
                {
                    render:function (data,type,full) {
                        return '<a href="edit-user?id=' + full['id'] + '">更改信息</a>&nbsp;<a href="distribution-role?id=' + full['id'] + '">分配角色</a>';
                    }
                }
            ]
        })
    </script>
<?php JsBlock::end();?>