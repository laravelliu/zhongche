<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/3/4
 * Time: 11:35
 */

use yii\helpers\Url;
use app\widgets\JsBlock;

$this->title = '车辆型号参数列表';

//面包屑
$this->params['breadcrumbs'][] = '参数管理';
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
                        <a href="<?=Url::to(['param/add-vehicle-type'])?>" class="btn btn-default btn-sm">
                            <i class="fa fa-play"></i>添加车辆类别
                        </a>

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="car-type-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>车辆类别</th>
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
        $('#car-type-table').DataTable({
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
                url: "<?=Url::to(['quality/get-quality-item'])?>",
                type: "post",
                dataType : "json"
            },
            'language':{
                "sProcessing": "处理中...",
                "sLengthMenu": "显示 _MENU_ 项结果",
                "sZeroRecords": "没有匹配车辆类别信息",
                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                "sInfoPostFix": "",
                "sSearch": "搜索:",
                "sUrl": "",
                "sEmptyTable": "暂无车辆类别信息",
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
                {data: "json"},
                {data: "create_time"},
                {data: "update_time"},
                {
                    render:function (data,type,full) {
                        return '<a href="edit-car-type?id=' + full['id'] + '">编辑</a>';
                    }
                }
            ]
        })
    </script>
<?php JsBlock::end();?>