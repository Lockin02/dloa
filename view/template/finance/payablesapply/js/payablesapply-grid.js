var show_page = function () {
    $("#payablesapplyGrid").yxgrid("reload");
};

$(function () {
    //��ʼ����ͷ��ť
    var buttonsArr = [
        {
            text: "������ӡ",
            icon: 'print',
            action: function (row, rows, idArr) {
                if (row) {
                    var idArrCatch = [];
                    for (var i = 0; i < rows.length; i++) {
                        if (rows[i].ExaStatus !== '���') {
                            alert('���� [' + rows[i].id + '] ����δ��ɣ����ܽ���������ӡ�����������ӡ����ѡ�񵥾ݺ󣬵��������ӡ����');
                            return false;
                        }

                        if (rows[i].status != 'FKSQD-01') {
                            alert('���� [' + rows[i].id + '] ����δ����״̬�����ܽ���������ӡ�����������ӡ����ѡ�񵥾ݺ󣬵��������ӡ����');
                            return false;
                        }

                        if($.inArray( rows[i].id , idArr) >= 0){
                            idArrCatch.push(rows[i].id);
                        }
                    }

                    showModalWin("?model=finance_payablesapply_payablesapply&action=toBatchPrint&id=" + idArrCatch.toString());
                } else {
                    showModalWin("?model=finance_payablesapply_payablesapply&action=toBatchPrint");
                }
            }
        },
        {
            text: '������ӡ',
            icon: 'print',
            action: function (row, rows, idArr) {
                if (row) {
                    var idStr = idArr.toString();
                    showModalWin("?model=finance_payablesapply_payablesapply&action=toBatchPrintAlong&id=" + idStr, 1);
                } else {
                    alert('��ѡ��һ�ŵ��ݴ�ӡ');
                }
            }
        },
        {
            text: "ȷ�ϸ���",
            icon: 'add',
            action: function (row, rows, idArr) {
                if (row) {
                    if (confirm('ȷ�Ͻ��д˲���ô��')) {
                        for (var i = 0; i < rows.length; i++) {
                            if (rows[i].ExaStatus !== '���') {
                                alert('���� [' + rows[i].id + '] ����δ��ɣ����ܽ���ȷ�ϸ������');
                                return false;
                            }

                            if (rows[i].status != 'FKSQD-01') {
                                alert('���� [' + rows[i].id + '] ����δ����״̬�����ܽ���ȷ�ϸ������');
                                return false;
                            }
                        }
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_payables_payables&action=addInGroupOneKey",
                            data: {
                                ids: idArr.toString()
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('¼��ɹ���');
                                    show_page(1);
                                } else {
                                    alert('¼��ʧ��!');
                                }
                            }
                        });
                    }
                } else {
                    alert('����ѡ������һ����¼');
                }
            }
        },
        {
            text: '¼�븶��',
            icon: 'add',
            action: function (row, rows) {
                if (row) {
                    if (rows.length != 1) {
                        alert('�˹���ֻ��һ��ѡ��һ�ŵ���');
                        return false;
                    }
                    if (row.ExaStatus == '���' && row.status == 'FKSQD-01') {
                        if (row.payFor == 'FKLX-01') {
                            showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
                                + row.id
                                + '&formType=CWYF-01');
                        } else if (row.payFor == 'FKLX-02') {
                            showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
                                + row.id
                                + '&formType=CWYF-02');
                        } else {
                            showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
                                + row.id
                                + '&formType=CWYF-03');
                        }
                    } else {
                        alert('�����Ѹ������δ���ϸ������������ܽ��д˲���');
                        return false;
                    }
                } else {
                    alert('��ѡ��һ�ŵ���');
                }
            }
        },
        {
            name: 'view',
            text: "�߼���ѯ",
            icon: 'view',
            action: function () {
                showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
            }
        },
        {
            name: 'file',
            text: "�����ϴ�",
            icon: 'edit',
            action: function (row, rows) {
                if (row) {
                    if (rows.length != 1) {
                        alert('�˹���ֻ��һ��ѡ��һ�ŵ���');
                        return false;
                    }
                    showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id=" + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
                } else {
                    alert('��ѡ��һ�ŵ���');
                }
            }
        },
        {
            name: 'excOut',
            text: "����",
            icon: 'excel',
            items: [
                {
                    text: '������Ϣ',
                    icon: 'excel',
                    action: function () {
                        var $thisGrid = $("#payablesapplyGrid").data('yxgrid');
                        var url = "?model=finance_payablesapply_payablesapply&action=excelOut"
                                + '&ExaStatus=' + filterUndefined($thisGrid.options.param.ExaStatus)
                                + '&status=' + filterUndefined($thisGrid.options.param.status)

                                + '&supplierName=' + filterUndefined($thisGrid.options.extParam.supplierName)

                                + '&salesman=' + filterUndefined($thisGrid.options.extParam.salesman)
                                + '&salesmanId=' + filterUndefined($thisGrid.options.extParam.salesmanId)

                                + '&deptName=' + filterUndefined($thisGrid.options.extParam.deptName)
                                + '&deptId=' + filterUndefined($thisGrid.options.extParam.deptId)

                                + '&feeDeptName=' + filterUndefined($thisGrid.options.extParam.feeDeptName)
                                + '&feeDeptId=' + filterUndefined($thisGrid.options.extParam.feeDeptId)

                                + '&sourceType=' + filterUndefined($thisGrid.options.extParam.sourceType)
                                + '&payForArr=FKLX-01,FKLX-02'
                                + '&isEntrust=0'
                            ;
                        if ($thisGrid.options.extParam.formDateBegin != undefined) {
                            url += '&formDateBegin=' + filterUndefined($thisGrid.options.extParam.formDateBegin);
                        }
                        if ($thisGrid.options.extParam.formDateEnd != undefined) {
                            url += '&formDateEnd=' + filterUndefined($thisGrid.options.extParam.formDateEnd);
                        }
                        window.open(url, "", "width=200,height=200,top=200,left=200");
                    }
                },
                {
                    text: '������ϸ',
                    icon: 'excel',
                    action: function () {
                        var $thisGrid = $("#payablesapplyGrid").data('yxgrid');
                        var url = "?model=finance_payablesapply_payablesapply&action=excelDetail&outType=05"
                                + '&ExaStatus=' + filterUndefined($thisGrid.options.param.ExaStatus)
                                + '&status=' + filterUndefined($thisGrid.options.param.status)

                                + '&supplierName=' + filterUndefined($thisGrid.options.extParam.supplierName)

                                + '&salesman=' + filterUndefined($thisGrid.options.extParam.salesman)
                                + '&salesmanId=' + filterUndefined($thisGrid.options.extParam.salesmanId)

                                + '&deptName=' + filterUndefined($thisGrid.options.extParam.deptName)
                                + '&deptId=' + filterUndefined($thisGrid.options.extParam.deptId)

                                + '&feeDeptName=' + filterUndefined($thisGrid.options.extParam.feeDeptName)
                                + '&feeDeptId=' + filterUndefined($thisGrid.options.extParam.feeDeptId)

                                + '&sourceType=' + filterUndefined($thisGrid.options.extParam.sourceType)
                                + '&payForArr=FKLX-01,FKLX-02'
                                + '&isEntrust=0'
                            ;
                        if ($thisGrid.options.extParam.formDateBegin != undefined) {
                            url += '&formDateBegin=' + filterUndefined($thisGrid.options.extParam.formDateBegin);
                        }
                        if ($thisGrid.options.extParam.formDateEnd != undefined) {
                            url += '&formDateEnd=' + filterUndefined($thisGrid.options.extParam.formDateEnd);
                        }
                        window.open(url, "", "width=200,height=200,top=200,left=200");
                    }
                },
                {
                    text: '������ϸ(07)',
                    icon: 'excel',
                    action: function () {
                        var $thisGrid = $("#payablesapplyGrid").data('yxgrid');
                        var url = "?model=finance_payablesapply_payablesapply&action=excelDetail"
                                + '&ExaStatus=' + filterUndefined($thisGrid.options.param.ExaStatus)
                                + '&status=' + filterUndefined($thisGrid.options.param.status)

                                + '&supplierName=' + filterUndefined($thisGrid.options.extParam.supplierName)

                                + '&salesman=' + filterUndefined($thisGrid.options.extParam.salesman)
                                + '&salesmanId=' + filterUndefined($thisGrid.options.extParam.salesmanId)

                                + '&deptName=' + filterUndefined($thisGrid.options.extParam.deptName)
                                + '&deptId=' + filterUndefined($thisGrid.options.extParam.deptId)

                                + '&feeDeptName=' + filterUndefined($thisGrid.options.extParam.feeDeptName)
                                + '&feeDeptId=' + filterUndefined($thisGrid.options.extParam.feeDeptId)

                                + '&sourceType=' + filterUndefined($thisGrid.options.extParam.sourceType)
                                + '&payForArr=FKLX-01,FKLX-02'
                                + '&isEntrust=0'
                            ;
                        if ($thisGrid.options.extParam.formDateBegin != undefined) {
                            url += '&formDateBegin=' + filterUndefined($thisGrid.options.extParam.formDateBegin);
                        }
                        if ($thisGrid.options.extParam.formDateEnd != undefined) {
                            url += '&formDateEnd=' + filterUndefined($thisGrid.options.extParam.formDateEnd);
                        }
                        window.open(url, "", "width=200,height=200,top=200,left=200");
                    }
                }
            ]
        }
    ];
    //�����رհ�ť
    var batchClose = {
        text: "�����ر�",
        icon: 'delete',
        action: function (row, rows, idArr) {
            if (row) {
                for (var i = 0; i < rows.length; i++) {
                    if (rows[i].ExaStatus != '���') {
                        alert('���� [' + rows[i].id + '] ����δ��ɣ����ܽ��йرղ���');
                        return false;
                    }
                    if (rows[i].status != 'FKSQD-00' && rows[i].status != 'FKSQD-01') {
                        alert('���� [' + rows[i].id + '] ����δ�ύ֧��/δ����״̬�����ܽ��йرղ���');
                        return false;
                    }
                }
                showThickboxWin('?model=finance_payablesapply_payablesapply&action=toBatchClose&id='
                    + idArr.toString()
                    + '&skey=' + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
            } else {
                alert('��ѡ��һ�ŵ���');
            }
        }
    };
    //�رո�������Ȩ��
    $.ajax({
        type: 'POST',
        url: '?model=finance_payablesapply_payablesapply&action=getLimits',
        data: {
            'limitName': '�رո�������Ȩ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(batchClose);
            }
        }
    });
    $("#payablesapplyGrid").yxgrid({
        model: 'finance_payablesapply_payablesapply',
        title: '��������',
        action: 'pageJsonList',
        param: {payForArr: 'FKLX-01,FKLX-02', isEntrust: '0'},
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        customCode: 'payablesapplyGrid',
        noCheckIdValue: 'noId',
        isRightMenu: false,
        pageSize: 40, // ÿҳĬ�ϵĽ����
        //����Ϣ
        colModel: [
            {
                display: '��ӡ',
                name: 'printId',
                width: 30,
                align: 'center',
                sortable: false,
                process: function (v, row) {
                    if (row.id == 'noId') return '';
                    if (row.printCount > 0) {
                        return '<img src="images/icon/print.gif" title="��ӡ����Ϊ:' + row.printCount + ',���һ�δ�ӡʱ��:' + row.lastPrintTime + '"/>';
                    } else {
                        return '<img src="images/icon/print1.gif" title="δ��ӡ���ĵ���"/>';
                    }
                }
            },
            {
                display: '�����',
                name: 'id',
                width: 60,
                sortable: true,
                process: function (v, row) {
                    if (row.id == 'noId') {
                        return v;
                    }
                    if (row.payFor == 'FKLX-03') {
                        if (row.sourceType != '') {
                            return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        }
                    } else {
                        if (row.sourceType != '') {
                            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=finance_payablesapply_payablesapply&action=toViewSimple&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000\")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        }
                    }
                }
            },
            {
                name: 'formNo',
                display: '���뵥���',
                sortable: true,
                width: 140,
                hide: true,
                process: function (v, row) {
                    if (row.id == 'noId') {
                        return v;
                    }
                    if (row.payFor == 'FKLX-03') {
                        if (row.sourceType != '') {
                            return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        }
                    } else {
                        if (row.sourceType != '') {
                            return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        }
                    }
                }
            },
            {
                name: 'payMoneyCur',
                display: '����ҽ��',
                sortable: true,
                process: function (v, row) {
                    if (row.currencyCode != 'CNY' && row.id != 'noId') {
                        return '--';
                    } else {
                        if (v >= 0) {
                            return moneyFormat2(v);
                        } else {
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        }
                    }
                },
                width: 80
            },
            {
                name: 'payMoney',
                display: '��ҽ��',
                sortable: true,
                process: function (v, row) {
                    if (row.currencyCode == 'CNY' && row.id != 'noId') {
                        return '--';
                    } else {
                        if (v >= 0) {
                            return moneyFormat2(v);
                        } else {
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        }
                    }
                },
                width: 80
            },
            {
                name: 'supplierName',
                display: '��Ӧ������',
                sortable: true,
                width: 150
            },
            {
                name: 'account',
                display: '�����˺�',
                sortable: true,
                width: 120
            },
            {
                name: 'bank',
                display: '��������',
                sortable: true,
                width: 120
            },
            {
                name: 'ExaUser',
                display: '������',
                sortable: true,
                width: 45
            },
            {
                name: 'ExaContent',
                display: '������Ϣ',
                sortable: true,
                width: 130
            },
            {
                name: 'salesman',
                display: '������',
                sortable: true,
                width: 45
            },
            {
                name: 'deptName',
                display: '���벿��',
                sortable: true,
                width: 80
            },
            {
                name: 'remark',
                display: '������;',
                sortable: true,
                width: 80
            },
            {
                name: 'instruction',
                display: '֧��˵��',
                sortable: true,
                width: 100
            },
            {
                name: 'isAdvPay',
                display: '��ǰ����',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v) {
                    if (v == '1') {
                        return '��';
                    } else {
                        return '��';
                    }
                }
            },
            {
                name: 'auditDate',
                display: '������������',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    return v == "" ? row.payDate : v;
                }
            },
            {
                name: 'actPayDate',
                display: 'ʵ�ʸ�������',
                sortable: true,
                width: 80
            },
            {
                name: 'formDate',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'sourceType',
                display: 'Դ������',
                sortable: true,
                datacode: 'YFRK',
                width: 80
            },
            {
                name: 'payFor',
                display: '��������',
                sortable: true,
                datacode: 'FKLX',
                width: 80
            },
            {
                name: 'pchMoney',//Դ�����
                display: 'Դ����ͬ���',
                sortable: false,
                process: function (v, row) {
                    if (v >= 0) {
                        return moneyFormat2(v);
                    } else {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                },
                width: 80
            },
            {
                name: 'payedMoney',
                display: '�Ѹ����',
                sortable: true,
                process: function (v) {
                    if (v >= 0) {
                        return moneyFormat2(v);
                    } else {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                },
                width: 80
            },
            {
                name: 'currency',
                display: '�������',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'rate',
                display: '����',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'status',
                display: '����״̬',
                sortable: true,
                datacode: 'FKSQD',
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaDT',
                display: '����ʱ��',
                sortable: true,
                width: 80
            },
            {
                name: 'feeDeptName',
                display: '���ù�������',
                sortable: true,
                width: 80
            },
            {
                name: 'feeDeptId',
                display: '���ù�������id',
                sortable: true,
                hide: true,
                width: 80
            },
            {
                name: 'businessBelongName',
                display: '������˾',
                sortable: true,
                width: 80
            },
            {
                name: 'isInvoice',
                display: '�Ƿ񿪾ݷ�Ʊ',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.sourceType == 'YFRK-02') {
                        if (v == '1') {
                            return '��';
                        } else if (v == '0') {
                            return '��';
                        }
                    }
                    else
                        return '-';
                }
            },
            {
                name: 'comments',
                display: '��ע',
                sortable: true,
                width: 80
            },
            {
                name: 'createName',
                display: '������',
                hide: true,
                sortable: true
            },
            {
                name: 'createTime',
                display: '��������',
                sortable: true,
                width: 120,
                hide: true
            },
            {
                name: 'lastPrintTime',
                display: '���һ�δ�ӡʱ��',
                sortable: true,
                width: 120
            }
        ],
        // ���ӱ������
        subGridOptions: {
            url: '?model=finance_payablesapply_detail&action=pageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'payapplyId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������
                }
            ],
            // ��ʾ����
            colModel: [
                {
                    name: 'objType',
                    display: 'Դ������',
                    datacode: 'YFRK'
                },
                {
                    name: 'objCode',
                    display: 'Դ�����',
                    width: 150
                },
                {
                    name: 'money',
                    display: '������',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'purchaseMoney',
                    display: 'Դ�����',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                }
            ]
        },
        buttonsEx: buttonsArr,
        //��������
        comboEx: [
            {
                text: '����״̬',
                key: 'ExaStatus',
                type: 'workFlow',
                value: '���'
            },
            {
                text: '����״̬',
                key: 'status',
                datacode: 'FKSQD',
                value: 'FKSQD-01',
                clearExtParam: true
            }
        ],
        searchitems: [
            {
                display: '��Ӧ������',
                name: 'supplierName'
            },
            {
                display: '���뵥���',
                name: 'formNoSearch'
            },
            {
                display: 'Դ�����',
                name: 'objCodeSearch'
            },
            {
                display: 'id',
                name: 'id'
            },
            {
                display: '������',
                name: 'salesmanSearch'
            },
            {
                display: '���벿��',
                name: 'deptNameSearch'
            },
            {
                display: '���ù�������',
                name: 'feeDeptNameSearch'
            }
        ],
        sortorder: 'DESC',
        sortname: 'c.actPayDate DESC,c.lastPrintTime DESC,c.id'
    });
});