/**
 * �����Է�������������
 */
(function($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_functiontype', {
        options: {
            hiddenId: 'typeId',
            nameCol: 'typeName',
            //isFocusoutCheck:false,
            gridOptions: {
                showcheckbox: false,
                model: 'product_terminal_functiontype',
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
                        name: 'typeName',
                        display: '��������',
                        sortable: true
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