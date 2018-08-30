var show_page = function () {
    $("#otherGrid").yxgrid("reload");
};

$(function () {

    //表头按钮数组
    var buttonsArr = [];

    //表头按钮数组
    var excelOutArr = {
        name: 'exportOut',
        text: "导出",
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

    // 异步设置导入权限
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '导出权限'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(excelOutArr);
            }
        }
    });

    // 异步设置导入权限
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '回款邮件通知权限'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                // 设置邮件通知按钮
                buttonsArr.push({
                    name: 'mail',
                    text: "回款邮件通知",
                    icon: 'search',
                    action: function () {
                        showThickboxWin('?model=contract_other_other&action=toSendMail'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800')
                    }
                });
            }
        }
    });

    // 发票录入权限
    var invoiceLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '发票权限'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                invoiceLimit = true;
            }
        }
    });

    // 录入不开票金额权限
    var unInvoiceLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '录入不开票金额'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                unInvoiceLimit = true;
            }
        }
    });

    // 录入无分摊发票权限
    var noShareCostLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '录入无分摊发票'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                noShareCostLimit = true;
            }
        }
    });

    // 部门助理权限
    var assistantLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '部门助理'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                assistantLimit = true;
            }
        }
    });

    // 修改分摊明细权限
    var costLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '修改分摊明细'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                costLimit = true;
            }
        }
    });

    // 关闭合同权限
    var closeLimit = false;
    $.ajax({
        type: 'POST',
        url: '?model=contract_other_other&action=getLimits',
        data: {
            'limitName': '关闭合同权限'
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
        title: '其他合同',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        customCode: 'otherGrid',
        isOpButton: false,
        autoload: autoloadVal,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'createDate',
            display: '录入日期',
            width: 70
        }, {
            name: 'fundTypeName',
            display: '款项性质',
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
            display: '鼎利合同号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (row.status == 4) {
                    return "<a href='#' style='color:red' title='变更中的合同' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    return "<a href='#' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                }
            }
        }, {
            name: 'orderName',
            display: '合同名称',
            sortable: true,
            width: 130
        }, {
            name: 'signCompanyName',
            display: '签约公司',
            sortable: true,
            width: 150
        }, {
            name: 'businessBelongName',
            display: '归属公司',
            sortable: true,
            width: 100
        }, {
            name: 'proName',
            display: '公司省份',
            sortable: true,
            width: 70
        }, {
            name: 'address',
            display: '联系地址',
            sortable: true,
            hide: true
        }, {
            name: 'phone',
            display: '联系电话',
            sortable: true,
            hide: true
        }, {
            name: 'linkman',
            display: '联系人',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'signDate',
            display: '签约日期',
            sortable: true,
            width: 80
        }, {
            name: 'currency',
            display: '币种',
            width: 70
        }, {
            name: 'orderMoney',
            display: '合同总金额',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'payForBusinessName',
            display: '付款业务类型',
            sortable: true,
            process: function (v, row) {
                return (v == "NULL") ? "" : v;
            }
        }, {
            name: 'payApplyMoney',
            display: '申请付款',
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
                        var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款申请金额为：' + moneyFormat2(row.countPayApplyMoney);
                        return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
                    }
                }
            },
            width: 80
        }, {
            name: 'payedMoney',
            display: '已付金额',
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
                        var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款金额为：' + moneyFormat2(row.countPayMoney);
                        return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
                    }
                }
            },
            width: 80
        }, {
            name: 'returnMoney',
            display: '返款金额',
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
            display: '已收发票',
            sortable: true,
            process: function (v, row) {
                if (row.fundType != 'KXXZB') {
                    if (row.id == 'noId') {
                        return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
                    }
                    return '--';
                } else {
                    if (v * 1 != 0) {
                        var thisTitle = '其中初始导入收票金额为: ' + moneyFormat2(row.initInvotherMoney) + ',后期收票金额为：' + moneyFormat2(row.countInvotherMoney);
                        return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
                    } else {
                        return 0;
                    }
                }
            },
            width: 80
        }, {
            name: 'confirmInvotherMoney',
            display: '财务确认发票',
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
            display: '未回款/未开票金额',
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
            display: '申请开票',
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
            display: '已开发票',
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
            display: '收款金额',
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
            display: '不开票金额',
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
            display: '合同负责人',
            sortable: true,
            hide: true
        }, {
            name: 'deptName',
            display: '部门名称',
            sortable: true,
            hide: true
        }, {
            name: 'status',
            display: '状态',
            sortable: true,
            width: 60,
            process: function (v) {
                if (v == '0') {
                    return "未提交";
                } else if (v == 1) {
                    return "审批中";
                } else if (v == 2) {
                    return "执行中";
                } else if (v == 3) {
                    return "已关闭";
                } else if (v == 4) {
                    return "变更中";
                }
            }
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 60
        }, {
            name: 'signedStatus',
            display: '合同签收',
            sortable: true,
            process: function (v, row) {
                if (row.id == "noId") {
                    return '';
                }
                if (v == "1") {
                    return "已签收";
                } else {
                    return "未签收";
                }
            },
            width: 70
        }, {
            name: 'objCode',
            display: '业务编号',
            sortable: true,
            width: 120
        }, {
            name: 'isNeedStamp',
            display: '已申请盖章',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (v == "0") {
                    return "否";
                } else if (v == "1") {
                    return "是";
                }
            }
        }, {
            name: 'isStamp',
            display: '是否已盖章',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (v == "0") {
                    return "否";
                } else if (v == "1") {
                    return "是";
                }
            }
        }, {
            name: 'stampType',
            display: '盖章类型',
            sortable: true,
            width: 80
        }, {
            name: 'createName',
            display: '申请人',
            sortable: true
        }, {
            name: 'remark',
            display: '备注',
            sortable: true,
            width: 150
        }, {
            name: 'updateTime',
            display: '更新时间',
            sortable: true,
            width: 130
        }, {
            name: 'chanceCode',
            display: '商机编号',
            sortable: true,
            width: 120
        }, {
            name: 'prefBidDate',
            display: '预计投标时间',
            sortable: true,
            width: 120
        }, {
            name: 'contractCode',
            display: '销售合同编号',
            sortable: true,
            width: 120
        }, {
            name: 'projectPrefEndDate',
            display: '项目预计结束时间',
            sortable: true,
            width: 120
        }, {
            name: 'delayPayDays',
            display: '延后回款天数',
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
            display: '是否是银行保函',
            sortable: true,
            width: 90,
            process: function (v, rowData) {
                if (v == "0") {
                    return (rowData.payForBusiness == "FKYWLX-03" || rowData.payForBusiness == "FKYWLX-04")? "否" : "";
                } else if (v == "1") {
                    return "是";
                }
            }
        },{
            name: 'prefPayDate',
            display: '预计押金回款时间',
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
                return row.ExaStatus == "待提交" || row.ExaStatus == "打回";
            }
        },
        // 扩展右键菜单
        menusEx: [{
            text: '查看合同',
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
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '录入发票',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || invoiceLimit == false) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                if (row.orderMoney * 1 <= accAdd(row.invotherMoney, row.returnMoney, 2) * 1) {
                    alert('合同可录入发票额已满');
                    return false;
                }
                showModalWin("?model=finance_invother_invother&isAudit=1&action=toAddObj&objType=YFQTYD02&objId=" + row.id, 1, row.id);
            }
        }, {
            text: '录入发票(无分摊)',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || invoiceLimit == false || noShareCostLimit == false) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                if (row.orderMoney * 1 <= accAdd(row.invotherMoney, row.returnMoney, 2) * 1) {
                    alert('合同可录入发票额已满');
                    return false;
                }
                showModalWin("?model=finance_invother_invother&isAudit=1&action=toAddObj&shareCost=0&objType=YFQTYD02&objId=" + row.id, 1, row.id);
            }
        }, {
            text: '录入不开票金额',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3) {
                    return false;
                } else if (unInvoiceLimit == false) {//权限控制
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
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
            text: '录入返款',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || invoiceLimit == false) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
            },
            action: function (row, rows, grid) {
                showThickboxWin("?model=contract_other_other&action=toUpdateReturnMoney&id="
                    + row.id
                    + "&skey=" + row.skey_
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
            }
        }, {
            text: '申请付款',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status == 3 || assistantLimit == false) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
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
                        alert('合同存在未处理完成的退款单，不能申请付款');
                        return false;
                    } else { //如果可以继续申请
                        showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + row.id, 1, row.id);
                    }
                } else {
                    alert("请选中一条数据");
                }
            }
            // }, {
            //     name: 'stamp',
            //     text: '申请盖章',
            //     icon: 'add',
            //     showMenuFn: function (row) {
            //         if (row.status == 3 || assistantLimit == false) {
            //             return false;
            //         }
            //         if (row.ExaStatus == "完成") {
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
            //             alert("请选中一条数据");
            //         }
            //     }
        }, {
            name: 'change',
            text: '修改分摊明细',
            icon: 'edit',
            showMenuFn: function (row) {
                return row.ExaStatus == "完成" && (row.fundType == 'KXXZB' || row.fundType == 'KXXZC') && row.status == '2' && costLimit;
            },
            action: function (row) {
                showModalWin("?model=contract_other_other&action=toChangeCostShare&id="
                    + row.id
                    + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            text: '关闭合同',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.ExaStatus == "完成") {
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
                    alert("请选中一条数据");
                }
            }
        }],
        buttonsEx: buttonsArr,

        // 高级搜索
        advSearchOptions: {
            modelName: 'otherGrid',
            // 选择字段后进行重置值操作
            selectFn: function ($valInput) {
                $valInput.yxselect_user("remove");
                $valInput.yxcombogrid_signcompany("remove");
            },
            searchConfig: [{
                name: '鼎利合同号',
                value: 'c.orderCode'
            }, {
                name: '签约日期',
                value: 'c.signDate',
                changeFn: function ($t, $valInput) {
                    $valInput.click(function () {
                        WdatePicker({
                            dateFmt: 'yyyy-MM-dd'
                        });
                    });
                }
            }, {
                name: '签约公司',
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
                name: '公司省份',
                value: 'c.proName'
            }, {
                name: '合同金额',
                value: 'c.orderMoney'
            }, {
                name: '款项性质',
                value: 'c.fundType',
                type: 'select',
                datacode: 'KXXZ'
            }]
        },
        searchitems: [{
            display: '负责人',
            name: 'principalName'
        }, {
            display: '申请人',
            name: 'createName'
        }, {
            display: '签约公司',
            name: 'signCompanyName'
        }, {
            display: '合同名称',
            name: 'orderName'
        }, {
            display: '合同编号',
            name: 'orderCodeSearch'
        }, {
            display: '业务编号',
            name: 'objCodeSearch'
        }],
        // 默认搜索字段名
        sortname: "c.createTime",
        // 默认搜索顺序 降序DESC 升序ASC
        sortorder: "DESC",
        // 审批状态数据过滤
        comboEx: [{
            text: "款项性质",
            key: 'fundType',
            datacode: 'KXXZ'
        }, {
            text: "付款业务类型",
            key: 'payForBusiness',
            datacode: 'FKYWLX'
        }, {
            text: '合同状态',
            key: 'status',
            value: 2,
            data: [{
                text: '未提交',
                value: '0'
            }, {
                text: '审批中',
                value: '1'
            }, {
                text: '执行中',
                value: '2'
            }, {
                text: '已关闭',
                value: '3'
            }, {
                text: '变更中',
                value: '4'
            }]
        }, {
            text: '款票对比',
            key: 'payandinv',
            data: [{
                text: '大于',
                value: '1'
            }, {
                text: '大于等于',
                value: '2'
            }, {
                text: '等于',
                value: '3'
            }, {
                text: '小于等于',
                value: '4'
            }, {
                text: '小于',
                value: '5'
            }
            ]
        }]
    });
});