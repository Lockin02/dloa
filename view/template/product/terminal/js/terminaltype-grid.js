var show_page = function(page) {
    $("#productTree").yxtree("reload");
    $("#terminaltypeGrid").yxgrid("reload");
};
$(function() {
    $("#productTree").yxtree({
        url: '?model=product_terminal_product&action=getProduct',
        event: {
            "node_click": function(event, treeId, treeNode) {
                var terminaltypeGrid = $("#terminaltypeGrid").data('yxgrid');
                terminaltypeGrid.options.param['productId'] = treeNode.id;
                $("#productName").val(treeNode.name);
                $("#productId").val(treeNode.id);
                terminaltypeGrid.reload();
            }
        }
    });
    $("#terminaltypeGrid").yxgrid({
        model: 'product_terminal_terminaltype',
        title: '�ն˷������',
        //����Ϣ
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
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
                name: 'orderIndex',
                display: '˳��',
                sortable: true,
                width: 130
            }, {
                name: 'remark',
                display: '��ע',
                sortable: true,
                width: 250
            }],
        toAddConfig: {
            toAddFn: function(p) {
                showThickboxWin("?model=product_terminal_terminaltype&action=toAdd&productName="
                        + $("#productName").val()
                        + "&productId="
                        + $("#productId").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
            }
        },
        searchitems: [{
                display: '��Ʒ����',
                name: 'productName'
            }, {
                display: '�ն˷�������',
                name: 'typeName'
            }]
    });
});