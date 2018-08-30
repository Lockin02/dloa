/**
 * 终端产品下拉表格组件
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
                // 列信息
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
                    }],
                // 默认搜索字段名
                sortname: "orderIndex",
                // 默认搜索顺序
                sortorder: "ASC"
            }
        }
    });
})(jQuery);