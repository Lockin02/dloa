var show_page = function () {
    $("#goodsbaseinfoGrid").yxgrid("reload");
};
$(function () {
    $("#goodsbaseinfoGrid").yxgrid({
        title: '产品版本历史',
        data : {
            'collection' : [
                { 'id' : 1 ,'version' : 'v0.0.1', 'createName' : 'admin', 'createTime' : '2014-09-12 08:12:10', 'remark' : '测试版本发布'}
            ],
            'page' : 1,
            'totalSize' : 1
        },
        showcheckbox: false,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'version',
                display: '版本号',
                width: 80,
                sortable: true
            },
            {
                name: 'createName',
                display: '创建人',
                sortable: true,
                width: 80
            },
            {
                name: 'createTime',
                display: '创建日期',
                sortable: true,
                width: 140
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true,
                width: 300
            }
        ],
        menusEx: [
            {
                name: 'view',
                text: "查看变更详情",
                icon: 'view',
                action: function (row, rows, grid) {
                    alert('查看变更详情');
                }
            }
        ],
        searchitems: [
            {
                display: "版本号",
                name: 'version'
            }
        ]
    });
});