var show_page = function () {
    $("#otherGrid").yxgrid("reload");
};

$(function () {

    $("#otherGrid").yxgrid({
        model: 'contract_other_other',
        action: 'pageJsonInfo',
        param : {"fundType":'KXXZB',"payForBusinessArr":"FKYWLX-04,FKYWLX-03,FKYWLX-06,FKYWLX-07","ids":$("#ids").val()},
        title: '其他合同',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        customCode: 'otherGrid',
        isOpButton: false,
        showcheckbox : false,
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
            display: '欠票金额',
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
            name: 'payForBusinessName',
            display: '付款业务类型',
            sortable: true,
            process: function (v, row) {
                return (v == "NULL") ? "" : v;
            }
        },{
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
        }, {
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
        sortorder: "DESC"
    });
});