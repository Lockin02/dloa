var show_page = function () {
    $("#incomeGrid").yxsubgrid("reload");
};

$(function () {
    $("#incomeGrid").yxsubgrid({
        model: 'finance_income_income',
        action: 'pageJsonList',
        param: {"formType": "YFLX-DKD"},
        title: '�������',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        customCode: "incomeGrid",
        isOpButton: false,
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '���˵���',
            name: 'inFormNum',
            sortable: true,
            width: 110,
            hide: true
        }, {
            display: 'ϵͳ���ݺ�',
            name: 'incomeNo',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (row.id == 'noId') return v;
                return "<a href='#' onclick='showOpenWin(\"?model=finance_income_income&action=toAllot&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
        }, {
            display: '���λid',
            name: 'incomeUnitId',
            sortable: true,
            hide: true
        }, {
            display: '���λ',
            name: 'incomeUnitName',
            sortable: true,
            width: 130
        }, {
            display: '���λ����',
            name: 'incomeUnitType',
            sortable: true,
            datacode: 'KHLX',
            hide: true
        }, {
            display: '��ͬ��λid',
            name: 'contractUnitId',
            sortable: true,
            hide: true
        }, {
            display: '��ͬ��λ',
            name: 'contractUnitName',
            sortable: true,
            width: 130,
            hide: true
        }, {
            display: 'ʡ��',
            name: 'province',
            sortable: true,
            width: 70
        }, {
            display: '��������',
            name: 'incomeDate',
            sortable: true,
            width: 80
        }, {
            display: '��������',
            name: 'incomeType',
            datacode: 'DKFS',
            sortable: true,
            width: 60
        }, {
            display: '��������',
            name: 'sectionType',
            datacode: 'DKLX',
            sortable: true,
            width: 60
        }, {
            display: '������',
            name: 'incomeMoney',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        }, {
            name: 'businessBelongName',
            display: '������˾',
            sortable: true,
            width: 80
        }, {
            display: '��ǰ�ڼ����',
            name: 'isAdjust',
            sortable: true,
            width: 70,
            process: function (v) {
                if (v == 1) {
                    return '��';
                } else {
                    return '��';
                }
            }
        }, {
            display: '¼����',
            name: 'createName',
            sortable: true,
            width: 80
        }, {
            display: '״̬',
            name: 'status',
            datacode: 'DKZT',
            sortable: true,
            width: 80
        }, {
            display: '���ʼ�',
            name: 'isSended',
            sortable: true,
            width: 60,
            process: function (v) {
                if (v == 1) {
                    return '��';
                } else {
                    return '��';
                }
            }
        }, {
            display: '¼��ʱ��',
            name: 'createTime',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '��ע',
            name: 'remark',
            width: 120
        }],
        buttonsEx: [
            {
                name: 'view',
                text: "�߼���ѯ",
                icon: 'view',
                action: function () {
                    showThickboxWin("?model=finance_income_income&action=toSearch&"
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
                }
            }, {
                name: 'otherIncome',
                text: "��������",
                icon: 'add',
                action: function (row) {
                    showOpenWin("?model=finance_income_income&action=toAddOther");
                }
            }, {
                name: 'excelIn',
                text: "����",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=finance_income_income&action=toExcel"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
                }
            }, {
                name: 'excelOut',
                text: "����",
                icon: 'excel',
                action: function () {
                    var $thisGrid = $("#incomeGrid").data('yxsubgrid');
                    console.log($thisGrid.options.searchParam);
                    var url = "?model=finance_income_income&action=toExcOut"
                        + '&beginYearMonth=' + filterUndefined($thisGrid.options.extParam.beginYearMonth)
                        + '&endYearMonth=' + filterUndefined($thisGrid.options.extParam.endYearMonth)

                        + '&province=' + filterUndefined($thisGrid.options.extParam.province)

                        + '&incomeDate=' + filterUndefined($thisGrid.options.extParam.incomeDate)
                        + '&incomeMoney=' + filterUndefined($thisGrid.options.extParam.incomeMoney)

                        + '&incomeUnitId=' + filterUndefined($thisGrid.options.extParam.incomeUnitId)
                        + '&incomeUnitName=' + filterUndefined($thisGrid.options.extParam.incomeUnitName)

                        + '&contractUnitId=' + filterUndefined($thisGrid.options.extParam.contractUnitId)
                        + '&contractUnitName=' + filterUndefined($thisGrid.options.extParam.contractUnitName)

                        + '&objCode=' + filterUndefined($thisGrid.options.extParam.objCode)

                        + '&incomeUnitType=' + filterUndefined($thisGrid.options.extParam.incomeUnitType)
                    ;
                    var status = $("#status").val();
                    if (status != "") {
                        url += "&status=" + status;
                    }
                    var isSended = $("#isSended").val();
                    if (isSended != "") {
                        url += "&isSended=" + isSended;
                    }
                    for (var k in $thisGrid.options.searchParam) {
                        if ($thisGrid.options.searchParam[k] != "") {
                            url += "&" + k + "=" + $thisGrid.options.searchParam[k];
                        }
                    }
                    window.open(url,"", "width=200,height=200,top=200,left=200");
                }
            }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=finance_income_incomeAllot&action=pageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'incomeId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������
                }
            ],
            // ��ʾ����
            colModel: [{
                name: 'objType',
                display: 'Դ������',
                datacode: 'KPRK'
            }, {
                name: 'objCode',
                display: 'Դ�����',
                width: 180
            }, {
                name: 'areaName',
                display: '��������',
                width: 80
            }, {
                name: 'rObjCode',
                display: 'ҵ����',
                width: 150
            }, {
                name: 'money',
                display: '������',
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                name: 'allotDate',
                display: '��������'
            }]
        },
        toAddConfig: {
            toAddFn: function (p) {
                showOpenWin("?model=finance_income_income&action=toAdd&formType=YFLX-DKD");
            }
        },

        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�༭����',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.status != 'DKZT-YFP' && row.status != 'DKZT-BFFP') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row)
                    showOpenWin("?model=finance_income_income"
                        + "&action=init"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_']);
            }
        }, {
            text: '���䵽��',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.status != 'DKZT-FHK' && row.incomeUnitId != '0') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row)
                    showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_'], 1, 720, 1000, row.incomeNo);
            }
        }, {
            text: '�޸ı�ע',
            icon: 'edit',
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=finance_income_income&action=toEditRemark&id="
                        + row.id
                        + '&skey=' + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
                }
            }
        }, {
            text: '�鿴',
            icon: 'view',
            action: function (row, rows, grid) {
                if (row)
                    showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_']
                        + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500"
                        + "&width=900");
            }
        }, {
            text: '�����˿',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status != 'DKZT-WFP') {
                    return true;
                }
                return false;
            },
            action: function (row) {
                showOpenWin("?model=finance_income_income"
                    + "&action=addByPush"
                    + "&id="
                    + row.id + "&formType=YFLX-TKD"
                    + '&skey=' + row['skey_']);
            }
        }, {
            name: 'view',
            text: "������־",
            icon: 'view',
            action: function (row, rows, grid) {
                showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_finance_income"
                    + "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
            }
        }, {
            name: 'delete',
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.status == 'DKZT-WFP' || row.status == 'DKZT-FHK' || row.incomeMoney == 0) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_income_income&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    // grid.reload();
                                    alert('ɾ���ɹ���');
                                    show_page();
                                } else {
                                    alert('ɾ��ʧ�ܣ�');
                                    show_page();
                                }
                            }
                        });
                    }
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }],
        // ��������
        comboEx: [{
            text: '״̬',
            key: 'status',
            datacode: 'DKZT',
            value: 'DKZT-WFP'
        }, {
            text: '���ʼ�',
            key: 'isSended',
            data: [{
                value: 0,
                text: '��'
            }, {
                value: 1,
                text: '��'
            }]
        }],
        searchitems: [{
            display: '�ͻ�����',
            name: 'incomeUnitName'
        }, {
            display: '�ͻ�ʡ��',
            name: 'province'
        }, {
            display: 'ϵͳ���ݺ�',
            name: 'incomeNo'
        }, {
            display: '������',
            name: 'incomeMoney'
        }, {
            display: '���˵���',
            name: 'inFormNum'
        }, {
            display: '��������',
            name: 'incomeDateSearch'
        }],
        sortname: 'updateTime'
    });
});