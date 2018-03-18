var selectInfo = function () {
    var all = [];
    var select = [];
    var un_select = [];

    var init = function (obj) {

       /* var m = new Map([[1, 'x'], [2, 'y'], [3, 'z']]);

        m.forEach(function (value, key, map) {
            alert(value);
        });*/

        if(obj.all == 'undefined'){
            alert('数据出错');
            return false;
        }

        for(var mp in obj.all){
            all.push([mp,obj.all[mp]]);
        }

        for(var mn in obj.selected){
            select.push([mn,obj.selected[mn]]);
        }

        for(var mv in obj.unSelect){
            un_select.push([mv,obj.unSelect[mv]]);
        }

        console.log(all);
        console.log(select);
        console.log(un_select);

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
    };

    var selectOne = function () {
        var st = $('#unSelect option:selected').val();

        //删除为选择
    };

    var selectAll = function () {

    };

    var deleteOne = function () {
        var st = $('#selected option:selected').val();
        console.log(st);

    };

    var deleteAll = function () {

    };

    return {
        init:function (obj) {
            init(JSON.parse(obj));
        }
    }
}();
