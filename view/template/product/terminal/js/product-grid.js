var show_page = function(page) {
    $("#productGrid").yxgrid("reload");
};
$(function() {
    $("#productGrid").yxgrid({
        model: 'product_terminal_product',
        title: '�ն˲�Ʒ����',
        //����Ϣ
        colModel: [{
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'productName',
                display: '��Ʒ����',
                sortable: true,
                width: 130
            }, {
                name: 'productCode',
                display: '��Ʒ����',
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
        searchitems: [{
                display: '��Ʒ����',
                name: 'productName'
            }, {
                display: '��Ʒ����',
                name: 'productCode'
            }]
    });
});