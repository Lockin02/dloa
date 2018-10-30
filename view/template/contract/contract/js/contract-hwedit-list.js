// ���㷽��
function countAll(rowNum) {
    var beforeStr = "productInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // ��ȡ��ǰ��
        var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // ��ȡ��ǰ����
        var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // ���㱾�н�� - ����˰
        var thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}


function countAllP(rowNum) {
    var beforeStr = "borrowConEquInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // ��ȡ��ǰ��
        var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // ��ȡ��ǰ����
        var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // ���㱾�н�� - ����˰
        var thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}

$(function () {
    proInfoList();

    $("#linkmanListInfo").yxeditgrid({
        objName: 'contract[linkman]',
        url: '?model=contract_contract_linkman&action=listJsonLimit',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'prinvipalId': $("#prinvipalId").val(),
            'createId': $("#createId").val(),
            'areaPrincipalId': $("#areaPrincipalId").val(),
            'isTemp': '0',
            'isDel': '0'
        },
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '�ͻ���ϵ��',
                name: 'linkmanName',
                tclass: 'txt'
            },
            {
                display: '��ϵ��ID',
                name: 'linkmanId',
                type: 'hidden'
            },
            {
                display: '�绰',
                name: 'telephone',
                tclass: 'txt'
            },
            {
                display: 'ְλ',
                name: 'position',
                tclass: 'txt'
            },
            {
                display: '����',
                name: 'Email',
                tclass: 'txt'
            },
            {
                display: '��ע',
                name: 'remark',
                tclass: 'txt',
                width: 500
            }
        ]
    });


    if ($("#isSubAppChange").val() == '1') {
        var param = {
            'contractId': $("#contractId").val(),
            'isDel': '0',
            'isBorrowToorder': '0'
        };
    } else {
        var param = {
            'contractId': $("#contractId").val(),
            'isTemp': '0',
            'isDel': '0',
            'isBorrowToorder': '0'
        };
    }
    //�����嵥
    $("#materialInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        type: 'view',
        tableClass: 'form_in_table',
        param: param,
        colModel: [
            {
                display: '���ϱ��',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '��������',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '����Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '��ִ������',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '���˿�����',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: 'ʵ��ִ������',
                name: 'actNum'
            },
            {
                display: '����',
                name: 'price',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v, 6);
                }
            },
            {
                display: '���',
                name: 'money',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '��������Id',
                name: 'license',
                type: 'hidden'
            },
            {
                name: 'licenseButton',
                display: '��������',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\"" + row.license
                            + "\")'>�鿴</a>";
                    }
                }
            }
        ]
    });
    //�տ��ƻ�
    $("#financialplanInfo").yxeditgrid({
        objName: 'contract[financialplan]',
        url: '?model=contract_contract_financialplan&action=listJsonLimit',
        type: 'view',
        tableClass: 'form_in_table',
        param: {
            'contractId': $("#contractId").val()
        },
        colModel: [
            {
                display: '����',
                name: 'planDate',
                type: 'date'
            },
            {
                display: '��Ʊ���',
                name: 'invoiceMoney',
                tclass: 'txtshort',
                type: 'money'
            },
            {
                display: '�տ���',
                name: 'incomeMoney',
                tclass: 'txtshort',
                type: 'money'
            },
            {
                display: '��ע',
                name: 'remark',
                tclass: 'txtlong'
            }
        ]
    });
    //������ת����
    $("#borrowConEquInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': '0',
            'isDel': '0',
            'isBorrowToorder': '1'
        },
        isAddOneRow: false,
        isAdd: false,
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '������Ʒ',
                name: 'conProduct',
                tclass: 'txt'
            },
            {
                display: '���ϱ��',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '��������',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '����Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '��ִ������',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '���˿�����',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: 'ʵ��ִ������',
                name: 'actNum'
            },
            {
                display: '����',
                name: 'price',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '���',
                name: 'money',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '��������Id',
                name: 'license',
                type: 'hidden'
            },
            {
                name: 'licenseButton',
                display: '��������',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\"" + row.license
                            + "\")'>�鿴</a>";
                    }
                }
            }
        ]

    });
});

//��Ʒ�嵥
function proInfoList() {
    var currency = $("#currency").html();
    if (currency != '�����') {
        var rate = $("#rate").html();
        //��Ʒ�嵥
        $("#productInfo").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_contract_product&action=listJsonLimit',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'contractId': $("#contractId").val(),
                'dir': 'ASC',
                'prinvipalId': $("#prinvipalId").val(),
                'createId': $("#createId").val(),
                'areaPrincipalId': $("#areaPrincipalId").val(),
                //			'isTemp' : '0',
                'isDel': '0'
            },
            colModel: [
                {
                    name: 'exeDeptName',
                    display: 'ִ������',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '��Ʒ����',
                    width: 80
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + $("#contractId").val()
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '��ƷId',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '����',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '����(' + currency + ')',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '����',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v * rate);
                    }
                },
                {
                    display: '���(' + currency + ')',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '���',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v * rate);
                    }
                },
                {
                    display: '��������Id',
                    name: 'license',
                    type: 'hidden'
                },
                //{
                //    name: 'licenseButton',
                //    display: '��������',
                //    process: function (v, row) {
                //        if (row.license != "") {
                //            return "<a href='javascript:void(0)' onclick='showLicense(\""
                //                + row.license + "\")'>��������</a>";
                //        }
                //    }
                //},
                {
                    display: '��Ʒ����Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    name: 'deployButton',
                    display: '��Ʒ����',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>��Ʒ����</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    } else {
        //��Ʒ�嵥
        $("#productInfo").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_contract_product&action=listJsonLimit',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'contractId': $("#contractId").val(),
                'dir': 'ASC',
                'prinvipalId': $("#prinvipalId").val(),
                'createId': $("#createId").val(),
                'areaPrincipalId': $("#areaPrincipalId").val(),
                //			'isTemp' : '0',
                'isDel': '0'
            },
            colModel: [
                {
                    name: 'exeDeptName',
                    display: 'ִ������',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '��Ʒ����',
                    width: 80
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + $("#contractId").val()
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '��ƷId',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '����',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '����',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '���',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                    //		}, {
                    //			display : '������',
                    //			name : 'warrantyPeriod',
                    //			tclass : 'txtshort'
                },
                {
                    display: '��������Id',
                    name: 'license',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    name: 'deployButton',
                    display: '��Ʒ����',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>��Ʒ����</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    }
}
