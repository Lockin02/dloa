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
                goodsbaseinfoGrid.option("newp", 1);//�ָ���һҳ
                $("#parentName").val(treeNode.name);
                $("#parentId").val(treeNode.id);
                goodsbaseinfoGrid.reload();
            }
        }
    });

    $("#goodsbaseinfoGrid").yxgrid({
        model: 'goods_goods_goodsbaseinfo',
        title: '��Ʒ������Ϣ',
        param: {
            goodsTypeId: -1
        },
        showcheckbox: false,
        isDelAction: false,
        isOpButton: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'goodsTypeName',
                display: '������������',
                width: 80,
                sortable: true
            },
            {
                name: 'goodsClass',
                display: '��Ʒ����',
                sortable: true,
//                datacode: 'HTCPFL',
                width: 150
            },
            {
                name: 'goodsName',
                display: '��Ʒ����',
                sortable: true,
                width: 200
            },
            {
                name: 'osGoodsName',
                display: '�����Ʒ����',
                sortable: true,
                width: 200
            },
            {
                name: 'unitName',
                display: '��λ',
                sortable: true,
                width: 50
            },
            {
                name: 'isEncrypt',
                display: '��Ҫ����',
                sortable: true,
                width: '70',
                process: function (v, row) {
                    if (v == "on") {
                        return "��";
                    } else {
                        return "��";
                    }
                }
            },
            {
                name: 'exeDeptName',
                display: '��Ʒ��',
                sortable: true
            },
            {
                name: 'auditDeptName',
                display: 'ִ������',
                width: 80,
                sortable: true
            },
            {
                name: 'useStatus',
                display: '״̬',
                sortable: true,
                datacode: 'WLSTATUS',
                width: 50
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true,
                width: 300
            },
            {
                name: 'description',
                display: '˵��',
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
                text: '���ñ༭',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin("?model=goods_goods_properties&action=toEditConfig&goodsId="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=1150");
                }
            },
            {
                text: '����Ԥ��',
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=goods_goods_properties&action=toPreView&goodsId="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
                }
            },
            {
                text: '�����汾',
                icon: 'add',
                action: function (row, rows, grid) {
                    showModalWin("?model=goods_goods_goodsbaseinfo&action=toAddWhole");
                }
            },
            {
                text: '�汾��ʷ',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin("?model=goods_goods_goodsbaseinfo&action=toShowHistory");
                }
            },
            {
                text: 'ɾ��',
                icon: 'delete',
                action: function (row, rows, grid) {
                    if (window.confirm("ȷ��Ҫɾ��?")) {
                        $.ajax({
                            type: "POST",
                            url: "?model=goods_goods_goodsbaseinfo&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    show_page();
                                    alert('ɾ���ɹ���');
                                } else {
                                    alert('ɾ��ʧ��!');
                                }
                            }
                        });
                    }
                }
            },
            {
                text: '������',
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
                text: '����',
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
                text: "������־",
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_goods_base_info"
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
                }
            }
        ],
        // ����״̬���ݹ���
        comboEx: [{
            text: "״̬",
            key: 'useStatus',
            value: 'WLSTATUSKF',
            datacode: 'WLSTATUS'
        }, {
            text: "��Ʒ��",
            key: 'exeDeptCode',
            datacode: 'HTCPX'
        }],
        searchitems: [
            {
                display: "��Ʒ����",
                name: 'goodsName'
            },
            {
                display: "�����Ʒ����",
                name: 'osGoodsName'
            },
            {
                display: "���ϱ��",
                name: 'productCode'
            },
            {
                display: "��������",
                name: 'productName'
            }
        ]
    });
});