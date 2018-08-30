var show_page = function () {
    $("#payablesapplyGrid").yxgrid("reload");
};

$(function () {
    //初始化表头按钮
    var buttonsArr = [
        {
            text: "批量打印",
            icon: 'print',
            action: function (row, rows, idArr) {
                if (row) {
                    var idArrCatch = [];
                    for (var i = 0; i < rows.length; i++) {
                        if (rows[i].ExaStatus !== '完成') {
                            alert('单据 [' + rows[i].id + '] 审批未完成，不能进行批量打印操作，如需打印，请选择单据后，点击独立打印功能');
                            return false;
                        }

                        if (rows[i].status != 'FKSQD-01') {
                            alert('单据 [' + rows[i].id + '] 不是未付款状态，不能进行批量打印操作，如需打印，请选择单据后，点击独立打印功能');
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
            text: '独立打印',
            icon: 'print',
            action: function (row, rows, idArr) {
                if (row) {
                    var idStr = idArr.toString();
                    showModalWin("?model=finance_payablesapply_payablesapply&action=toBatchPrintAlong&id=" + idStr, 1);
                } else {
                    alert('请选择一张单据打印');
                }
            }
        },
        {
            text: "确认付款",
            icon: 'add',
            action: function (row, rows, idArr) {
                if (row) {
                    if (confirm('确认进行此操作么？')) {
                        for (var i = 0; i < rows.length; i++) {
                            if (rows[i].ExaStatus !== '完成') {
                                alert('单据 [' + rows[i].id + '] 审批未完成，不能进行确认付款操作');
                                return false;
                            }

                            if (rows[i].status != 'FKSQD-01') {
                                alert('单据 [' + rows[i].id + '] 不是未付款状态，不能进行确认付款操作');
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
                                    alert('录入成功！');
                                    show_page(1);
                                } else {
                                    alert('录入失败!');
                                }
                            }
                        });
                    }
                } else {
                    alert('请先选择至少一条记录');
                }
            }
        },
        {
            text: '录入付款',
            icon: 'add',
            action: function (row, rows) {
                if (row) {
                    if (rows.length != 1) {
                        alert('此功能只能一次选择一张单据');
                        return false;
                    }
                    if (row.ExaStatus == '完成' && row.status == 'FKSQD-01') {
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
                        alert('单据已付款或者未符合付款条件，不能进行此操作');
                        return false;
                    }
                } else {
                    alert('请选择一张单据');
                }
            }
        },
        {
            name: 'view',
            text: "高级查询",
            icon: 'view',
            action: function () {
                showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
            }
        },
        {
            name: 'file',
            text: "附件上传",
            icon: 'edit',
            action: function (row, rows) {
                if (row) {
                    if (rows.length != 1) {
                        alert('此功能只能一次选择一张单据');
                        return false;
                    }
                    showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id=" + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
                } else {
                    alert('请选择一张单据');
                }
            }
        },
        {
            name: 'excOut',
            text: "导出",
            icon: 'excel',
            items: [
                {
                    text: '付款信息',
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
                    text: '付款明细',
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
                    text: '付款明细(07)',
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
    //批量关闭按钮
    var batchClose = {
        text: "批量关闭",
        icon: 'delete',
        action: function (row, rows, idArr) {
            if (row) {
                for (var i = 0; i < rows.length; i++) {
                    if (rows[i].ExaStatus != '完成') {
                        alert('单据 [' + rows[i].id + '] 审批未完成，不能进行关闭操作');
                        return false;
                    }
                    if (rows[i].status != 'FKSQD-00' && rows[i].status != 'FKSQD-01') {
                        alert('单据 [' + rows[i].id + '] 不是未提交支付/未付款状态，不能进行关闭操作');
                        return false;
                    }
                }
                showThickboxWin('?model=finance_payablesapply_payablesapply&action=toBatchClose&id='
                    + idArr.toString()
                    + '&skey=' + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
            } else {
                alert('请选择一张单据');
            }
        }
    };
    //关闭付款申请权限
    $.ajax({
        type: 'POST',
        url: '?model=finance_payablesapply_payablesapply&action=getLimits',
        data: {
            'limitName': '关闭付款申请权限'
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
        title: '付款申请',
        action: 'pageJsonList',
        param: {payForArr: 'FKLX-01,FKLX-02', isEntrust: '0'},
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        customCode: 'payablesapplyGrid',
        noCheckIdValue: 'noId',
        isRightMenu: false,
        pageSize: 40, // 每页默认的结果数
        //列信息
        colModel: [
            {
                display: '打印',
                name: 'printId',
                width: 30,
                align: 'center',
                sortable: false,
                process: function (v, row) {
                    if (row.id == 'noId') return '';
                    if (row.printCount > 0) {
                        return '<img src="images/icon/print.gif" title="打印次数为:' + row.printCount + ',最近一次打印时间:' + row.lastPrintTime + '"/>';
                    } else {
                        return '<img src="images/icon/print1.gif" title="未打印过的单据"/>';
                    }
                }
            },
            {
                display: '付款单号',
                name: 'id',
                width: 60,
                sortable: true,
                process: function (v, row) {
                    if (row.id == 'noId') {
                        return v;
                    }
                    if (row.payFor == 'FKLX-03') {
                        if (row.sourceType != '') {
                            return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
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
                display: '申请单编号',
                sortable: true,
                width: 140,
                hide: true,
                process: function (v, row) {
                    if (row.id == 'noId') {
                        return v;
                    }
                    if (row.payFor == 'FKLX-03') {
                        if (row.sourceType != '') {
                            return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
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
                display: '人民币金额',
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
                display: '外币金额',
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
                display: '供应商名称',
                sortable: true,
                width: 150
            },
            {
                name: 'account',
                display: '银行账号',
                sortable: true,
                width: 120
            },
            {
                name: 'bank',
                display: '开户银行',
                sortable: true,
                width: 120
            },
            {
                name: 'ExaUser',
                display: '审批人',
                sortable: true,
                width: 45
            },
            {
                name: 'ExaContent',
                display: '审批信息',
                sortable: true,
                width: 130
            },
            {
                name: 'salesman',
                display: '申请人',
                sortable: true,
                width: 45
            },
            {
                name: 'deptName',
                display: '申请部门',
                sortable: true,
                width: 80
            },
            {
                name: 'remark',
                display: '款项用途',
                sortable: true,
                width: 80
            },
            {
                name: 'instruction',
                display: '支付说明',
                sortable: true,
                width: 100
            },
            {
                name: 'isAdvPay',
                display: '提前申请',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v) {
                    if (v == '1') {
                        return '是';
                    } else {
                        return '否';
                    }
                }
            },
            {
                name: 'auditDate',
                display: '期望付款日期',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    return v == "" ? row.payDate : v;
                }
            },
            {
                name: 'actPayDate',
                display: '实际付款日期',
                sortable: true,
                width: 80
            },
            {
                name: 'formDate',
                display: '单据日期',
                sortable: true,
                width: 80
            },
            {
                name: 'sourceType',
                display: '源单类型',
                sortable: true,
                datacode: 'YFRK',
                width: 80
            },
            {
                name: 'payFor',
                display: '申请类型',
                sortable: true,
                datacode: 'FKLX',
                width: 80
            },
            {
                name: 'pchMoney',//源单金额
                display: '源单合同金额',
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
                display: '已付金额',
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
                display: '付款币种',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'rate',
                display: '汇率',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'status',
                display: '单据状态',
                sortable: true,
                datacode: 'FKSQD',
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaDT',
                display: '审批时间',
                sortable: true,
                width: 80
            },
            {
                name: 'feeDeptName',
                display: '费用归属部门',
                sortable: true,
                width: 80
            },
            {
                name: 'feeDeptId',
                display: '费用归属部门id',
                sortable: true,
                hide: true,
                width: 80
            },
            {
                name: 'businessBelongName',
                display: '归属公司',
                sortable: true,
                width: 80
            },
            {
                name: 'isInvoice',
                display: '是否开据发票',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.sourceType == 'YFRK-02') {
                        if (v == '1') {
                            return '是';
                        } else if (v == '0') {
                            return '否';
                        }
                    }
                    else
                        return '-';
                }
            },
            {
                name: 'comments',
                display: '备注',
                sortable: true,
                width: 80
            },
            {
                name: 'createName',
                display: '创建人',
                hide: true,
                sortable: true
            },
            {
                name: 'createTime',
                display: '创建日期',
                sortable: true,
                width: 120,
                hide: true
            },
            {
                name: 'lastPrintTime',
                display: '最后一次打印时间',
                sortable: true,
                width: 120
            }
        ],
        // 主从表格设置
        subGridOptions: {
            url: '?model=finance_payablesapply_detail&action=pageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'payapplyId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称
                }
            ],
            // 显示的列
            colModel: [
                {
                    name: 'objType',
                    display: '源单类型',
                    datacode: 'YFRK'
                },
                {
                    name: 'objCode',
                    display: '源单编号',
                    width: 150
                },
                {
                    name: 'money',
                    display: '申请金额',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'purchaseMoney',
                    display: '源单金额',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                }
            ]
        },
        buttonsEx: buttonsArr,
        //过滤数据
        comboEx: [
            {
                text: '审批状态',
                key: 'ExaStatus',
                type: 'workFlow',
                value: '完成'
            },
            {
                text: '单据状态',
                key: 'status',
                datacode: 'FKSQD',
                value: 'FKSQD-01',
                clearExtParam: true
            }
        ],
        searchitems: [
            {
                display: '供应商名称',
                name: 'supplierName'
            },
            {
                display: '申请单编号',
                name: 'formNoSearch'
            },
            {
                display: '源单编号',
                name: 'objCodeSearch'
            },
            {
                display: 'id',
                name: 'id'
            },
            {
                display: '申请人',
                name: 'salesmanSearch'
            },
            {
                display: '申请部门',
                name: 'deptNameSearch'
            },
            {
                display: '费用归属部门',
                name: 'feeDeptNameSearch'
            }
        ],
        sortorder: 'DESC',
        sortname: 'c.actPayDate DESC,c.lastPrintTime DESC,c.id'
    });
});