var choose = function () {

    var data = [];

    var init = function (obj) {
        $('.work-area').bind('click',workArea);
        $('.station').bind('click',station);

        //初始化数据
    };

    //初始化数据
    var initValue = function(){

    };

    //工区选择
    var workArea = function () {
        var $this = $(this);
        var workArea = $(this).val();
        var obj = {key:workArea};

        if ($this.prop('checked')) {
            var waArr = [];
            $this.parent('.box-body').find('.station').each(function () {
               $(this).prop('checked',true);
                waArr.push($(this).val());
            });
            obj.value = waArr;
            data.push(obj);
        } else {
            $this.parent('.box-body').find('.station').each(function () {
                $(this).prop('checked',false);
            });

            for(var wp in data){
                if(data[wp].key == workArea){
                    data.splice(wp,1);
                }
            }
        }

    };

    //工位选择
    var station = function () {
        var $this = $(this);
        var $workAreaHtml = $this.parents('.box-body').find('.work-area');
        var station = $(this).val();

        var workArea =  $workAreaHtml.val();
        var dataKey = checkDataKey(workArea);

        if ($this.prop('checked')) {

            $workAreaHtml.prop('checked',true);

            if (dataKey === false) {
                var obj = {key:workArea};
                obj.value = [station];
                data.push(obj);
            } else {
                data[dataKey].value.push(station);
            }
        } else {
            if(dataKey === false){
                alert('数据错误,请刷新页面');
            }

            for(var wp in data[dataKey].value){
                if(data[dataKey].value[wp] == station){
                    data[dataKey].value.splice(wp,1);
                }
            }

            //检查产线是否有工位
            if(data[dataKey].value.length == 0){
                data.splice(dataKey,1);
                $workAreaHtml.prop('checked',false);
            }
        }

    };

    //检查是否有此产线
    var checkDataKey = function (id) {
        for(var wp in data){
            if(data[wp].key == id){
                return wp;
            }
        }

        return false;
    };



    return {
        init : function (obj) {
            init(obj);
        }

    }
}();