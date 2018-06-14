var choose = function () {

    var type;
    var data = [];
    var url = 'post-type-work-area';
    var jobUrl = 'do-job-station';

    var init = function (obj) {
        //初始化数据
        initValue(obj);

        $('.work-area').bind('click',workArea);
        $('.station').bind('click',station);
        $('#save').bind('click',saveInfo);
        $('#saveJobStation').bind('click',jobStation);
    };

    //初始化数据
    var initValue = function(obj){
        type = obj.type;

        //初始化车间
        for (var wp in obj.workshop) {
            var k = {'workshop':obj.workshop[wp], 'value':[]};
            data.push(k);
        }

        //没做选择
        if (obj.chooseStation.length == 0) {
            return false;
        }

        //初始化选中数据
        for (var wn in obj.chooseStation) {
            for (var mn in obj.chooseStation[parseInt(wn)]) {
                var nObj = {workarea:mn,value:obj.chooseStation[parseInt(wn)][parseInt(mn)]};

                for(var mb in data){
                    if(data[mb]['workshop'] == wn){
                        data[mb].value.push(nObj);
                    }
                }
            }
        }

    };

    //工区选择
    var workArea = function () {
        var $this = $(this);
        var workArea = $(this).val();
        var workshop = $this.parents('.box').attr('data-workshop');
        var obj = {workarea:workArea};

        if ($this.prop('checked')) {
            var waArr = [];
            $this.parent('.box-body').find('.station').each(function () {
               $(this).prop('checked',true);
                waArr.push($(this).val());
            });
            obj.value = waArr;

            for (var mn in data) {
                if (data[mn].workshop == workshop) {
                    data[mn].value.push(obj);
                }
            }

        } else {
            $this.parent('.box-body').find('.station').each(function () {
                $(this).prop('checked',false);
            });

            for(var wp in data){
                if(data[wp].workshop == workshop){
                    for (var mna in data[wp].value){
                        if(data[wp].value[mna].workarea == workArea){
                            data[wp].value.splice(mna,1);
                        }
                    }
                }
            }
        }
        console.log(data);

    };

    //工位选择
    var station = function () {
        var $this = $(this);
        var $workAreaHtml = $this.parents('.box-body').find('.work-area');
        var station = $(this).val();

        var workArea =  $workAreaHtml.val();
        var workshop = $this.parents('.box').attr('data-workshop');

        var workshopKey = checkWorkshopKey(workshop);

        if(workshopKey === false){
            alert('数据错误,请刷新页面');
        }
        var areaKey = checkDataKey(workshopKey,workArea);

        if ($this.prop('checked')) {

            $workAreaHtml.prop('checked',true);

            if (areaKey === false) {
                var obj = {'workarea':workArea};
                obj.value = [station];
                data[workshopKey].value.push(obj);
            } else {
                data[workshopKey]['value'][areaKey].value.push(station);
            }

        } else {
            if (areaKey === false) {
                alert('数据错误,请刷新页面');
            }

            for(var mnb in data[workshopKey]['value'][areaKey].value){
                if(data[workshopKey]['value'][areaKey].value[mnb] == station){
                    data[workshopKey]['value'][areaKey].value.splice(mnb,1);
                }
            }

            //检查产线是否有工位
            if(data[workshopKey]['value'][areaKey].value.length == 0){
                data[workshopKey].value.splice(areaKey,1);
                $workAreaHtml.prop('checked',false);
            }
        }

        console.log(data);

    };

    //检查是否有此产线
    var checkDataKey = function (workshopKey, id) {
        if (data[workshopKey].value .length>0) {
            for (var mn in data[workshopKey].value) {
                if(data[workshopKey].value[mn].workarea == id){
                    return mn;
                }
            }
        }

        return false;
    };

    var checkWorkshopKey = function (workshopId) {
        for (var wp in data) {
            if (data[wp].workshop == workshopId){
                return wp;
            }
        }

        return false;
    };

    //提交数据
    var postData = function () {
        $.ajax({
            'url' : url,
            'data' : {'data':data, 'type':type},
            'type' : 'post',
            'dataType' : 'json',
            'success' : function (data) {
                if(data.code == 0){
                    alert(data.message);
                }
            }
        });
    };

    var jobStation = function () {
        $.ajax({
            'url' : jobUrl,
            'data' : {'type':type},
            'type' : 'post',
            'dataType' : 'json',
            'success' : function (data) {
                if(data.code == 0){
                    alert('生成职能工位成功');
                } else {
                    alert(data.message);
                }
            }
        });
    };

    //检查信息是否正确
    var checkDataInfo = function () {
        var $have = false;
        var $data = [];
        var $workshopNu = [];

        for (var wp in data) {
            var workshopId = data[wp].workshop;

            if (data[wp].value.length > 0) {
                $workshopNu.push(workshopId);
                var $num = 0;
                //工区
                for(var mn in data[wp].value){
                    if (mn == 0) {
                        $num = data[wp]['value'][mn].value.length;
                    } else {
                        if($num != data[wp]['value'][mn].value.length){
                            $data.push(workshopId);
                        }
                    }
                }

                $have = true;
            }
        }

        if ($have === false) {
            alert('请先选择工区和工位');
            return false;
        }

        if($workshopNu.length != data.length){
            alert('每个车间都需要选择一个工区');
            return false;
        }

        if($data.length > 0) {
            alert('所选工区的工位数量要一致');
            return false;
        }

        return true;
    };

    var saveInfo = function () {
        var $status = checkDataInfo();

        if($status){
            postData();
        }
    };

    return {
        init : function (obj) {
            init($.parseJSON(obj));
        }

    }
}();