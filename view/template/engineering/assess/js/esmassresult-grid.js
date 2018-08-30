var show_page = function () {
    $("#esmassresultGrid").yxgrid("reload");
};
$(function () {
    // 是否允许修改
    var isEditAction = false;
    $.ajax({
        type: 'POST',
        url: '?model=engineering_project_esmproject&action=getLimits',
        data: {
            limitName: '日志考核配置'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                isEditAction = true;
            }
        }
    });

    $("#esmassresultGrid").yxgrid({
        model: 'engineering_assess_esmassresult',
        title: '日志考核结果设置',
        sortorder: 'ASC',
        isAddAction: false,
        isEditAction: isEditAction,
        isDelAction: false,
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'resultName',
                display: '日志/周报',
                sortable: true,
                width: 60
            },
//            {
//                name: 'resultVal',
//                display: '对应值',
//                sortable: true,
//                width: 60
//            },
//            {
//                name: 'resultScore',
//                display: '考核分数',
//                sortable: true,
//                width: 60
//            },
            {
                name: 'score_10',
                display: '10',
                sortable: true,
                width: 60
            },
            {
                name: 'score_9',
                display: '9',
                sortable: true,
                width: 60
            },
            {
                name: 'score_8',
                display: '8',
                sortable: true,
                width: 60
            },
            {
                name: 'score_7',
                display: '7',
                sortable: true,
                width: 60
            },
            {
                name: 'score_6',
                display: '6',
                sortable: true,
                width: 60
            },
            {
                name: 'score_5',
                display: '5',
                sortable: true,
                width: 60
            },
            {
                name: 'score_4',
                display: '4',
                sortable: true,
                width: 60
            },
            {
                name: 'score_3',
                display: '3',
                sortable: true,
                width: 60
            },
            {
                name: 'score_2',
                display: '2',
                sortable: true,
                width: 60
            },
            {
                name: 'score_1',
                display: '1',
                sortable: true,
                width: 60
            },
            {
                name: 'score_0',
                display: '0',
                sortable: true,
                width: 60
            }
        ],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        }
    });
});