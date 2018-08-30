var show_page = function () {
    $("#costshareGrid").yxgrid("reload");
};

$(function () {
    // ��ȡ������Ϣ
    var periodArr = [];
    var periodDefault = '';
    $.ajax({
        type: "POST",
        url: "?model=finance_period_period&action=getAllPeriod",
        data: {effectiveCost: 1},
        async: false,
        success: function (data) {
            periodArr = eval("(" + data + ")");
            if (periodArr.length > 0) {
                var newPeriod = [];
                for (var i = 0; i < periodArr.length; i++) {
                    newPeriod.push({
                        value: periodArr[i].text,
                        text: periodArr[i].text
                    });
                }
                periodArr = newPeriod;
                periodDefault = periodArr[0].value;
            }
        }
    });

    $("#costshareGrid").yxgrid({
        model: 'finance_cost_costshare',
        action : 'statistictPageJson',
        param: {
        	detailTypeArr: '4,5',
        	auditStatus: '1', 
        	costTypeNameNo: 'Ͷ�걣֤��',
        	shareObjTypeNo: 'FTDXLX-05',
			feeManId: $('#userId').val(),
			salesAreaId: $('#areaId').val(),
			auditDateYear: $('#year').val()
        },
        title: '��̯��ϸ�б�',
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        customCode: 'costShareGrid',
        showcheckbox : false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'auditStatus',
            display: '���',
            width: 25,
            align: 'center',
            process: function (v, row) {
                switch (v) {
                    case '1' :
                        return '<img title="�����[' + row.auditor + ']\n�������[' + row.auditDate
                            + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    case '3' :
                        return '<img title="����" src="images/icon/ok1.png" style="width:15px;height:15px;">';
                    default :
                }
            }
        }, {
            name: 'moduleName',
            display: '�������',
            sortable: true,
            width: 60
        }, {
            name: 'companyName',
            display: '��˾����',
            sortable: true,
            width: 60
        }, {
            name: 'belongCompanyName',
            display: '������˾',
            sortable: true,
            width: 60
        }, {
            name: 'objId',
            display: 'Դ��id',
            sortable: true,
            hide: true
        }, {
            name: 'objType',
            display: 'Դ������',
            sortable: true,
            width: 60,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '�⳥��';
                    case '2' :
                        return '������ͬ';
                    default :
                        return v;
                }
            }
        }, {
            name: 'objCode',
            display: 'Դ�����',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (v != "") {
                    return "<a href='javascript:void(0)' onclick='viewInfo(\"" + row.objId + "\",\"" + row.objType + "\")'>" + v + "</a>";
                }
            }
        }, {
            name: 'supplierName',
            display: '��Ӧ��',
            sortable: true,
            width: 120
        }, {
            name: 'feeMan',
            display: '���óе���',
            sortable: true,
            width: 80
        }, {
            name: 'salesArea',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'inPeriod',
            display: '�����ڼ�',
            sortable: true,
            width: 60
        }, {
            name: 'belongPeriod',
            display: '�����ڼ�',
            sortable: true,
            width: 60
        }, {
            name: 'detailType',
            display: 'ҵ������',
            sortable: true,
            width: 80,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '���ŷ���';
                    case '2' :
                        return '��ͬ��Ŀ����';
                    case '3' :
                        return '�з���Ŀ����';
                    case '4' :
                        return '��ǰ����';
                    case '5' :
                        return '�ۺ����';
                    default :
                        return v;
                }
            }
        }, {
            name: 'headDeptName',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'belongDeptName',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'chanceCode',
            display: '�̻����',
            sortable: true
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 150
        }, {
            name: 'customerType',
            display: '�ͻ�����',
            sortable: true
        }, {
            name: 'province',
            display: '����ʡ��',
            sortable: true,
            width: 70
        }, {
            name: 'parentTypeId',
            display: 'parentTypeId',
            hide: true
        }, {
            name: 'parentTypeName',
            display: '������ϸ�ϼ�',
            hide: true
        }, {
            name: 'costTypeId',
            display: 'costTypeId',
            hide: true
        }, {
            name: 'costTypeName',
            display: '������ϸ',
            width: 100
        }, {
            name: 'costMoney',
            display: '��̯���',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true
        }, {
            name: 'hookMoney',
            display: '�ۼƹ������',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true
        }, {
            name: 'thisMonthHookMoney',
            display: '���¹������',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true
        }, {
            name: 'unHookMoney',
            display: 'δ�������',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true
        }, {
            name: 'hookStatus',
            display: '����״̬',
            process: function (v) {
                switch (v) {
                    case '0' :
                        return 'δ����';
                    case '1' :
                        return '�ѹ���';
                    case '2' :
                        return '���ֹ���';
                    default :
                        return v;
                }
            },
            width: 60
        }],
