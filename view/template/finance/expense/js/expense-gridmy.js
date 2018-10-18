var show_page = function () {
    $("#expenseGrid").yxgrid("reload");
};

//查看方法 - 兼容新旧报销单
function viewBill(id, billNo, isNew) {
    if (isNew == '1') {
        showModalWin("?model=finance_expense_exsummary&action=toView&id=" + id, 1)
    } else {
        showOpenWin("general/costmanage/reim/summary_detail.php?status=出纳付款&BillNo=" + billNo, 1)
    }
}

function getCostSummaryProvinceOpts(){
    var result = $.ajax({
        type: 'POST',
        url: "index1.php?model=finance_expense_expense&action=getAllProProvince",
        async: false
    }).responseText;
    var result = eval("("+result+")");
    var optArr = [];
    $.each(result.optsArr,function(i,item){
        optArr.push({
            text: item,
            value: i
        });
    })
    return optArr;
}

$(function () {
    var provinceOpts = getCostSummaryProvinceOpts();
    $("#expenseGrid").yxgrid({
        model: 'finance_expense_expense',
        action: 'myPageJson',
        title: '我的报销明细',
        isDelAction: false,
        customCode: 'myexpense',
        showcheckbox: false,
        isOpButton: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '收单',
            name: 'recView',
            sortable: true,
            align: 'center',
            width: 30,
            process: function (v, row) {
                if (row.needExpenseCheck == "1") {
                    if (row.IsFinRec == '1') {
                        return '<img title="部门收单[' + row.RecInvoiceDT + '] \n上交财务[' + row.HandUpDT + '] \n财务收单[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    } else {
                        if (row.isHandUp == "1") {
                            return '<img title="部门收单[' + row.RecInvoiceDT + '] \n上交财务[' + row.HandUpDT + ']" src="images/icon/ok2.png" style="width:15px;height:15px;">';
                        } else {
                            if (row.isNotReced == '0') {
                                return '<img title="部门收单[' + row.RecInvoiceDT + ']" src="images/icon/ok1.png" style="width:15px;height:15px;">';
                            }
                        }
                    }
                } else {
                    if (row.IsFinRec == '1') {
                        return '<img title="财务收单[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    }
                }
            }
        }, {
            display: '部门收单状态',
            name: 'isNotReced',
            sortable: true,
            width: 60,
            hide: true,
            process: function (v) {
                return v == '0' ? '已收单' : '未收单';
            }
        }, {
            display: '部门收单时间',
            name: 'RecInvoiceDT',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '单据上交状态',
            name: 'isHandUp',
            sortable: true,
            width: 60,
            hide: true,
            process: function (v) {
                return v == '1' ? '已上交' : '未上交';
            }
        }, {
            display: '单据上交时间',
            name: 'HandUpDT',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '财务收单状态',
            name: 'IsFinRec',
            sortable: true,
            width: 60,
            hide: true,
            process: function (v) {
                return v == '1' ? '已收单' : '未收单';
            }
        }, {
            display: '财务收单时间',
            name: 'FinRecDT',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '新报销单',
            name: 'isNew',
            sortable: true,
            width: 50,
            process: function (v) {
                return v == '1' ? '是' : '否';
            },
            hide: true
        }, {
            display: '需要部门检查',
            name: 'needExpenseCheck',
            sortable: true,
            width: 50,
            process: function (v) {
                return v == '1' ? '是' : '否';
            },
            hide: true
        }, {
            name: 'BillNo',
            display: '审批单号',
            sortable: true,
            width: 130,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='viewBill(\"" + row.id + "\",\"" + row.BillNo + "\",\"" +
                    row.isNew + "\")'>" + v + "</a>";
            }
        }, {
            name: 'DetailType',
            display: '报销类型',
            sortable: true,
            width: 70,
            process: function (v, row) {
                if (v * 1 > 0) {
                    switch (v) {
                        case '1' :
                            return '部门费用';
                        case '2' :
                            return '合同项目费用';
                        case '3' :
                            return '研发费用';
                        case '4' :
                            return '售前费用';
                        case '5' :
                            return '售后费用';
                        default :
                            return v;
                    }
                } else {
                    switch (row.CostBelongTo) {
                        case '1' :
                            return '部门费用';
                        default :
                            return '工程费用';
                    }
                }
            }
        }, {
            name: 'CostManName',
            display: '报销人',
            sortable: true,
            width: 90,
            hide: true
        },  {
            name: 'CostDepartName',
            display: '报销人部门',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'CostManCom',
            display: '报销人公司',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'CostBelongDeptName',
            display: '费用归属部门',
            sortable: true,
            width: 75
        }, {
            name: 'CostBelongCom',
            display: '费用归属公司',
            sortable: true,
            width: 75
        }, {
            name: 'Amount',
            display: '报销金额',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'feeRegular',
            display: '常规费用',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            },
            hide: true
        }, {
            name: 'feeSubsidy',
            display: '补贴费用',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            },
            hide: true
        }, {
            name: 'invoiceMoney',
            display: '发票金额',
            sortable: true,
            width: 80,
            hide: true,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'invoiceNumber',
            display: '发票数量',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'CheckAmount',
            display: '检查金额',
            sortable: true,
            hide: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'isProject',
            display: '项目报销',
            sortable: true,
            process: function (v) {
                return v == '1' ? '是' : '否';
            },
            width: 60,
            hide: true
        }, {
            name: 'ProjectNO',
            display: '项目编号',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'proManagerName',
            display: '项目经理',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'proProvince',
            display: '项目省份',
            sortable: true,
            width: 90
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'contractName',
            display: '合同名称',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'CustomerType',
            display: '客户类型',
            sortable: true,
            hide: true
        }, {
            name: 'Purpose',
            display: '事由',
            sortable: true,
            width: 180
        }, {
            name: 'InputManName',
            display: '录入人',
            sortable: true,
            width: 90,
            hide: true
        }, {
            name: 'Status',
            display: '单据状态',
            sortable: true,
            width: 70
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            width: 70,
            sortable: true,
            hide: true
        }, {
            name: 'ExaDT',
            display: '审批日期',
            width: 80,
            sortable: true
        }, {
            name: 'InputDate',
            display: '录入时间',
            sortable: true,
            width: 130
        }, {
            name: 'subCheckDT',
            display: '提交检查时间',
            sortable: true,
            width: 130,
            hide: true
        }, {
            name: 'UpdateDT',
            display: '更新时间',
            sortable: true,
            width: 130,
            hide: true
        }],
        toAddConfig: {
            toAddFn: function () {
                showModalWin("?model=finance_expense_expense&action=toAdd", 1, 'expenseAdd');
            }
        },
        //打开的是expense中的方法 -- 情形比较特殊
        toEditConfig: {
            showMenuFn: function (row) {
                return (row.isNew == '1' && row.ExaStatus == '编辑' && row.Status == '编辑')
                    || row.isNew == '0' && row.Status == '编辑';
            },
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                if (rowData.isNew == '1') {
                    showModalWin("?model=finance_expense_expense&action=toEdit&id=" + rowData.id, 1, rowData.BillNo);
                } else {
                    alert('此单据为【旧报销单】，不能进行【编辑】操作，请重新录入一张新报销单，不便之处，敬请谅解');
                }
            }
        },
        //打开的是expense中的方法 -- 情形比较特殊
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                viewBill(row.id, row.BillNo, row.isNew);
            }
        },
        menusEx: [{
            text: "重新编辑",
            icon: 'edit',
            showMenuFn: function (row) {
                return row.Status == '打回';
            },
            action: function (row) {
                if (row.isNew == '1') {
                    showModalWin("?model=finance_expense_expense&action=toEdit&id=" + row.id, 1, row.BillNo);
                } else {
                    alert('此单据为【旧报销单】，不能进行【重新编辑】操作，请重新录入一张新报销单，不便之处，敬请谅解');
                }
            }
        }, {
            text: "提交部门检查",
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.needExpenseCheck == "0") {
                    return false;
                }
                return row.isNew == '1' && row.Status == '编辑';
            },
            action: function (row) {
                if (window.confirm(("确定要提交部门检查吗?"))) {
                    if (row.projectId != "0") {
                        var projectOverspend = false;
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_project_esmproject&action=ajaxGetProject",
                            data: {'id': row.projectId},
                            dataType: 'json',
                            async: false,
                            success: function (data) {
                                if (data != null) {
                                    if (data.feeAll * 1 > data.budgetAll * 1) {
                                        projectOverspend = false;
                                        alert('提示：因项目现已超支，暂不允许提交报销单，请联系项目经理，尽快变更项目预算。');
                                    }else{
                                        projectOverspend = true;
                                    }
                                } else {
                                    projectOverspend = false;
                                    alert('提示：项目匹配出错，请重新尝试');
                                }
                            }
                        });
                        if (!projectOverspend) {
                            return false;
                        }
                    }

                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=ajaxHand",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('提交成功！');
                                show_page(1);
                            } else {
                                alert("提交失败! ");
                            }
                        }
                    });
                }
            }
        }, {
            text: "确认单据",
            icon: 'edit',
            showMenuFn: function (row) {
                return row.isNew == '1' && row.Status == '等待确认';
            },
            action: function (row) {
                if (window.confirm(("确定要确认单据吗？"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=confirmCheck",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('确认成功！');
                                show_page(1);
                            } else {
                                alert("确认失败! ");
                            }
                        }
                    });
                }
            }
        }, {
            text: "否认单据",
            icon: 'delete',
            showMenuFn: function (row) {
                return row.isNew == '1' && row.Status == '等待确认';
            },
            action: function (row) {
                if (window.confirm(("确定要否认单据吗？"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=unconfirmCheck",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('否认成功！');
                                show_page(1);
                            } else {
                                alert("否认失败! ");
                            }
                        }
                    });
                }
            }
        }, {
            text: "提交审批",
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.needExpenseCheck == "1") {
                    return false;
                }
                return row.isNew == '1'
                    && (row.ExaStatus == '编辑' || row.ExaStatus == '打回')
                    && row.Status != '部门检查';
            },
            action: function (row) {
                //如果包含是工程报销类型且含有项目信息,获取项目区域
                var rangeId = '';
                if (row.projectId != "0") {
                    var projectOverspend = false; // 超支
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_project_esmproject&action=ajaxGetProject",
                        data: {id: row.projectId},
                        dataType: 'json',
                        async: false,
                        success: function (data) {
                            if (data != null) {
                                if (data.feeAll * 1 > data.budgetAll * 1) {
                                    projectOverspend = true;
                                    alert('提示：因项目现已超支，暂不允许提交报销单，请联系项目经理，尽快变更项目预算。');
                                }
                            } else {
                                projectOverspend = true;
                                alert('提示：项目匹配出错，请重新尝试');
                            }
                        }
                    });
                    // 如果超支，返回失败
                    if (projectOverspend) {
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_project_esmproject&action=getRangeId",
                        data: {'projectId': row.projectId},
                        async: false,
                        success: function (data) {
                            rangeId = '&billArea=' + data;
                        }
                    });
                }

                // alert("提交审批前需要检查是否存在与阿里商旅重叠的费用项，请耐心等待, 谢谢!");
                // 检查填报区间内的阿里商旅是否已存在对应的费用项
                var chkResult = $.ajax({
                    url : 'index1.php?model=finance_expense_expense&action=checkAliTripCostRecord',
                    data: {
                        type : 'byBillNo',
                        billNo : (row)? row.BillNo : ''
                    },
                    type : "POST",
                    async : false
                }).responseText;
                var result = eval("("+chkResult+")");
                if(result.result.length > 0){
                    console.log(result);
                    if(!confirm("本报销区间内，存在与阿里商旅重叠的费用项，请核查是否继续提交。")){
                        return false;
                    }
                }

                if (row.isLate == "1") {
                    showThickboxWin('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId='
                        + row.id + '&flowMoney=' + row.Amount
                        + '&billDept=' + row.CostBelongDeptId
                        + '&billCompany=' + row.CostBelongComId
                        + rangeId
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                } else {
                    showThickboxWin('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId='
                        + row.id + '&flowMoney=' + row.Amount
                        + '&billDept=' + row.CostBelongDeptId
                        + '&billCompany=' + row.CostBelongComId
                        + rangeId
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            }
        }, {
            name: 'cancel',
            text: '撤消审批',
            icon: 'edit',
            showMenuFn: function (row) {
                // 第一人审批过后不显示撤销按钮
                var result = $.ajax({
                    type: 'POST',
                    url: "index1.php?model=common_approvalView&action=checkHasAppAction",
                    data: {
                        pid: row.id,
                        itemtype: 'cost_summary_list'
                    },
                    async: false
                }).responseText;

                var hasAppAction = (result == 0) ? 0 : parseInt(result);

                if ((row.isNew == '1' && row.ExaStatus == '部门审批' && row.needExpenseCheck == '0') && hasAppAction <= 0) {
                    return 1;
                } else {
                    return 0;
                }
            },
            action: function (row) {
                if (row) {
                    var ewfurl = row.isLate == "1" ?
                        'controller/finance/expense/ewf_indexlate.php?actTo=delWork&billId=' :
                        'controller/finance/expense/ewf_index.php?actTo=delWork&billId=';
                    $.ajax({
                        type: "POST",
                        url: "?model=common_workflow_workflow&action=isAudited",
                        data: {
                            billId: row.id,
                            examCode: 'cost_summary_list'
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('单据已经存在审批信息，不能撤销审批！');
                                show_page();
                                return false;
                            } else {
                                if (confirm('确定要撤消审批吗？')) {
                                    $.ajax({
                                        type: "GET",
                                        url: ewfurl,
                                        data: {"billId": row.id, "isLate": row.isLate},
                                        async: false,
                                        success: function (data) {
                                            //撤销审批需减去费用预算表中的已决算
                                            $.ajax({
                                                type: "GET",
                                                url: '?model=finance_budget_budget&action=subFinal',
                                                data: {"billId": row.id},
                                                async: false,
                                                success: function (dataTwo) {
                                                    alert(data);
                                                    show_page();
                                                }
                                            })
                                        }
                                    });
                                }
                            }
                        }
                    });
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            text: "删除",
            icon: 'delete',
            showMenuFn: function (row) {
                return row.isNew == '1' && (row.Status == '编辑' || row.Status == '打回');
            },
            action: function (row) {
                if (window.confirm(("确定要删除?"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=ajaxdeletes",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('删除成功！');
                                show_page(1);
                            } else {
                                alert("删除失败! ");
                                show_page(1);
                            }
                        }
                    });
                }
            }
        }, {
            text: "打单",
            icon: 'print',
            showMenuFn: function (row) {
                if (row.isNew == "1") {
                    if (row.ExaStatus != "编辑" && row.ExaStatus != '打回') {
                        return true;
                    }
                } else {
                    if (row.Status != '编辑' && row.Status != '部门检查') {
                        return true;
                    }
                }
                return false;
            },
            action: function (row) {
                showOpenWin("?model=cost_bill_billcheck&action=print_bill&billno=" + row.BillNo, 1);
            }
        }],
        //过滤数据
        comboEx: [{
            text: '项目省份',
            key: 'proProvinceSearch',
            data: provinceOpts
        },{
            text: '报销类型',
            key: 'DetailType',
            data: [{
                text: '部门费用',
                value: '1'
            }, {
                text: '合同项目费用',
                value: '2'
            }, {
                text: '研发费用',
                value: '3'
            }, {
                text: '售前费用',
                value: '4'
            }, {
                text: '售后费用',
                value: '5'
            }, {
                text: '旧报销单',
                value: '0'
            }]
        }, {
            text: '单据状态',
            key: 'Status',
            data: [{
                text: '编辑',
                value: '编辑'
            }, {
                text: '部门检查',
                value: '部门检查'
            }, {
                text: '等待确认',
                value: '等待确认'
            }, {
                text: '部门审批',
                value: '部门审批'
            }, {
                text: '财务审核',
                value: '财务审核'
            }, {
                text: '出纳付款',
                value: '出纳付款'
            }, {
                text: '打回',
                value: '打回'
            }, {
                text: '完成',
                value: '完成'
            }]
        }],
        searchitems: [{
            display: "审批单号",
            name: 'BillNoSearch'
        }, {
            display: "项目名称",
            name: 'projectNameSearch'
        }, {
            display: "项目编号",
            name: 'projectCodeSearch'
        }, {
            display: "商机编号",
            name: 'chanceCodeSearch'
        }, {
            display: "商机名称",
            name: 'chanceNameSearch'
        }, {
            display: "合同编号",
            name: 'contractCodeSearch'
        }, {
            display: "合同名称",
            name: 'contractNameSearch'
        }, {
            display: "报销金额",
            name: 'Amount'
        }, {
            display: "报销人",
            name: 'CostManNameSearch'
        }, {
            display: "事由",
            name: 'PurposeSearch'
        }],
        sortname: "c.UpdateDT"
    });
});