var show_page = function () {
    $("#warning_logsGrid").yxgrid("reload");
};
$(function () {
    $("#warning_logsGrid").yxgrid({
        model: 'system_warninglogs_warninglogs',
        title: '预警执行记录',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'objId',
            display: '业务ID',
            sortable: true,
            width: 50
        }, {
            name: 'objName',
            display: '业务名称',
            sortable: true,
            width: 180
        }, {
            name: 'executeSql',
            display: '查询脚本',
            sortable: true,
            width: 600
        }, {
            name: 'executeTime',
            display: '执行时间',
            sortable: true,
            width: 130
        }],
        searchitems: [{
            display: "业务名称",
            name: "objNameSearch"
        }]
    });
});