//        menusEx: [{
//            text: '������¼',
//            icon: 'edit',
//            showMenuFn: function (row) {
//                return row.hookStatus != '0';
//            },
//            action: function (row) {
//                showOpenWin("?model=finance_cost_costHook&hookId="
//                + row.id, 1, 700, 1100, row.id);
//            }
//        }],
//        buttonsEx: [{
//            text: "�������",
//            icon: "edit",
//            action: function (row, rows) {
//                if (row) {
//                    var canAuditArr = [];
//
//                    for (var i = 0; i < rows.length; i++) {
//                        if (checkPeriod(rows[i].inPeriod) == false) {
//                            alert('����С�ڵ�ǰ�������ڵ����ݣ����ܽ�����˲���');
//                            return false;
//                        }
//
//                        if (rows[i].auditStatus == '2') {
//                            canAuditArr.push(rows[i].id);
//                        }
//                    }
//
//                    if (canAuditArr.length > 0) {
//                        if (confirm('ȷ�Ͻ�ѡ�еļ�¼�����')) {
//                            $.ajax({
//                                url: "?model=finance_cost_costshare&action=quickAudit",
//                                data: {ids: canAuditArr.toString()},
//                                type: 'POST',
//                                success: function (data) {
//                                    if (data == "1") {
//                                        alert('��˳ɹ�');
//                                        show_page();
//                                    } else {
//                                        alert('���ʧ��');
//                                    }
//                                }
//                            });
//                        }
//                    } else {
//                        alert('ѡ�м�¼��û�п���˵ļ�¼');
//                    }
//                } else {
//                    alert('��ѡ��һ����¼');
//                }
//            }
//        }, {
//            text: "���",
//            icon: "edit",
//            action: function (row, rows) {
//                if (row) {
//                    for (var i = 0; i < rows.length; i++) {
//                        if (rows[i].auditStatus == "2") {
//                            showOpenWin("?model=finance_cost_costshare&action=toAudit&objId=" +
//                                rows[i].objId +
//                                "&objType=" +
//                                rows[i].objType +
//                                "&objCode=" +
//                                rows[i].objCode +
//                                "&company=" +
//                                rows[i].company +
//                                "&companyName=" +
//                                rows[i].companyName +
//                                "&supplierName=" +
//                                rows[i].supplierName,
//                                1, 700, 1100, '��˷�̯��¼');
//                            return false;
//                        }
//                    }
//                }
//                // ����Ƿ�����Ҫ��˵ķ�̯��¼
//                $.ajax({
//                    url: "?model=finance_cost_costshare&action=getWaitInfo",
//                    type: "POST",
//                    success: function (data) {
//                        if (data == "0") {
//                            alert('û����Ҫ��˵ĵ���');
//                        } else {
//                            data = eval("(" + data + ")");
//                            showOpenWin("?model=finance_cost_costshare&action=toAudit&objId=" +
//                                data.objId +
//                                "&objType=" +
//                                data.objType +
//                                "&objCode=" +
//                                data.objCode +
//                                "&company=" +
//                                data.company +
//                                "&companyName=" +
//                                data.companyName +
//                                "&supplierName=" +
//                                data.supplierName,
//                                1, 700, 1100, '��˷�̯��¼');
//                        }
//                    }
//                });
//            }
//        }, {
//            text: "�����",
//            icon: "delete",
//            action: function (row, rows) {
//                if (row) {
//                    var canAuditArr = [];
//                    for (var i = 0; i < rows.length; i++) {
//                        if (rows[i].hookStatus != 0) {
//                            alert('���ܶ��ѽ��й���������������������˲���');
//                            return false;
//                        }
//
//                        if (checkPeriod(rows[i].inPeriod) == false) {
//                            alert('����С�ڵ�ǰ�������ڵ����ݣ����ܽ��з���˲���');
//                            return false;
//                        }
//
//                        if (rows[i].auditStatus == '1') {
//                            canAuditArr.push(rows[i].id);
//                        }
//                    }
//
//                    if (canAuditArr.length > 0) {
//                        if (confirm('ȷ�Ͻ�ѡ�еļ�¼�������')) {
//                            $.ajax({
//                                url: "?model=finance_cost_costshare&action=unAudit",
//                                data: {ids: canAuditArr.toString()},
//                                type: 'POST',
//                                success: function (data) {
//                                    if (data == "1") {
//                                        alert('����˳ɹ�');
//                                        show_page();
//                                    } else {
//                                        alert('�����ʧ��');
//                                    }
//                                }
//                            });
//                        }
//                    } else {
//                        alert('ѡ�м�¼��û�пɷ���˵ļ�¼');
//                    }
//                } else {
//                    alert('��ѡ��һ����¼');
//                }
//            }
//        }, {
//            text: "����",
//            icon: "delete",
//            action: function (row, rows) {
//                if (row) {
//                    var canBackArr = [];
//                    for (var i = 0; i < rows.length; i++) {
//                        if (rows[i].hookStatus != '0') {
//                            alert('���ܶ��ѽ��й���������������������˲���');
//                            return false;
//                        }
//
//                        if (checkPeriod(rows[i].inPeriod) == false) {
//                            alert('����С�ڵ�ǰ�������ڵ����ݣ����ܽ��г��ز���');
//                            return false;
//                        }
//
//                        if (rows[i].auditStatus == '2') {
//                            canBackArr.push(rows[i].id);
//                        }
//                    }
//
//                    if (canBackArr.length > 0) {
//                        if (confirm('ȷ�Ͻ�ѡ�еļ�¼������')) {
//                            $.ajax({
//                                url: "?model=finance_cost_costshare&action=quickBack",
//                                data: {ids: canBackArr.toString()},
//                                type: 'POST',
//                                success: function (data) {
//                                    if (data == "1") {
//                                        alert('���سɹ�');
//                                        show_page();
//                                    } else {
//                                        alert('����ʧ��');
//                                    }
//                                }
//                            });
//                        }
//                    } else {
//                        alert('ѡ�м�¼��û�пɳ��صļ�¼');
//                    }
//                } else {
//                    alert('��ѡ��һ����¼');
//                }
//            }
//        }, {
//            text: "����",
//            icon: 'excel',
//            action: function () {
//                window.open(
//                    '?model=finance_cost_costshare&action=exportExcel&periodNo=' + $("#periodNo").val() +
//                    '&auditStatusNo=0&auditStatusArr=' + $("#auditStatusArr").val() +
//                    '&hookStatusArr=' + $("#hookStatusArr").val() +
//                    '&inPeriod=' + $("#inPeriod").val()
//                    ,
//                    '������Ʊ����',
//                    'width=200,height=200,top=200,left=200'
//                );
//            }
//        }],
        //��������
        comboEx: [
//        {
//            text: '���',
//            key: 'auditStatusArr',
//            value: '1,2',
//            data: [{
//                text: 'δ���',
//                value: '2'
//            }, {
//                text: '�����',
//                value: '1'
//            }, {
//                text: '����',
//                value: '3'
//            }, {
//                text: 'δ������',
//                value: '1,2'
//            }]
//        }, {
//            text: '����',
//            key: 'hookStatusArr',
//            value: '0,2',
//            data: [{
//                text: 'δ����',
//                value: '0'
//            }, {
//                text: '�ѹ���',
//                value: '1'
//            }, {
//                text: '���ֹ���',
//                value: '2'
//            }, {
//                text: 'δ���',
//                value: '0,2'
//            }]
//        }, {
//            text: '������',
//            key: 'periodNo',
//            value: periodDefault,
//            data: periodArr
//        }, 
        {
            text: '�����ڼ�',
            key: 'inPeriod',
            data: periodArr
        }],
        searchitems: [{
            display: "Դ�����",
            name: 'objCodeSearch'
        }, {
            display: "�̻����",
            name: 'chanceCodeSearch'
        }, {
            display: "��Ŀ���",
            name: 'projectCodeSearch'
        }, {
            display: "��ͬ���",
            name: 'contractCodeSearch'
        }],
        sortorder: 'objId'
    });
});

