var show_page = function(page) {
    $("#productTree").yxtree("reload");
    $("#terminalinfoGrid").yxgrid("reload");
};
$(function() {
    $("#productTree").yxtree({
        url: '?model=product_terminal_product&action=getProduct',
        event: {
            "node_click": function(event, treeId, treeNode) {
                var terminalinfoGrid = $("#terminalinfoGrid").data('yxgrid');
                terminalinfoGrid.options.param['productId'] = treeNode.id;
                $("#productName").val(treeNode.name);
                $("#productId").val(treeNode.id);
                terminalinfoGrid.reload();
            }
        }
    });
    $("#terminalinfoGrid").yxgrid({
        model: 'product_terminal_terminalinfo',
        title: '�ն˷������',
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'terminalName',
                display: '�ն�����',
                sortable: true,
                width: 130
            },{
                name: 'productName',
                display: '������Ʒ����',
                sortable: true,
                width: 130
            }, {
                name: 'productId',
                display: '������ƷId',
                sortable: true,
                hide: true
            }, {
                name: 'typeName',
                display: '�ն˷�������',
                sortable: true
            }, {
                name: 'typeId',
                display: '�ն˷���Id',
                sortable: true,
                hide: true
            },  {
                name: 'deviceType',
                display: '�豸����',
                sortable: true,
            }/*,  {
                name: 'os',
                display: '����ϵͳ',
                sortable: true,
            }*/,  {
                name: 'supportNetwork',
                display: '֧������',
                sortable: true
            }, {
                name: 'versionStatus',
                display: '�汾״̬',
                datacode:"BBZT",
                sortable: true
            }, {
                name: 'formalVersion',
                display: '��ʽ�����汾��',
                sortable: true
            }, {
                name: 'newVersion',
                display: '������ʽ�̼��汾��',
                sortable: true,
                width: 140
            }, {
                name: 'softFunction',
                display: '�������',
                sortable: true
            }, {
                name: 'materialsName',
                display: '������������',
                sortable: true
            }, {
                name: 'materialsId',
                display: '��������id',
                hide: true
            }, {
                name: 'orderIndex',
                display: '˳��',
                sortable: true
            }, {
                name: 'remark',
                display: '��ע',
                sortable: true,
                width: 150
            }],
        toAddConfig: {
            toAddFn: function(p) {
                showThickboxWin("?model=product_terminal_terminalinfo&action=toAdd&productName="
                        + $("#productName").val()
                        + "&productId="
                        + $("#productId").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=850");
            }
        },
        searchitems: [{
                display: '�ն�����',
                name: 'terminalName'
            }, {
                display: '��Ʒ����',
                name: 'productName'
            },{
                display: '�ն˷�������',
                name: 'typeName'
            }]
    });
});