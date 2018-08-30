var show_page = function () {
    $("#otherGrid").yxgrid("reload");
};

$(function () {

    //��ͷ��ť����
    var buttonsArr = [];

    //��ͷ��ť����
    var excelOutArr = {
        name: 'exportOut',
        text: "����",
        icon: 'excel',
        action: function () {
            var gridObj = $("#otherGrid");
            var thisGrid = gridObj.data('yxgrid');
            var data = gridObj.yxgrid("getData");
            var advSql = data['advSql'] ? data['advSql'] : "";

            var url = "?model=contract_other_other&action=exportExcel"
                    + '&status=' + filterUndefined(thisGrid.options.param.status)
                    + '&fundType=' + filterUndefined(thisGrid.options.param.fundType)
                    + '&advSql=' + advSql
                ;
            window.open(url, "", "width=200,height=200,top=200,left=200");
        }
    };

    // �첽���õ���Ȩ��
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '����Ȩ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(excelOutArr);
            }
        }
    });

    // �첽���õ���Ȩ��
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '�ؿ��ʼ�֪ͨȨ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                // �����ʼ�֪ͨ��ť
                buttonsArr.push({
                    name: 'mail',
                    text: "�ؿ��ʼ�֪ͨ",
                    icon: 'search',
                    action: function () {
                        showThickboxWin('?model=contract_other_other&action=toSendMail'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800')
                    }
                });
            }
        }
    });

    // ��Ʊ¼��Ȩ��
    var invoiceLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '��ƱȨ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                invoiceLimit = true;
            }
        }
    });

    // ¼�벻��Ʊ���Ȩ��
    var unInvoiceLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '¼�벻��Ʊ���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                unInvoiceLimit = true;
            }
        }
    });

    // ¼���޷�̯��ƱȨ��
    var noShareCostLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '¼���޷�̯��Ʊ'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                noShareCostLimit = true;
            }
        }
    });

    // ��������Ȩ��
    var assistantLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '��������'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                assistantLimit = true;
            }
        }
    });

    // �޸ķ�̯��ϸȨ��
    var costLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '�޸ķ�̯��ϸ'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                costLimit = true;
            }
        }
    });

    // �رպ�ͬȨ��
    var closeLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '�رպ�ͬȨ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                closeLimit = true;
            }
        }
    });
    var assLimit = $("#assLimit").val();
    var autoloadVal = $("#autoload").val();
    if (autoloadVal == "") {
        autoloadVal = false;
    }
    $("#otherGrid").yxgrid({
        model: 'contract_other_other',
        action: 'pageJsonFinanceInfo',
        title: '������ͬ',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        customCode: 'otherGrid',
        isOpButton: false,
        autoload: autoloadVal,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'createDate',
            display: '¼������',
            width: 70
        }, {
            name: 'fundTypeName',
            display: '��������',
            sortable: true,
            width: 70,
            process: function (v, row) {
                if (row.fundType == 'KXXZB') {
                    return '<span style="color:blue">' + v + '</span>';
                } else if (row.fundType == 'KXXZA') {
                    return '<span style="color:green">' + v + '</span>';
                } else {
                    return v;
                }
            }
        }, {
            name: 'orderCode',
            display: '������ͬ��',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (row.status == 4) {
                    return "<a href='#' style='color:red' title='����еĺ�ͬ' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    return "<a href='#' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                }
            }
        }, {
            name: 'orderName',
            display: '��ͬ����',
            sortable: true,
            width: 130
        }, {
            name: 'signCompanyName',
            display: 'ǩԼ��˾',
            sortable: true,
            width: 150
        }, {
            name: 'businessBelongName',
            display: '������˾',
            sortable: true,
            width: 100
        }, {
            name: 'proName',
            display: '��˾ʡ��',
            sortable: true,
            width: 70
        }, {
            name: 'address',
            display: '��ϵ��ַ',
            sortable: true,
            hide: true
        }, {
            name: 'phone',
            display: '��ϵ�绰',
            sortable: true,
            hide: true
        }, {
            name: 'linkman',
            display: '��ϵ��',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'signDate',
            display: 'ǩԼ����',
            sortable: true,
            width: 80
        }, {
            name: 'currency',
            display: '����',
            width: 70
        }, {
            name: 'orderMoney',
            display: '��ͬ�ܽ��',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'payForBusinessName',
            display: '����ҵ������',
            sortable: true,
            process: function (v, row) {
                return (v == "NULL") ? "" : v;
            }
        }, {
            name: 'payApplyMoney',
            display: '���븶��',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 == 0) {
                        return 0;
                    } else {
                        var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ���������Ϊ��' + moneyFormat2(row.countPayApplyMoney);
                        return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
                    }
                }
            },
            width: 80
        }, {
            name: 'payedMoney',
            display: '�Ѹ����',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 == 0) {
                        return 0;
                    } else {
                        var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ�����Ϊ��' + moneyFormat2(row.countPayMoney);
                        return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
                    }
                }
            },
            width: 80
        }, {
            name: 'returnMoney',
            display: '������',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 80
        }, {
            name: 'invotherMoney',
            display: '���շ�Ʊ',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        var thisTitle = '���г�ʼ������Ʊ���Ϊ: ' + moneyFormat2(row.initInvotherMoney) + ',������Ʊ���Ϊ��' + moneyFormat2(row.countInvotherMoney);
                        return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 80
        }, {
            name: 'confirmInvotherMoney',
            display: '����ȷ�Ϸ�Ʊ',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 80
        }, {
            name: 'needInvotherMoney',
            display: 'δ�ؿ�/δ��Ʊ���',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 150
        }, {
            name: 'applyInvoice',
            display: '���뿪Ʊ',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZA') {
                    if (row.id == 'noId') {
                        return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 == 0) {
                        return 0;
                    } else {
                        return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    }
                }
            },
            width: 80
        }, {
            name: 'invoiceMoney',
            display: '�ѿ���Ʊ',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZA') {
                    if (row.id == 'noId') {
                        return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 80
        }, {
            name: 'incomeMoney',
            display: '�տ���',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZA') {
                    if (row.id == 'noId') {
                        return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 80
        }, {
            name: 'uninvoiceMoney',
            display: '����Ʊ���',
            sortable: true,
            process: function (v, row) {
                // if (row.fundType != 'KXXZA') {
                if (row.id == 'noId') {
                    return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
                    // }
                    // return '--';
                } else {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return '<a href="javascript:void(0)" style="color:green" onclick="javascript:showThickboxWin(\'?model=contract_uninvoice_uninvoice&action=toObjList&objId='
                            + row.id
                            + '&objType=KPRK-09'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'
                            + moneyFormat2(v) + '</a>';
                    }
                }
            },
            width: 80
        }, {
            name: 'principalName',
            display: '��ͬ������',
            sortable: true,
            hide: true
        }, {
            name: 'deptName',
            display: '��������',
            sortable: true,
            hide: true
        }, {
            name: 'status',
            display: '״̬',
            sortable: true,
            width: 60,
            process: function (v) {
                if (v == '0') {
                    return "δ�ύ";
                } else if (v == 1) {
                    return "������";
                } else if (v == 2) {
                    return "ִ����";
                } else if (v == 3) {
                    return "�ѹر�";
                } else if (v == 4) {
                    return "�����";
                }
            }
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 60
        }, {
            name: 'signedStatus',
            display: '��ͬǩ��',
            sortable: true,
            process: function (v, row) {
                if (row.id == "noId") {
                    return '';
                }
                if (v == "1") {
                    return "��ǩ��";
                } else {
                    return "δǩ��";
                }
            },
            width: 70
        }, {
            name: 'objCode',
            display: 'ҵ����',
            sortable: true,
            width: 120
        }, {
            name: 'isNeedStamp',
            display: '���������',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (v == "0") {
                    return "��";
                } else if (v == "1") {
                    return "��";
                }
            }
        }, {
            name: 'isStamp',
            display: '�Ƿ��Ѹ���',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (v == "0") {
                    return "��";
                } else if (v == "1") {
                    return "��";
                }
            }
        }, {
            name: 'stampType',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'createName',
            display: '������',
            sortable: true
        }, {
            name: 'remark',
            display: '��ע',
            sortable: true,
            width: 150
        }, {
            name: 'updateTime',
            display: '����ʱ��',
            sortable: true,
            width: 130
        }, {
            name: 'chanceCode',
            display: '�̻����',
            sortable: true,
            width: 120
        }, {
            name: 'prefBidDate',
            display: 'Ԥ��Ͷ��ʱ��',
            sortable: true,
            width: 120
        }, {
            name: 'contractCode',
            display: '���ۺ�ͬ���',
            sortable: true,
            width: 120
        }, {
            name: 'projectPrefEndDate',
            display: '��ĿԤ�ƽ���ʱ��',
            sortable: true,
            width: 120
        }, {
            name: 'delayPayDays',
            display: '�Ӻ�ؿ�����',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (v == "0") {
                    return "";
                } else {
                    return v;
                }
            }
        }, {
            name: 'isBankbackLetter',
            display: '�Ƿ������б���',
            sortable: true,
            width: 90,
            process: function (v, rowData) {
                if (v == "0") {
                    return (rowData.payForBusiness == "FKYWLX-03" || rowData.payForBusiness == "FKYWLX-04")? "��" : "";
                } else if (v == "1") {
                    return "��";
                }
            }
        },{
            name: 'prefPayDate',
            display: 'Ԥ��Ѻ��ؿ�ʱ��',
            sortable: true,
            width: 120
        }],
        toAddConfig: {
            formWidth: 1000,
            formHeight: 500
        },
        toEditConfig: {
            formWidth: 1000,
            formHeight: 500,
            showMenuFn: function (row) {
                return row.ExaStatus == "���ύ" || row.ExaStatus == "���";
            }
        },
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�鿴��ͬ',
            icon: 'view',
            showMenuFn: function (row) {
                return row.id != "noId";
            },
            action: function (row, rows, grid) {
                if (row) {
                    showModalWin("?model=contract_other_other&action=viewTab&id="
                        + row.id
                        + "&fundType="
                        + row.fundType
                        + "&skey=" + row.skey_
                    );
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '¼�뷢Ʊ',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || invoiceLimit == false) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                if (row.orderMoney * 1 <= accAdd(row.invotherMoney, row.returnMoney, 2) * 1) {
                    alert('��ͬ��¼�뷢Ʊ������');
                    return false;
                }
                showModalWin("?model=finance_invother_invother&isAudit=1&action=toAddObj&objType=YFQTYD02&objId=" + row.id, 1, row.id);
            }
        }, {
            text: '¼�뷢Ʊ(�޷�̯)',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || invoiceLimit == false || noShareCostLimit == false) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                if (row.orderMoney * 1 <= accAdd(row.invotherMoney, row.returnMoney, 2) * 1) {
                    alert('��ͬ��¼�뷢Ʊ������');
                    return false;
                }
                showModalWin("?model=finance_invother_invother&isAudit=1&action=toAddObj&shareCost=0&objType=YFQTYD02&objId=" + row.id, 1, row.id);
            }
        }, {
            text: '¼�벻��Ʊ���',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3) {
                    return false;
                } else if (unInvoiceLimit == false) {//Ȩ�޿���
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function (row) {
                showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
                    + row.id
                    + '&objCode='
                    + row.orderCode
                    + '&objType=KPRK-09'
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
            }
        }, {
            text: '¼�뷵��',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || invoiceLimit == false) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                showThickboxWin("?model=contract_other_other&action=toUpdateReturnMoney&id="
                    + row.id
                    + "&skey=" + row.skey_
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
            }
        }, {
            text: '���븶��',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || assistantLimit == false) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                if (row) {
                    var data = '';
                    $.ajax({
                        type: "POST",
                        url: "?model=contract_other_other&action=canPayapply",
                        data: {"id": row.id},
                        async: false,
                        success: function (data) {
                            data = data;
                        }
                    });
                    if (data == 'hasBack') {
                        alert('��ͬ����δ������ɵ��˿���������븶��');
                        return false;
                    } else { //������Լ�������
                        showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + row.id, 1, row.id);
                    }
                } else {
                    alert("��ѡ��һ������");
                }
            }
            // }, {
            //     name: 'stamp',
            //     text: '�������',
            //     icon: 'add',
            //     showMenuFn: function (row) {
            //         if (row.status == 3 || assistantLimit == false) {
            //             return false;
            //         }
            //         if (row.ExaStatus == "���") {
            //             if (row.isNeedStamp == '0') {
            //                 return true;
            //             } else {
            //                 return row.isStamp == '1';
            //             }
            //         }
            //         else
            //             return false;
            //
            //     },
            //     action: function (row, rows, grid) {
            //         if (row) {
            //             showThickboxWin("?model=contract_other_other&action=toStamp&id="
            //             + row.id
            //             + "&skey=" + row.skey_
            //             + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=750");
            //         } else {
            //             alert("��ѡ��һ������");
            //         }
            //     }
        }, {
            name: 'change',
            text: '�޸ķ�̯��ϸ',
            icon: 'edit',
            showMenuFn: function (row) {
                return row.ExaStatus == "���" && (row.fundType == 'KXXZB' || row.fundType == 'KXXZC') && row.status == '2' && costLimit;
            },
            action: function (row) {
                showModalWin("?model=contract_other_other&action=toChangeCostShare&id="
                    + row.id
                    + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            text: '�رպ�ͬ',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.ExaStatus == "���") {
                    return !(row.status == 3 || closeLimit == false);
                } else {
                    return false;
                }
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=contract_other_other&action=toClose&id="
                        + row.id
                        + "&closeLimit=" + closeLimit
                        + "&skey=" + row.skey_
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }],
        buttonsEx: buttonsArr,

        // �߼�����
        advSearchOptions: {
            modelName: 'otherGrid',
            // ѡ���ֶκ��������ֵ����
            selectFn: function ($valInput) {
                $valInput.yxselect_user("remove");
                $valInput.yxcombogrid_signcompany("remove");
            },
            searchConfig: [{
                name: '������ͬ��',
                value: 'c.orderCode'
            }, {
                name: 'ǩԼ����',
                value: 'c.signDate',
                changeFn: function ($t, $valInput) {
                    $valInput.click(function () {
                        WdatePicker({
                            dateFmt: 'yyyy-MM-dd'
                        });
                    });
                }
            }, {
                name: 'ǩԼ��˾',
                value: 'c.signCompanyName',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#signCompanyId" + rowNum)[0]) {
                        $hiddenCmp = $("<input type='hidden' id='signCompanyId" + rowNum + "'/>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxcombogrid_signcompany({
                        hiddenId: 'signCompanyId' + rowNum,
                        height: 250,
                        width: 550,
                        isShowButton: false
                    });
                }
            }, {
                name: '��˾ʡ��',
                value: 'c.proName'
            }, {
                name: '��ͬ���',
                value: 'c.orderMoney'
            }, {
                name: '��������',
                value: 'c.fundType',
                type: 'select',
                datacode: 'KXXZ'
            }]
        },
        searchitems: [{
            display: '������',
            name: 'principalName'
        }, {
            display: '������',
            name: 'createName'
        }, {
            display: 'ǩԼ��˾',
            name: 'signCompanyName'
        }, {
            display: '��ͬ����',
            name: 'orderName'
        }, {
            display: '��ͬ���',
            name: 'orderCodeSearch'
        }, {
            display: 'ҵ����',
            name: 'objCodeSearch'
        }],
        // Ĭ�������ֶ���
        sortname: "c.createTime",
        // Ĭ������˳�� ����DESC ����ASC
        sortorder: "DESC",
        // ����״̬���ݹ���
        comboEx: [{
            text: "��������",
            key: 'fundType',
            datacode: 'KXXZ'
        }, {
            text: "����ҵ������",
            key: 'payForBusiness',
            datacode: 'FKYWLX'
        }, {
            text: '��ͬ״̬',
            key: 'status',
            value: 2,
            data: [{
                text: 'δ�ύ',
                value: '0'
            }, {
                text: '������',
                value: '1'
            }, {
                text: 'ִ����',
                value: '2'
            }, {
                text: '�ѹر�',
                value: '3'
            }, {
                text: '�����',
                value: '4'
            }]
        }, {
            text: '��Ʊ�Ա�',
            key: 'payandinv',
            data: [{
                text: '����',
                value: '1'
            }, {
                text: '���ڵ���',
                value: '2'
            }, {
                text: '����',
                value: '3'
            }, {
                text: 'С�ڵ���',
                value: '4'
            }, {
                text: 'С��',
                value: '5'
            }
            ]
        }]
    });
});