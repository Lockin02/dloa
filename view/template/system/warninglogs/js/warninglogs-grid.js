var show_page = function () {
    $("#warning_logsGrid").yxgrid("reload");
};
$(function () {
    $("#warning_logsGrid").yxgrid({
        model: 'system_warninglogs_warninglogs',
        title: 'Ԥ��ִ�м�¼',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'objId',
            display: 'ҵ��ID',
            sortable: true,
            width: 50
        }, {
            name: 'objName',
            display: 'ҵ������',
            sortable: true,
            width: 180
        }, {
            name: 'executeSql',
            display: '��ѯ�ű�',
            sortable: true,
            width: 600
        }, {
            name: 'executeTime',
            display: 'ִ��ʱ��',
            sortable: true,
            width: 130
        }],
        searchitems: [{
            display: "ҵ������",
            name: "objNameSearch"
        }]
    });
});