/**
 * 下拉客户表格组件
 */
(function ($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_office', {
        options: {
            hiddenId: 'officeId',
            nameCol: 'officeName',
            gridOptions: {
                showcheckbox: false,
                model: 'engineering_officeinfo_officeinfo',
                action: 'pageJsonUsing',
                // 列信息
                colModel: [{
                    display: '产品线编码',
                    name: 'productLine',
                    hide: true
                }, {
                    display: '执行部门',
                    name: 'productLineName',
                    width: '80'
                }, {
                    display: '区域',
                    name: 'officeName',
                    width: '80'
                }, {
                    display: '服务总监',
                    name: 'mainManager'
                }, {
                    display: '服务经理',
                    name: 'managerName',
                    width: '120'
                }, {
                    display: '责任范围',
                    name: 'rangeName',
                    width: '180'
                }, {
                    display: '备注',
                    name: 'TypeOne',
                    hide: true
                }],
                // 快速搜索
                searchitems: [{
                    display: '区域名称',
                    name: 'officeName'
                }],
                // 默认搜索字段名
                sortname: "id",
                // 默认搜索顺序
                sortorder: "ASC"
            }
        }
    });
})(jQuery);