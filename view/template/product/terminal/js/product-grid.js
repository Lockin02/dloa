var show_page = function(page) {
    $("#productGrid").yxgrid("reload");
};
$(function() {
    $("#productGrid").yxgrid({
        model: 'product_terminal_product',
        title: '终端产品管理',
        //列信息
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'productName',
                display: '产品名称',
                sortable: true,
                width: 130
            }, {
                name: 'productCode',
                display: '产品编码',
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
        searchitems: [{
                display: '产品名称',
                name: 'productName'
            }, {
                display: '产品编码',
                name: 'productCode'
            }]
    });
});