var show_page = function(page) {
    $("#productTree").yxtree("reload");
    $("#functioninfoGrid").yxgrid("reload");
};
$(function() {
    $("#productTree").yxtree({
        url: '?model=product_terminal_product&action=getProduct',
        event: {
            "node_click": function(event, treeId, treeNode) {
                var functioninfoGrid = $("#functioninfoGrid").data('yxgrid');
                functioninfoGrid.options.param['productId'] = treeNode.id;
                $("#productName").val(treeNode.name);
                $("#productId").val(treeNode.id);
                functioninfoGrid.reload();
            }
        }
    });
    $("#functioninfoGrid").yxgrid({
        model: 'product_terminal_functioninfo',
        title: '��������Ϣ����',
        //����Ϣ
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },{
                name :'functionName',
                display:'����������',
                sortable: true,
                width: 130
            }, {
                name: 'productName',
                display: '������Ʒ����',
                sortable: true,
                width: 130
            }, {
                name: 'productId',
                display: '������ƷId',
                hide: true
            }, {
                name: 'typeName',
                display: '��������',
                sortable: true
            }, {
                name: 'typeId',
                display: '��������Id',
                hide: true
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
                showThickboxWin("?model=product_terminal_functioninfo&action=toAdd&productName="
                        + $("#productName").val()
                        + "&productId="
                        + $("#productId").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
            }
        },
        searchitems: [{
                display: '����������',
                name: 'functionName'
            }]
    });
});