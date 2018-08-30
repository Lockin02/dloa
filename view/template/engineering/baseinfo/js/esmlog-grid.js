$(function () {
    $("#grid").yxgrid({
        model : 'engineering_baseinfo_esmlog',
        title: '数据更新记录',
        param: {
            projectId: $("#projectId").val()
        },
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        colModel: [{
            display: 'id',
            name: 'id',
            width: 80,
            hide : true
        }, {
            display: '更新项',
            name: 'operationType',
            width: 120
        }, {
            display: '更新记录数',
            name: 'description',
            align: 'center',
            process: function (v) {
                var detail = v.split('|');
                return detail[0];
            }
        }, {
            display: '更新时间',
            name: 'operationTime',
            align: 'left',
            width: 130
        }, {
            display: '更新范围',
            name: 'description',
            align: 'center',
            process: function (v) {
                var detail = v.split('|');
                return detail[1] + '月';
            }
        },{
            display: '操作人',
            name: 'userName',
            align: 'left',
            width: 100
        }],
        searchitems : [{
            display : "更新项",
            name : 'operationTypeSearch'
        }, {
            display : "更新时间",
            name : 'operationTimeSearch'
        }]
    });
});