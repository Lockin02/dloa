/**
 * �ն˲�Ʒ����������
 */
(function($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_terminalProduct', {
        options: {
            hiddenId: 'productId',
            nameCol: 'productName',
            //isFocusoutCheck:false,
            gridOptions: {
//                showcheckbox: false,
                model: 'product_terminal_product',
                action: 'pageJson',
//                param: {'isStart': '0'},
                pageSize: 8,
                // ����Ϣ
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
                    }],
                // Ĭ�������ֶ���
                sortname: "orderIndex",
                // Ĭ������˳��
                sortorder: "ASC"
            }
        }
    });
})(jQuery);