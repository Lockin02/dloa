var show_page = function () {
    $("#goodsbaseinfoGrid").yxgrid("reload");
};

$(function () {
    $("#goodsTypeTree").yxtree({
        url: '?model=goods_goods_goodstype&action=getTreeData',
        event: {
            "node_click": function (event, treeId, treeNode) {
                var goodsbaseinfoGrid = $("#goodsbaseinfoGrid").data('yxgrid');
                goodsbaseinfoGrid.options.param['goodsTypeId'] = treeNode.id;
                goodsbaseinfoGrid.option("newp", 1);//恢复第一页
                $("#parentName").val(treeNode.name);
                $("#parentId").val(treeNode.id);
                goodsbaseinfoGrid.reload();
            }
        }
    });

    $("#goodsbaseinfoGrid").yxgrid({
        model: 'goods_goods_goodsbaseinfo',
        title: '产品基本信息',
        param: {
            goodsTypeId: -1
        },
        showcheckbox: false,
        isDelAction: false,
        isOpButton: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'goodsTypeName',
                display: '所属分类名称',
                width: 80,
                sortable: true
            },
            {
                name: 'goodsClass',
                display: '产品分类',
                sortable: true,
//                datacode: 'HTCPFL',
                width: 150
            },
            {
                name: 'goodsName',
                display: '产品名称',
                sortable: true,
                width: 200
            },
            {
                name: 'osGoodsName',
                display: '海外产品名称',
                sortable: true,
                width: 200
            },
            {
                name: 'unitName',
                display: '单位',
                sortable: true,
                width: 50
            },
            {
                name: 'isEncrypt',
                display: '需要加密',
                sortable: true,
                width: '70',
                process: function (v, row) {
                    if (v == "on") {
                        return "是";
                    } else {
                        return "否";
                    }
                }
            },
            {
                name: 'exeDeptName',
                display: '产品线',
                sortable: true
            },
            {
                name: 'auditDeptName',
                display: '执行区域',
                width: 80,
                sortable: true
            },
            {
                name: 'useStatus',
                display: '状态',
                sortable: true,
                datacode: 'WLSTATUS',
                width: 50
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true,
                width: 300
            },
            {
                name: 'description',
                display: '说明',
                sortable: true,
                width: 300
            }
        ],
        toAddConfig: {
            toAddFn: function (p) {
                showThickboxWin("?model=goods_goods_goodsbaseinfo&action=toAdd&parentName="
                + $("#parentName").val()
                + "&parentId="
                + $("#parentId").val()
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=420&width=750");
            }
        },
        toEditConfig: {
            action: 'toEdit',
            formHeight: 430,
            formWidth: 750
        },
        toViewConfig: {
            action: 'toView',
            formHeight: 350,
            formWidth: 750
        },
        menusEx: [
            {
                text: '配置编辑',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin("?model=goods_goods_properties&action=toEditConfig&goodsId="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=1150");
                }
            },
            {
                text: '配置预览',
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=goods_goods_properties&action=toPreView&goodsId="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
                }
            },
            {
                text: '发布版本',
                icon: 'add',
                action: function (row, rows, grid) {
                    showModalWin("?model=goods_goods_goodsbaseinfo&action=toAddWhole");
                }
            },
            {
                text: '版本历史',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin("?model=goods_goods_goodsbaseinfo&action=toShowHistory");
                }
            },
            {
                text: '删除',
                icon: 'delete',
                action: function (row, rows, grid) {
                    if (window.confirm("确认要删除?")) {
                        $.ajax({
                            type: "POST",
                            url: "?model=goods_goods_goodsbaseinfo&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    show_page();
                                    alert('删除成功！');
                                } else {
                                    alert('删除失败!');
                                }
                            }
                        });
                    }
                }
            },
            {
                text: '检测关联',
                icon: 'view',
                action: function (row) {
                    showThickboxWin('?model=goods_goods_goodsbaseinfo&action=toViewRelation&id='
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
                }
            },
            {
                text: '更新',
                icon: 'edit',
                showMenuFn: function (row) {
                    return true;
                },
                action: function (row) {
                    showThickboxWin('?model=goods_goods_goodsbaseinfo&action=toUpdate&id='
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');

                }
            },
            {
                name: 'view',
                text: "操作日志",
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_goods_base_info"
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
                }
            }
        ],
        // 审批状态数据过滤
        comboEx: [{
            text: "状态",
            key: 'useStatus',
            value: 'WLSTATUSKF',
            datacode: 'WLSTATUS'
        }, {
            text: "产品线",
            key: 'exeDeptCode',
            datacode: 'HTCPX'
        }],
        searchitems: [
            {
                display: "产品名称",
                name: 'goodsName'
            },
            {
                display: "海外产品名称",
                name: 'osGoodsName'
            },
            {
                display: "物料编号",
                name: 'productCode'
            },
            {
                display: "物料名称",
                name: 'productName'
            }
        ]
    });
});