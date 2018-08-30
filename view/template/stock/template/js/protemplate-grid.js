var show_page = function(page) {
    $("#protemplateGrid").yxgrid("reload");
};
$(function() {
    $("#protemplateGrid").yxgrid({
        model: 'stock_template_protemplate',
        title: '����ģ�����ñ�',
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        },
        {
            name: 'templateName',
            display: 'ģ������',
            width: 200,
            sortable: true
        },
        {
            name: 'remark',
            display: '��ע',
            width: 200,
            sortable: true
        },
        {
            name: 'createName',
            display: '������',
            sortable: true,
            hide: true
        },
        {
            name: 'createId',
            display: '������id',
            sortable: true,
            hide: true
        },
        {
            name: 'createTime',
            display: '��������',
            sortable: true,
            hide: true
        },
        {
            name: 'updateName',
            display: '�޸���',
            sortable: true,
            hide: true
        },
        {
            name: 'updateId',
            display: '�޸���id',
            sortable: true,
            hide: true
        },
        {
            name: 'updateTime',
            display: '�޸�����',
            sortable: true,
            hide: true
        }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=stock_template_protrmplateitem&action=pageItemJson',
            param: [{
                paramId: 'mainId',
                colId: 'id'
            }],
            colModel: [{
                name: 'templateName',
                display: '��������'
            }]
        },

        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [{
            display: "ģ������",
            name: 'templateName'
        }]
    });
});