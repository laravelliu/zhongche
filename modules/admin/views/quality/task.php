<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/18
 * Time: 13:29
 */

use yii\helpers\Url;
use app\widgets\JsBlock;

$this->title = '质检任务列表';

//面包屑
$this->params['breadcrumbs'][] = '质检任务管理';
$this->params['breadcrumbs'][] = $this->title;

$user = $this->params['userInfo'];

?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="type-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>任务id</th>
                            <th>质检类型</th>
                            <th>车辆信息</th>
                            <th>所在状态</th>
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
    $('#type-table').DataTable({
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
            url: "<?=Url::to(['quality/get-task-list'])?>",
            type: "post",
            dataType : "json"
        },
        'language':{
            "sProcessing": "处理中...",
            "sLengthMenu": "显示 _MENU_ 项结果",
            "sZeroRecords": "没有匹配质检类型信息",
            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "暂无质检类型信息",
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
            {data: "type"},
            {data: "vehicle_info"},
            {data: "finish",render:function (data,type,full) {
                switch (data){
                    case '0':
                        return '正在进行';
                        break;
                    case '1':
                        return '已结束';
                        break;
                    case '2':
                        return '任务终止';
                        break;
                    default:
                        return '数据异常';
                        break;
                }

            }},
            {data: "create_time"},
            {data: "update_time"},
            {
                render:function (data,type,full) {
                    if (full['finish'] == 1) {
                        return '<a href="task-info?id=' + full['id'] + '">查看信息</a>';
                    }else{
                        if(full['is_admin'] && full['finish'] == 0){
                            return '<a href="javascript:void(0);" onclick="delTask('+full['id']+')">终止任务</a>';
                        } else {
                            return null;
                        }
                    }
                }
            }
        ]
    });

    <?php if($user->isSuperAdmin()):?>
    function delTask($id) {
        console.log($id);
        $.ajax({
            url:'/admin/quality/del-task',
            data:{id:$id},
            dataType:'json',
            type:'POST',
            success:function (data) {
               if (0 == data.code){
                   alert(data.message);
                   $('#'+$id+' td').eq(3).text('任务终止');
                   $('#'+$id+' td:last').text('');
               }else{
                   alert(data.message);
               }

            }
        });
    }
    <?php endif;?>


</script>
<?php JsBlock::end();?>
