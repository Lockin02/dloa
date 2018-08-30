var show_page = function (page) {
    $("#applyGrid").yxsubgrid("reload");
};
$(function () {
    $("#applyGrid").yxsubgrid({
        model: 'asset_purchase_apply_apply',
        title: '�ʲ��ɹ�����ȷ���б�',
        showcheckbox: false,
        param: {
            "totalManagerId": $("#userId").val(),
            "isSetMyList": 'true',
            // purchaseDepts: "0,2",
            stateNoIn: "δ�ύ"
        },
        isDelAction: false,
        isAddAction: false,
        isEditAction: false,
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'applyTime',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'formCode',
            display: '���ݱ��',
            sortable: true,
            width: 110
        }, {
            name: 'purchaseDept',
            display: '�ɹ�����',
            sortable: true,
            process: function (v) {
                if (v == "1") {
                    return '������';
                } else if (v == "2") {
                    return '��Ϥ������';
                } else {
                    return '������';
                }
            },
            width: 80
        }, {
            name: 'applicantName',
            display: '����������',
            sortable: true,
            hide: true
        }, {
            name: 'userName',
            display: 'ʹ��������',
            sortable: true
        }, {
            name: 'useDetName',
            display: 'ʹ�ò���',
            sortable: true,
            width: 80
        }, {
//			name : 'purchCategory',
//			display : '�ɹ�����',
//			sortable : true,
//			datacode : 'CGZL'
//		}, {
            name: 'assetUse',
            display: '�ʲ���;',
            sortable: true
        }, {
            name: 'state',
            display: '����״̬',
            sortable: true,
            width: 80
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 90
        }, {
            name: 'ExaDT',
            display: '����ʱ��',
            sortable: true
        }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=asset_purchase_apply_applyItem&action=pageJson',
            param: [{
                paramId: 'applyId',
                colId: 'id'
            }],
            colModel: [{
                name: 'inputProductName',
                width: 200,
                display: '��������'
            }, {
                name: 'pattem',
                display: "���"
            }, {
                name: 'unitName',
                display: "��λ",
                width: 50
            }, {
                name: 'applyAmount',
                display: "��������",
                width: 70
            }, {
                name: 'issuedAmount',
                display: "�´�����",
                width: 70,
                process: function (v) {
                    if (v == "") {
                        return 0;
                    } else {
                        return v;
                    }

                }
            }, {
                name: 'dateHope',
                display: "ϣ����������"
            }, {
                name: 'remark',
                display: "��ע"
            }]
        },
        comboEx: [{
            text: '����״̬',
            key: 'state',
            value: 'δȷ��',
            data: [{
                text: 'δȷ��',
                value: 'δȷ��'
            }, {
                text: '��ȷ��',
                value: '��ȷ��'
            }, {
            //    text: '���ύ',
            //    value: '���ύ'
            //}, {
                text: '���',
                value: '���'
            }]
        }],
        /**
         * ��������
         */
        searchitems: [{
            display: '���ݱ��',
            name: 'formCode'
        }, {
            display: 'ʹ�ò���',
            name: 'useDetName'
        }, {
            display: '��������',
            name: 'productName'
        }],
        toAddConfig: {
            formWidth: 900,
            /**
             * ������Ĭ�ϸ߶�
             */
            formHeight: 600
        },
        toViewConfig: {
            /**
             * �鿴��Ĭ�Ͽ��
             */
            formWidth: 900,
            /**
             * �鿴��Ĭ�ϸ߶�
             */
            formHeight: 600
        },
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: 'ȷ������',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.state == 'δȷ��' && row.ExaStatus == "���ύ") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin('index1.php?model=asset_purchase_apply_apply&action=init&perm=toConfirm&id=' + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=850");
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '���',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.state == 'δȷ��' && row.ExaStatus == "���ύ") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                var id = row.id;
                if (confirm("ȷ��Ҫ��ظ����뵥��?")) {
                    $.ajax({
                        type: "POST",
                        url: '?model=asset_purchase_apply_apply&action=dispassApply',
                        data: {"id": id},
                        success: function (result) {
                            var resultObj = eval("(" + result + ")");
                            if (resultObj.result == 'ok') {
                                alert("��سɹ�!");
                            } else {
                                alert("���ʧ��!");
                            }
                            show_page();
                            tb_remove();
                        }
                    });
                }
            }
        }]
    });
});