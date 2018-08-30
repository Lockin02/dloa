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
        title: '终端分类管理',
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'terminalName',
                display: '终端名称',
                sortable: true,
                width: 130
            },{
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
                name: 'typeId',
                display: '终端分类Id',
                sortable: true,
                hide: true
            },  {
                name: 'deviceType',
                display: '设备类型',
                sortable: true,
            }/*,  {
                name: 'os',
                display: '操作系统',
                sortable: true,
            }*/,  {
                name: 'supportNetwork',
                display: '支持网络',
                sortable: true
            }, {
                name: 'versionStatus',
                display: '版本状态',
                datacode:"BBZT",
                sortable: true
            }, {
                name: 'formalVersion',
                display: '正式发布版本号',
                sortable: true
            }, {
                name: 'newVersion',
                display: '最新正式固件版本号',
                sortable: true,
                width: 140
            }, {
                name: 'softFunction',
                display: '软件功能',
                sortable: true
            }, {
                name: 'materialsName',
                display: '关联物料名称',
                sortable: true
            }, {
                name: 'materialsId',
                display: '关联物料id',
                hide: true
            }, {
                name: 'orderIndex',
                display: '顺序',
                sortable: true
            }, {
                name: 'remark',
                display: '备注',
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
                display: '终端名称',
                name: 'terminalName'
            }, {
                display: '产品名称',
                name: 'productName'
            },{
                display: '终端分类名称',
                name: 'typeName'
            }]
    });
});