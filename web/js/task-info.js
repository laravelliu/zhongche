var task = function () {
    var init = function (obj) {
        
    };

    var getTaskInfo = function (taskId) {
        $.ajax({
            'url' : 'admin/quality/task-info-detail',
            'data' : {'taskId':taskId},
            'type' : 'post',
            'dataType' : 'json',
            'success' : function (data) {
                if(data.code == 0){
                    alert(data.message);
                }
            }
        });
    };

    return {
        init : function () {
            alert('123');
            //init(obj);
        }
    }
}();