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
        title: '终端分类管理',
        //列信息
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'productName',
                display: '所属产品名称',
                sortable: true,
                width: 130
            }, {
                name: 'productId',
                display: '所属产品Id',
                sortable: true,
                 hide: true
            }, {
                name: 'typeName',
                display: '终端分类名称',
                sortable: true
            }, {
                name: 'orderIndex',
                display: '顺序',
                sortable: true,
                width: 130
            }, {
                name: 'remark',
                display: '备注',
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
                display: '产品名称',
                name: 'productName'
            }, {
                display: '终端分类名称',
                name: 'typeName'
            }]
    });
});