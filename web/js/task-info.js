var task = function () {

    var taskId = null;

    var init = function (obj) {
        taskId = obj.taskId;

        $('.item-group').each(function () {
            var groupId = $(this).attr('data-group');
            var isFJ = $(this).attr('data-split');
            var itemType = $(this).attr('data-type');

            getTaskInfo(groupId,isFJ,itemType);
        });
    };


    var getTaskInfo = function (groupId,isFJ,itemType) {
        var $html = $(".item-group[data-group="+groupId+"]").find('.box-body');

        $.ajax({
            url : 'task-info-detail',
            data : {'taskId':taskId,'groupId':groupId,'isSplit':isFJ,'type':itemType},
            type : 'post',
            dataType : 'html',
            success : function (data) {
                $html.html(data);
            }
        });
    };

    return {
        init : function (obj) {

            init(JSON.parse(obj));
        }
    }
}();