var show_page = function () {
    $("#otherGrid").yxgrid("reload");
};

$(function () {

    $("#otherGrid").yxgrid({
        model: 'contract_other_other',
        action: 'pageJsonInfo',
        param : {"fundType":'KXXZB',"payForBusinessArr":"FKYWLX-04,FKYWLX-03,FKYWLX-06,FKYWLX-07","ids":$("#ids").val()},
        title: '������ͬ',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        customCode: 'otherGrid',
        isOpButton: false,
        showcheckbox : false,
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
            display: 'ǷƱ���',
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
            name: 'payForBusinessName',
            display: '����ҵ������',
            sortable: true,
            process: function (v, row) {
                return (v == "NULL") ? "" : v;
            }
        },{
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
        }, {
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
        sortorder: "DESC"
    });
});