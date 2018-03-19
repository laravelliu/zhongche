var selectInfo = function () {
    var id = '';
    var url = '';
    var all = [];
    var select = [];
    var un_select = [];

    var init = function (obj) {

        if(obj.all == 'undefined'){
            alert('数据出错');
            return false;
        }

        if(obj.id == 'undefined' || obj.id == null){
            alert('缺少id');
            return false;
        }

        if(obj.url == 'undefined' || obj.url == null){
            alert('缺少url');
            return false;
        }

        id = obj.id;
        url = obj.url;

        //初始化数据
        for(var mp in obj.all){
            all.push([mp,obj.all[mp]]);
        }

        for(var mn in obj.selected){
            select.push([mn,obj.selected[mn]]);
        }

        for(var mv in obj.unSelect){
            un_select.push([mv,obj.unSelect[mv]]);
        }

        //绑定选中
        $('#add').click(function () {
            selectOne();
        });

        //绑定选中所有
        $('#add_all').click(function () {
            selectAll();
        });

        //删除选中
        $('#remove').click(function () {
            deleteOne();
        });

        //删除选中所有
        $('#remove_all').click(function () {
            deleteAll();
        });

        //保存数据
        $('#save_info').click(function () {
           saveInfo();
        });
    };

    var selectOne = function () {

        var $seVal = [];

        $('#unSelect option:selected').each(function () {
            $seVal.push($(this).val());
        });

        if($seVal.length == 0){
            alert('请在未选择中挑选');
            return false;
        }

        var un_select_d = [];

        //删除未选择
        un_select.forEach(function (value,key,map) {
            if($.inArray(value[0], $seVal) > -1){
                un_select_d.push(value);
            }
        });
        $('#unSelect option:selected').remove();

        if(un_select_d != null){
            un_select_d.forEach(function (value,key,map) {
                un_select.forEach(function (val,ke,ma) {
                    if(val[0] == value[0]){
                        un_select.splice(ke,1);
                    }
                });
            })
        }

        //添加选择
        un_select_d.forEach(function (value,key,map) {
            select.push(value);
            $('#selected').append("<option value='"+value[0]+"'>"+value[1]+"</option>");
        });

    };

    var selectAll = function () {
        //赋值
        select = all;
        un_select = [];

        $('#unSelect').empty();
        $('#selected').empty();

        select.forEach(function (value,key,map) {
            $('#selected').append("<option value='"+value[0]+"'>"+value[1]+"</option>");
        })

    };

    var deleteOne = function () {

        var $seVal = [];

        $('#selected option:selected').each(function () {
            $seVal.push($(this).val());
        });

        if($seVal.length == 0){
            alert('请在已选择中挑选');
            return false;
        }

        var select_d = [];

        //删除已选择
        select.forEach(function (value,key,map) {
            if($.inArray(value[0], $seVal) > -1){
                select_d.push(value);
            }
        });

        $('#selected option:selected').remove();

        if(select_d != null){
            select_d.forEach(function (value,key,map) {
                select.forEach(function (val,ke,ma) {
                    if(val[0] == value[0]){
                        select.splice(ke,1);
                    }
                });
            })
        }

        //添加选择
        select_d.forEach(function (value,key,map) {
            un_select.push(value);
            $('#unSelect').append("<option value='"+value[0]+"'>"+value[1]+"</option>");
        });

    };

    var deleteAll = function () {
        //赋值
        select = [];
        un_select = all;

        $('#unSelect').empty();
        $('#selected').empty();

        un_select.forEach(function (value,key,map) {
            $('#unSelect').append("<option value='"+value[0]+"'>"+value[1]+"</option>");
        })

    };

    var saveInfo = function () {
        $.ajax({
            url : url,
            data : {id:id,selected:select,'unSelect':un_select},
            type:'post',
            dataType:'json',
            success:function (data) {
                if(data.code == 0){
                    alert('保存成功');
                }else{
                    alert(data.message);
                }
            }
        });
    };

    return {
        init:function (obj) {
            init(JSON.parse(obj));
        }
    }
}();
