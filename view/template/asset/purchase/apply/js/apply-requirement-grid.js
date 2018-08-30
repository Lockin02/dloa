var show_page = function (page) {
    $("#requirementGrid").yxsubgrid("reload");
};

$(function () {
    var menusArr = [{
        text: '�鿴',
        icon: 'view',
        action: function (row) {
            showOpenWin('?model=asset_purchase_apply_apply&action=toViewTab&applyId='
                + row.id
                + '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
        }
    }, {
        text: '�ύ����',
        icon: 'edit',
        showMenuFn: function (row) {
            if (row.state == "���ύ" || row.purchaseDept == '1') {
                return false;
            }
            return true;
        },
        action: function (row) {
            showThickboxWin('?model=asset_purchase_apply_apply&action=initRequire&id='
                + row.id +
                '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000');
        }
    }, {
        text: '��д���յ�',
        icon: 'add',
        showMenuFn: function (row) {
            if (row.state == "���ύ" && (row.purchaseDept == "0" || row.purchaseDept == "2")) {
                return true;
            }
            return false;
        },
        action: function (row, rows, grid) {
            if (row) {
                console.log(row);
                showThickboxWin("?model=asset_purchase_receive_receive&action=toRequireAdd"
                    + "&code="
                    + row.formCode
                    + "&applicantName="
                    + row.createName
                    + "&applicantId="
                    + row.createId
                    + "&applyId="
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
            } else {
                alert("��ѡ��һ������");
            }
        }
    }];
    // var attachment = {
    //     text: '����ɹ�',
    //     icon: 'edit',
    //     showMenuFn: function (row) {
    //         if (row.state == "���ύ" || row.purchaseDept == '1') {
    //             return false;
    //         }
    //         return true;
    //     },
    //     action: function (row) {
    //         showThickboxWin('?model=asset_purchase_apply_apply&action=toAssignPurchaser&id='
    //             + row.id +
    //
    //             '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000');
    //     }
    // };
    // $.ajax({
    //     type: 'POST',
    //     url: '?model=asset_purchase_apply_apply&action=getLimits',
    //     data: {
    //         'limitName': '�ɹ�����'
    //     },
    //     async: false,
    //     success: function (data) {
    //         if (data == 1) {
    //             menusArr.push(attachment);
    //         }
    //     }
    // });
    $("#requirementGrid").yxsubgrid({
        model: 'asset_purchase_apply_apply',
        action: "areaListPageJson",
        param: {
            "ExaStatus": '���',
            "ifShow": '0'
        },
        title: '�ɹ�����',
        showcheckbox: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'formCode',
            display: '���ݱ��',
            sortable: true,
            width: 100
        }, {
            name: 'relDocCode',
            display: '�������뵥��',
            sortable: true,
            width: 120
        }, {
            name: 'state',
            display: '����״̬',
            sortable: true,
            width: 60
        }, {
            name: 'applyTime',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'applicantName',
            display: '������',
            sortable: true,
            width: 80
        }, {
            name: 'userName',
            display: 'ʹ����',
            sortable: true,
            width: 80
        }, {
            name: 'useDetName',
            display: 'ʹ�ò���',
            sortable: true,
            width: 80
        }, {
            name: 'assetUse',
            display: '�ʲ���;',
            sortable: true,
            width: 80
        }, {
            name: 'purchaseDept',
            display: '�ɹ�����',
            sortable: true,
            width: 80,
            process: function (v) {
                if (v == '0') {
                    return "������";
                } else if (v == '1') {
                    return "������";
                } else if (v == "2") {
                    return '��Ϥ������';
                } else {
                    return "";
                }
            }
        }, {
            name: 'estimatPrice',
            display: 'Ԥ���ܼ�',
            sortable: true,
            // �б��ʽ��ǧ��λ
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'moneyAll',
            display: '�ܽ��',
            sortable: true,
            // �б��ʽ��ǧ��λ
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'agencyName',
            display: '�ɹ�����',
            width: 80
        }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=asset_purchase_apply_applyItem&action=IsDelPageJson',
            param: [{
                paramId: 'applyId',
                colId: 'id'
            }],
            colModel: [{
                display: '��������',
                name: 'productName',
                process: function (v, row) {
                    if (v == '') {
                        return row.inputProductName;
                    }
                    return v;
                },
                tclass: 'readOnlyTxtItem'
            }, {
                display: '���',
                name: 'pattem',
                tclass: 'readOnlyTxtItem'
            }, {
                display: '��������',
                name: 'applyAmount',
                tclass: 'txtshort'
            }, {
                display: '��Ӧ��',
                name: 'supplierName',
                tclass: 'txtmiddle'
            }, {
                display: '��λ',
                name: 'unitName',
                tclass: 'readOnlyTxtItem'
            }, {
                display: '�ɹ�����',
                name: 'purchAmount',
                tclass: 'txtshort'
            }, {
                display: '����',
                name: 'price',
                tclass: 'txtshort',
                // �б��ʽ��ǧ��λ
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                display: '���',
                name: 'moneyAll',
                tclass: 'txtshort',
                // �б��ʽ��ǧ��λ
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                display: 'ϣ����������',
                name: 'dateHope',
                type: 'date'
            }, {
                display: '��ע',
                name: 'remark',
                tclass: 'txt'
            }]
        },
        // ��չ�Ҽ��˵�
        menusEx: menusArr,
        comboEx: [{
            text: '����״̬',
            key: 'state',
            data: [{
                text: 'δ�ύ',
                value: 'δ�ύ'
            }, {
                text: '���ύ',
                value: '���ύ'
            }]
        }],
        /**
         * ��������
         */
        searchitems: [{
            display: '���ݱ��',
            name: 'formCode'
        }, {
            display: '�������뵥��',
            name: 'relDocCode'
        }, {
            display: '��������',
            name: 'applyTime'
        }, {
            display: '������',
            name: 'applicantName'
        }, {
            display: 'ʹ��������',
            name: 'userName'
        }, {
            display: '��������',
            name: 'productName'
        }]
    });
});