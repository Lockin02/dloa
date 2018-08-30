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
        title: '功能项信息管理',
        //列信息
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },{
                name :'functionName',
                display:'功能项名称',
                sortable: true,
                width: 130
            }, {
                name: 'productName',
                display: '所属产品名称',
                sortable: true,
                width: 130
            }, {
                name: 'productId',
                display: '所属产品Id',
                hide: true
            }, {
                name: 'typeName',
                display: '分类名称',
                sortable: true
            }, {
                name: 'typeId',
                display: '分类名称Id',
                hide: true
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
                showThickboxWin("?model=product_terminal_functioninfo&action=toAdd&productName="
                        + $("#productName").val()
                        + "&productId="
                        + $("#productId").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
            }
        },
        searchitems: [{
                display: '功能项名称',
                name: 'functionName'
            }]
    });
});