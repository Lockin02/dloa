var show_page = function() {
    $("#otherGrid").yxgrid("reload");
};

$(function() {
    $("#otherGrid").yxgrid({
        model: 'contract_other_other',
        action: 'myOtherListPageJson',
        title: '我的其他合同',
        isViewAction: false,
        isDelAction: false,
        customCode: 'otherGrid',
        isOpButton: false,
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
            display: '鼎利合同号',
            sortable: true,
            width: 120,
            process: function(v, row) {
                if (row.status == 4) {
                    return "<a href='#' style='color:red' title='变更中的合同' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    if (row.ExaStatus == '待提交' || row.ExaStatus == '部门审批') {
                        return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\",1," + row.id + ")'>" + v + "</a>";
                    } else {
                        return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\",1," + row.id + ")'>" + v + "</a>";
                    }
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
            width: 80
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
            name: 'projectTypeName',
            display: '项目类型',
            sortable: true,
            hide: true
        }, {
            name: 'payForBusinessName',
            display: '付款业务类型',
            sortable: true,
            process: function (v, row) {
                return (v == "NULL")? "" : v;
            }
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
            process: function(v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'payApplyMoney',
            display: '申请付款',
            sortable: true,
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            display: '已收发票',
            sortable: true,
            process: function(v, row) {
                if (row.fundType != 'KXXZB') {
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
            display: '未回款/未开票金额',
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
            display: '申请开票',
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
            display: '已开发票',
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
            display: '收款金额',
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
            display: '不开票金额',
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
            process: function(v) {
                if (v == 0) {
                    return "未提交";
                } else if (v == 1) {
                    return "审批中";
                } else if (v == 2) {
                    return "执行中";
                } else if (v == 3) {
                    return "已关闭";
                } else if (v == 4) {
                    return "变更中";
                } else if (v == 5) {
                    return "关闭审批中";
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
            process: function(v) {
                return v == "1" ? "已签收" : "未签收";
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
            process: function(v) {
                return v == 1 ? "是" : "否";
            }
        }, {
            name: 'isStamp',
            display: '是否已盖章',
            sortable: true,
            width: 60,
            process: function(v) {
                return v == 1 ? "是" : "否";
            }
        }, {
            name: 'stampType',
            display: '盖章类型',
            sortable: true,
            width: 80
        }, {
            name: 'createName',
            display: '申请人',
            sortable: true,
            hide: true
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
        },{
            name: 'chanceCode',
            display: '商机编号',
            sortable: true,
            width: 120
        },{
            name: 'prefBidDate',
            display: '预计投标时间',
            sortable: true,
            width: 120
        },{
            name: 'contractCode',
            display: '销售合同编号',
            sortable: true,
            width: 120
        },{
            name: 'projectPrefEndDate',
            display: '项目预计结束时间',
            sortable: true,
            width: 120
        },{
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
        },{
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
            formHeight: 500,
            toAddFn: function() {
                showModalWin("?model=contract_other_other&action=toAdd&open=1");
            }
        },
        toEditConfig: {
            formWidth: 1000,
            formHeight: 500,
            showMenuFn: function(row) {
                return row.ExaStatus == "待提交" || row.ExaStatus == "打回";
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
                    alert('请选择一行记录！');
                }
            }
        },
        // 扩展右键菜单
        buttonsEx: [{
            text: '批量付款',
            icon: 'add',
            action: function(row, rows) {
                if (row) {
                    var signCompanyName = "";
                    var businessBelongName = "";
                    var idArr = [];
                    for (var i = 0; i < rows.length; i++) {
                        //签约公司控制
                        if (i != 0 && (signCompanyName != rows[i].signCompanyName || businessBelongName != rows[i].businessBelongName)) {
                            alert('签约公司名称或者归属公司名称不一致，不允许进行此操作');
                            return false;
                        } else {
                            signCompanyName = rows[i].signCompanyName;
                            businessBelongName = rows[i].businessBelongName;
                        }

                        //合同类型控制
                        if (rows[i].fundType != "KXXZB") {
                            alert('非付款类合同不能进行此操作');
                            return false;
                        }

                        //状态控制
                        if (rows[i].status != '2') {
                            alert('非执行中状态的合同不能进行此操作');
                            return false
                        }

                        //合同金额基础控制
                        if (rows[i].orderMoney * 1 > rows[i].payApplyMoney) {
                            idArr.push(rows[i].id);
                        }
                    }
                    if (idArr.length == 0) {
                        alert('没有能够付款的合同');
                    } else {
                        var ids = idArr.toString();
                        showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + ids, 1, 'batch');
                    }
                } else {
                    alert("请选中一条数据");
                }
            }
        }],
        // 扩展右键菜单
        menusEx: [{
            text: '查看合同',
            icon: 'view',
            action: function(row) {
                if (row) {
                    if (row.ExaStatus == '待提交' || row.ExaStatus == '部门审批') {
                        if (row.ExaStatus == '部门审批' && row.closeReason != '') {
                            showModalWin("?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_, 1, row.id);
                        } else {
                            showModalWin("?model=contract_other_other&action=viewAlong&id=" + row.id + '&skey=' + row.skey_, 1, row.id);
                        }
                    } else {
                        showModalWin("?model=contract_other_other&action=viewTab&id=" + row.id + '&skey=' + row.skey_, 1, row.id);
                    }
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '提交审批',
            icon: 'add',
            showMenuFn: function(row) {
                return row.ExaStatus == "待提交" || row.ExaStatus == "打回";
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
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '撤销审批',
            icon: 'add',
            showMenuFn: function(row) {
                return row.ExaStatus == "部门审批";
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
                                alert('操作失败，原因：\n1.已撤销申请,不能重复撤销\n2.单据已经存在审批信息，不能撤销审批');
                                return false;
                            } else {
                                var url;
                                switch (msg) {
                                    case '其他合同审批' :
                                        url = 'controller/contract/other/ewf_index.php?actTo=delWork';
                                        break;
                                    case '其他合同立项付款申请' :
                                        url = 'controller/contract/other/ewf_forpayapply.php?actTo=delWork';
                                        break;
                                    case '其他合同关闭审批' :
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
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '录入不开票金额',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZA';
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
            text: '申请付款',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
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
                                alert('合同存在未处理完成的退款单，不能申请付款');
                                return false;
                            } else { // 如果可以继续申请
                                showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + row.id, 1, row.id);
                            }
                        }
                    });
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '录入发票',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.status == 3) {
                    return false;
                }
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB';
            },
            action: function(row) {
                if (row.orderMoney * 1 <= accAdd(row.invotherMoney, row.returnMoney, 2) * 1) {
                    alert('合同可录入发票额已满');
                    return false;
                }
                showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD02&objId=" + row.id, 1, row.id);
            }
        // }, {
        //     name: 'stamp',
        //     text: '申请盖章',
        //     icon: 'add',
        //     showMenuFn: function(row) {
        //         if (row.status == 3) {
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
        //     action: function(row) {
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
            name: 'file',
            text: '上传附件',
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
            text: '变更合同',
            icon: 'edit',
            showMenuFn: function(row) {
                return row.status == 2 && row.ExaStatus == '完成';
            },
            action: function(row) {
                showModalWin("?model=contract_other_other&action=toChange&id="
                + row.id
                + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            name: 'copy',
            text: '复制合同',
            icon: 'add',
//            showMenuFn: function(row) {
//                return row.status == 2 && row.ExaStatus == '完成';
//            },
            action: function(row) {
                showModalWin("?model=contract_other_other&action=toCopyAdd&id="
                    + row.id
                    + "&skey=" + row.skey_, 1, row.id);
            }
        },  {
            name: 'change',
            text: '修改分摊明细',
            icon: 'edit',
            showMenuFn: function(row) {
                return row.ExaStatus == "完成" && (row.fundType == 'KXXZB' || row.fundType == 'KXXZC')
                    && row.status == '2';
            },
            action: function(row) {
				showModalWin("?model=contract_other_other&action=toChangeCostShare&id="
                + row.id
                + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            text: '申请退款',
            icon: 'delete',
            showMenuFn: function(row) {
                return row.ExaStatus == "完成" && row.fundType == 'KXXZB' && row.status == '2';
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
                                alert('合同存在未处理完成的付款申请，不能申请退款');
                                return false;
                            } else if (data * 1 == '0') {
                                alert('合同无已付款项，不能申请退款');
                                return false;
                            } else if (data * 1 == -1) {
                                alert('合同退款申请金额已满，不能继续申请');
                                return false;
                            } else {
                                showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&payFor=FKLX-03&objType=YFRK-02&objId=" + row.id, 1, row.id);
                            }
                        }
                    });
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '关闭合同',
            icon: 'delete',
            showMenuFn: function(row) {
                return (row.ExaStatus == "完成" || row.ExaStatus == "打回") && row.status == "2";
            },
            action: function(row) {
                if (row) {
                    showThickboxWin("?model=contract_other_other&action=toClose&id="
                    + row.id
                    + "&skey=" + row.skey_
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '删除',
            icon: 'delete',
            showMenuFn: function(row) {
                return row.ExaStatus == "待提交" || row.ExaStatus == "打回";
            },
            action: function(rowData, rows, rowIds, g) {
                g.options.toDelConfig.toDelFn(g.options, g);
            }
        }],
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
        },{
            text: '合同状态',
            key: 'status',
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
            }, {
                text: '关闭审批中',
                value: '5'
            }]
        }]
    });
});