// ���ݲ鿴
function viewInfo(objId, objType) {
    switch (objType) {
        case "1" :
            showModalWin("?model=finance_compensate_compensate&action=toView&id=" + objId, 1);
            break;
        case "2" :
            $.ajax({
                type: "POST",
                url: "?model=contract_other_other&action=md5RowAjax",
                data: {"id": objId},
                async: false,
                success: function (data) {
                    showModalWin("?model=contract_other_other&action=viewTab&id=" + objId + "&skey=" + data, 1);
                }
            });
            break;
        default :
            return false;
    }
}

// ��֤��������
var periodYear;
var periodMonth;
function checkPeriod(period) {
    var periodArr = period.split('.');
    var thisPeriodYear = periodArr[0];
    var thisPeriodMonth = periodArr[1];

    // ���δ����������ڣ��Ȼ�ȡ
    if (!periodYear) {
        $.ajax({
            type: "POST",
            url: "?model=finance_period_period&action=getNowPeriod",
            data : {type : 'cost'},
            async: false,
            success: function (data) {
                data = eval("(" + data + ")");
                periodYear = data.thisYear;
                periodMonth = data.thisMonth;
            }
        });
    }

    return periodYear * 1 < thisPeriodYear * 1 ||
        (periodYear * 1 == thisPeriodYear * 1 && periodMonth * 1 <= thisPeriodMonth * 1);
}