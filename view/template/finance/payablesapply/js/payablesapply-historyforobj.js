var show_page = function() {
    $("#payablesapplyGrid").yxgrid("reload");
};

$(function() {
    var thisId = $("#thisId").val();

    var colModel = [{
        display: '�����',
        name: 'id',
        sortable: true,
        width: 60,
        process: function(v) {
            if (v != 'noId' && v != 'noId2') {
                return v;
            } else {
                return "";
            }
        }
    }, {
        name: 'formNo',
        display: '���뵥���',
        sortable: true,
        width: 130,
        process: function(v, row) {
            if (thisId != row.id) {
                return v;
            } else {
                return "<span style='color: blue' title='��ǰ���뵥'>" + v + "</span>";
            }
        }
    }, {
        name: 'formDate',
        display: '��������',
        sortable: true,
        hide: true,
        width: 70
    }];

    var objType = $("#objType").val();
    if (objType == 'YFRK-06') {
        colModel.push({
            name: 'period',
            display: '�����·�',
            sortable: true,
            width: 60
        });
    }

    // �������
    colModel.push({
        name: 'payDate',
        display: '������������',
        sortable: true,
        width: 70
    }, {
        name: 'actPayDate',
        display: 'ʵ�ʸ�������',
        sortable: true,
        width: 70
    }, {
        name: 'payFor',
        display: '��������',
        sortable: true,
        datacode: 'FKLX',
        width: 60
    }, {
        name: 'supplierName',
        display: '��Ӧ������',
        sortable: true,
        width: 160
    }, {
        name: 'payMoney',
        display: '������',
        sortable: true,
        process: function(v) {
            if (v >= 0) {
                return moneyFormat2(v);
            } else {
                return "<span class='red'>" + moneyFormat2(v) + "</span>";
            }
        },
        width: 70
    }, {
        name: 'payedMoney',
        display: '�Ѹ����',
        sortable: true,
        process: function(v) {
            if (v >= 0) {
                return moneyFormat2(v);
            } else {
                return "<span class='red'>" + moneyFormat2(v) + "</span>";
            }
        },
        width: 70
    }, {
        name: 'status',
        display: '״̬',
        sortable: true,
        datacode: 'FKSQD',
        width: 60
    }, {
        name: 'ExaStatus',
        display: '����״̬',
        sortable: true,
        width: 60
    }, {
        name: 'ExaDT',
        display: '����ʱ��',
        sortable: true,
        width: 80
    }, {
        name: 'deptName',
        display: '���벿��',
        sortable: true,
        hide: true,
        width: 80
    }, {
        name: 'salesman',
        display: '������',
        sortable: true,
        width: 80
    }, {
        name: 'createName',
        display: '������',
        sortable: true,
        hide: true,
        width: 80
    }, {
        name: 'createTime',
        display: '��������',
        sortable: true,
        width: 120,
        hide: true
    });

    $("#payablesapplyGrid").yxgrid({
        model: 'finance_payablesapply_payablesapply',
        action: 'historyJson',
        param: {dObjId: $("#objId").val(), dObjIds: $("#objIds").val(), dObjType: objType},
        title: '����������ʷ',
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        isOpButton: false,
        //����Ϣ
        colModel: colModel,
        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                showMenuFn: function(row) {
                    return row.id != 'noId' && row.id != 'noId2';
                },
                action: function(row) {
                    showThickboxWin('?model=finance_payablesapply_payablesapply&action=init&perm=view&id='
                    + row.id
                    + '&skey=' + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            }, {
                text: '�ύ����֧��',
                icon: 'edit',
                showMenuFn: function(row) {
                    return row.status == 'FKSQD-00' && row.ExaStatus == '���' && row.createId == $("#userId").val();
                },
                action: function(row) {
                    if (row.payDate == "") {
                        showThickboxWin('?model=finance_payablesapply_payablesapply&action=toConfirm&id='
                        + row.id
                        + '&supplierName=' + row['supplierName']
                        + '&payMoney=' + row['payMoney']
                        + '&skey=' + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
                    } else {
                        var thisDate = formatDate(new Date());
                        var s = DateDiff(thisDate, row.payDate);
                        // if (s > 0) {
                        //     alert('���������������ڻ��� ' + s + " �죬�ݲ����ύ����֧��");
                        // } else {
                            showThickboxWin('?model=finance_payablesapply_payablesapply&action=toConfirm&id='
                            + row.id
                            + '&supplierName=' + row['supplierName']
                            + '&payMoney=' + row['payMoney']
                            + '&skey=' + row['skey_']
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
                        // }
                    }
                }
            },{
                name: 'file',
                text: '�ϴ�����',
                icon: 'add',
                showMenuFn: function(row) {
                    if (row.status == 3 || row.createId != $("#userId").val()) {
                        return false;
                    }
                },
                action: function(row) {
                    showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id="
                        + row.id
                        + "&skey=" + row.skey_
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                }
            }, {
                text: '��ӡ',
                icon: 'print',
                showMenuFn: function(row) {
                    return row.status != 'FKSQD-04' && row.createId == $("#userId").val();
                },
                action: function(row) {
                    showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'], 1);
                }
            }, {
                text: '�ر�',
                icon: 'delete',
                showMenuFn: function(row) {
                    return row.ExaStatus == '���' && (row.status == 'FKSQD-01' || row.status == 'FKSQD-00') && row.createId == $("#userId").val();
                },
                action: function(row) {
                    showThickboxWin('?model=finance_payablesapply_payablesapply&action=toClose&id='
                        + row.id
                        + '&skey=' + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                }
            }
        ],
        event: {
            row_check: function(p1, p2, p3, row) {
                if (row.id != 'noId' && row.id != 'noId2') {
                    var allData = $("#payablesapplyGrid").yxgrid('getCheckedRows');

                    var payMoneyObj = $("#rownoId2 td[namex='payMoney'] div");
                    var payedMoneyObj = $("#rownoId2 td[namex='payedMoney'] div");

                    var payMoney = 0;
                    var payedMoney = 0;
                    if (allData.length > 0) {
                        for (var i = 0; i < allData.length; i++) {
                            payMoney = accAdd(payMoney, allData[i].payMoney, 2);
                            payedMoney = accAdd(payedMoney, allData[i].payedMoney, 2);
                        }
                    }
                    payMoneyObj.text(moneyFormat2(payMoney));
                    payedMoneyObj.text(moneyFormat2(payedMoney));
                }
            }
        },
        searchitems: [{
            display: 'id',
            name: 'id'
        }],
        sortname: 'updateTime'
    });
});