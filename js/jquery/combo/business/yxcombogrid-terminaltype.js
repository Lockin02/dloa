/**
 * 终端分类下拉表格组件
 */
(function($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_terminaltype', {
        options: {
//            hiddenId: 'typeId',
//            nameCol: 'typeName',
            //isFocusoutCheck:false,
            gridOptions: {
//                showcheckbox: false,
                model: 'product_terminal_terminaltype',
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
                        name: 'typeName',
                        display: '终端分类名称',
                        sortable: true
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