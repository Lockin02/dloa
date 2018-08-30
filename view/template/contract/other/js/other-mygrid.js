var show_page = function() {
    $("#otherGrid").yxgrid("reload");
};

$(function() {
    $("#otherGrid").yxgrid({
        model: 'contract_other_other',
        action: 'myOtherListPageJson',
        title: '�ҵ�������ͬ',
        isViewAction: false,
        isDelAction: false,
        customCode: 'otherGrid',
        isOpButton: false,
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
            process: function(v, row) {
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
            process: function(v, row) {
                if (row.status == 4) {
                    return "<a href='#' style='color:red' title='����еĺ�ͬ' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    if (row.ExaStatus == '���ύ' || row.ExaStatus == '��������') {
                        return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\",1," + row.id + ")'>" + v + "</a>";
                    } else {
                        return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\",1," + row.id + ")'>" + v + "</a>";
                    }
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
            width: 80
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
            name: 'projectTypeName',
            display: '��Ŀ����',
            sortable: true,
            hide: true
        }, {
            name: 'payForBusinessName',
            display: '����ҵ������',
            sortable: true,
            process: function (v, row) {
                return (v == "NULL")? "" : v;
            }
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
            process: function(v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'payApplyMoney',
            display: '���븶��',
            sortable: true,
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZA') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZA') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZA') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZA') {
                    return '--';
                } else {
                    if (row.id == undefined) return moneyFormat2(v);
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
            process: function(v) {
                if (v == 0) {
                    return "δ�ύ";
                } else if (v == 1) {
                    return "������";
                } else if (v == 2) {
                    return "ִ����";
                } else if (v == 3) {
                    return "�ѹر�";
                } else if (v == 4) {
                    return "�����";
                } else if (v == 5) {
                    return "�ر�������";
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
            process: function(v) {
                return v == "1" ? "��ǩ��" : "δǩ��";
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
            process: function(v) {
                return v == 1 ? "��" : "��";
            }
        }, {
            name: 'isStamp',
            display: '�Ƿ��Ѹ���',
            sortable: true,
            width: 60,
            process: function(v) {
                return v == 1 ? "��" : "��";
            }
        }, {
            name: 'stampType',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'createName',
            display: '������',
            sortable: true,
            hide: true
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
        },{
            name: 'chanceCode',
            display: '�̻����',
            sortable: true,
            width: 120
        },{
            name: 'prefBidDate',
            display: 'Ԥ��Ͷ��ʱ��',
            sortable: true,
            width: 120
        },{
            name: 'contractCode',
            display: '���ۺ�ͬ���',
            sortable: true,
            width: 120
        },{
            name: 'projectPrefEndDate',
            display: '��ĿԤ�ƽ���ʱ��',
            sortable: true,
            width: 120
        },{
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
        },{
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
            formHeight: 500,
            toAddFn: function() {
                showModalWin("?model=contract_other_other&action=toAdd&open=1");
            }
        },
        toEditConfig: {
            formWidth: 1000,
            formHeight: 500,
            showMenuFn: function(row) {
                return row.ExaStatus == "���ύ" || row.ExaStatus == "���";
            },
            toEditFn: function(p, g) {
                var c = p.toEditConfig;
                var rowObj = g.getSelectedRow();
                if (rowObj) {
                    var rowData = rowObj.data('data');
                    var keyUrl = "";
                    if (rowData['skey_']) {
                        keyUrl = "&skey=" + rowData['skey_'];
                    }
                    showModalWin("?model="
                    + p.model
                    + "&action="
                    + c.action
                    + c.plusUrl
                    + "&id="
                    + rowData[p.keyField]
                    + keyUrl, 1, rowData.id);
                } else {
                    alert('��ѡ��һ�м�¼��');
                }
            }
        },
        // ��չ�Ҽ��˵�
        buttonsEx: [{
            text: '��������',
            icon: 'add',
            action: function(row, rows) {
                if (row) {
                    var signCompanyName = "";
                    var businessBelongName = "";
                    var idArr = [];
                    for (var i = 0; i < rows.length; i++) {
                        //ǩԼ��˾����
                        if (i != 0 && (signCompanyName != rows[i].signCompanyName || businessBelongName != rows[i].businessBelongName)) {
                            alert('ǩԼ��˾���ƻ��߹�����˾���Ʋ�һ�£���������д˲���');
                            return false;
                        } else {
                            signCompanyName = rows[i].signCompanyName;
                            businessBelongName = rows[i].businessBelongName;
                        }

                        //��ͬ���Ϳ���
                        if (rows[i].fundType != "KXXZB") {
                            alert('�Ǹ������ͬ���ܽ��д˲���');
                            return false;
                        }

                        //״̬����
                        if (rows[i].status != '2') {
                            alert('��ִ����״̬�ĺ�ͬ���ܽ��д˲���');
                            return false
                        }

                        //��ͬ����������
                        if (rows[i].orderMoney * 1 > rows[i].payApplyMoney) {
                            idArr.push(rows[i].id);
                        }
                    }
                    if (idArr.length == 0) {
                        alert('û���ܹ�����ĺ�ͬ');
                    } else {
                        var ids = idArr.toString();
                        showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + ids, 1, 'batch');
                    }
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }],
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�鿴��ͬ',
            icon: 'view',
            action: function(row) {
                if (row) {
                    if (row.ExaStatus == '���ύ' || row.ExaStatus == '��������') {
                        if (row.ExaStatus == '��������' && row.closeReason != '') {
                            showModalWin("?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_, 1, row.id);
                        } else {
                            showModalWin("?model=contract_other_other&action=viewAlong&id=" + row.id + '&skey=' + row.skey_, 1, row.id);
                        }
                    } else {
                        showModalWin("?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_, 1, row.id);
                    }
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '�ύ����',
            icon: 'add',
            showMenuFn: function(row) {
                return row.ExaStatus == "���ύ" || row.ExaStatus == "���";
            },
            action: function(row) {
                if (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=contract_otherpayapply_otherpayapply&action=getFeeDeptId",
                        data: {"contractId": row.id, 'contractType': 'oa_sale_other'},
                        success: function(data) {
                            if (data != '0') {
                                showThickboxWin('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId='
                                + row.id
                                + "&flowMoney=" + row.orderMoney
                                + "&billDept=" + data
                                + "&billCompany=" + row.businessBelong
                                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            } else {
                                showThickboxWin('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId='
                                + row.id
                                + "&flowMoney=" + row.orderMoney
                                + "&billDept=" + row.deptId
                                + "&billCompany=" + row.businessBelong
                                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            }
                        }
                    });
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '��������',
            icon: 'add',
            showMenuFn: function(row) {
                return row.ExaStatus == "��������";
            },
            action: function(row) {
                if (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=common_workflow_workflow&action=isAuditedContract",
                        data: {
                            billId: row.id,
                            examCode: 'oa_sale_other'
                        },
                        success: function(msg) {
                            if (msg == '1') {
                                alert('����ʧ�ܣ�ԭ��\n1.�ѳ�������,�����ظ�����\n2.�����Ѿ�����������Ϣ�����ܳ�������');
                                return false;
                            } else {
                                var url;
                                switch (msg) {
                                    case '������ͬ����' :
                                        url = 'controller/contract/other/ewf_index.php?actTo=delWork';
                                        break;
                                    case '������ͬ���������' :
                                        url = 'controller/contract/other/ewf_forpayapply.php?actTo=delWork';
                                        break;
                                    case '������ͬ�ر�����' :
                                        url = 'controller/contract/other/ewf_close.php?actTo=delWork';
                                        break;
                                    default :
                                }
                                if (url) {
                                    $.ajax({
                                        type: "GET",
                                        url: url,
                                        data: {"billId": row.id},
                                        async: false,
                                        success: function(data) {
                                            alert(data);
                                            show_page();
                                        }
                                    });
                                }
                            }
                        }
                    });
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '¼�벻��Ʊ���',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZA';
            },
            action: function(row) {
                showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
                + row.id
                + '&objCode='
                + row.orderCode
                + '&objType=KPRK-09'
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
            }
        }, {
            text: '���븶��',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function(row) {
                if (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=contract_other_other&action=canPayapply",
                        data: {"id": row.id},
                        async: false,
                        success: function(data) {
                            if (data == 'hasBack') {
                                alert('��ͬ����δ������ɵ��˿���������븶��');
                                return false;
                            } else { // ������Լ�������
                                showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + row.id, 1, row.id);
                            }
                        }
                    });
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '¼�뷢Ʊ',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
                return row.ExaStatus == "���" && row.fundType == 'KXXZB';
            },
            action: function(row) {
                if (row.orderMoney * 1 <= accAdd(row.invotherMoney, row.returnMoney, 2) * 1) {
                    alert('��ͬ��¼�뷢Ʊ������');
                    return false;
                }
                showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD02&objId=" + row.id, 1, row.id);
            }
        // }, {
        //     name: 'stamp',
        //     text: '�������',
        //     icon: 'add',
        //     showMenuFn: function(row) {
        //         if (row.status == 3) {
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
        //     action: function(row) {
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
            name: 'file',
            text: '�ϴ�����',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
            },
            action: function(row) {
                showThickboxWin("?model=contract_other_other&action=toUploadFile&id="
                + row.id
                + "&skey=" + row.skey_
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
            }
        }, {
            name: 'change',
            text: '�����ͬ',
            icon: 'edit',
            showMenuFn: function(row) {
                return row.status == 2 && row.ExaStatus == '���';
            },
            action: function(row) {
                showModalWin("?model=contract_other_other&action=toChange&id="
                + row.id
                + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            name: 'copy',
            text: '���ƺ�ͬ',
            icon: 'add',
//            showMenuFn: function(row) {
//                return row.status == 2 && row.ExaStatus == '���';
//            },
            action: function(row) {
                showModalWin("?model=contract_other_other&action=toCopyAdd&id="
                    + row.id
                    + "&skey=" + row.skey_, 1, row.id);
            }
        },  {
            name: 'change',
            text: '�޸ķ�̯��ϸ',
            icon: 'edit',
            showMenuFn: function(row) {
                return row.ExaStatus == "���" && (row.fundType == 'KXXZB' || row.fundType == 'KXXZC')
                    && row.status == '2';
            },
            action: function(row) {
				showModalWin("?model=contract_other_other&action=toChangeCostShare&id="
                + row.id
                + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            text: '�����˿�',
            icon: 'delete',
            showMenuFn: function(row) {
                return row.ExaStatus == "���" && row.fundType == 'KXXZB' && row.status == '2';
            },
            action: function(row) {
                if (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=contract_other_other&action=canPayapplyBack",
                        data: {"id": row.id},
                        async: false,
                        success: function(data) {
                            if (data == 'hasBack') {
                                alert('��ͬ����δ������ɵĸ������룬���������˿�');
                                return false;
                            } else if (data * 1 == '0') {
                                alert('��ͬ���Ѹ�������������˿�');
                                return false;
                            } else if (data * 1 == -1) {
                                alert('��ͬ�˿����������������ܼ�������');
                                return false;
                            } else {
                                showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&payFor=FKLX-03&objType=YFRK-02&objId=" + row.id, 1, row.id);
                            }
                        }
                    });
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: '�رպ�ͬ',
            icon: 'delete',
            showMenuFn: function(row) {
                return (row.ExaStatus == "���" || row.ExaStatus == "���") && row.status == "2";
            },
            action: function(row) {
                if (row) {
                    showThickboxWin("?model=contract_other_other&action=toClose&id="
                    + row.id
                    + "&skey=" + row.skey_
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function(row) {
                return row.ExaStatus == "���ύ" || row.ExaStatus == "���";
            },
            action: function(rowData, rows, rowIds, g) {
                g.options.toDelConfig.toDelFn(g.options, g);
            }
        }],
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
        },{
            text: '��ͬ״̬',
            key: 'status',
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
            }, {
                text: '�ر�������',
                value: '5'
            }]
        }]
    });
});