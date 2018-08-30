var show_page = function () {
    $("#costshareGrid").yxgrid("reload");
};

$(function () {
    // 获取账期信息
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
        	costTypeNameNo: '投标保证金',
        	shareObjTypeNo: 'FTDXLX-05',
			feeManId: $('#userId').val(),
			salesAreaId: $('#areaId').val(),
			auditDateYear: $('#year').val()
        },
        title: '分摊明细列表',
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        customCode: 'costShareGrid',
        showcheckbox : false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'auditStatus',
            display: '审核',
            width: 25,
            align: 'center',
            process: function (v, row) {
                switch (v) {
                    case '1' :
                        return '<img title="审核人[' + row.auditor + ']\n审核日期[' + row.auditDate
                            + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    case '3' :
                        return '<img title="撤回" src="images/icon/ok1.png" style="width:15px;height:15px;">';
                    default :
                }
            }
        }, {
            name: 'moduleName',
            display: '所属板块',
            sortable: true,
            width: 60
        }, {
            name: 'companyName',
            display: '公司主体',
            sortable: true,
            width: 60
        }, {
            name: 'belongCompanyName',
            display: '归属公司',
            sortable: true,
            width: 60
        }, {
            name: 'objId',
            display: '源单id',
            sortable: true,
            hide: true
        }, {
            name: 'objType',
            display: '源单类型',
            sortable: true,
            width: 60,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '赔偿单';
                    case '2' :
                        return '其他合同';
                    default :
                        return v;
                }
            }
        }, {
            name: 'objCode',
            display: '源单编号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (v != "") {
                    return "<a href='javascript:void(0)' onclick='viewInfo(\"" + row.objId + "\",\"" + row.objType + "\")'>" + v + "</a>";
                }
            }
        }, {
            name: 'supplierName',
            display: '供应商',
            sortable: true,
            width: 120
        }, {
            name: 'feeMan',
            display: '费用承担人',
            sortable: true,
            width: 80
        }, {
            name: 'salesArea',
            display: '销售区域',
            sortable: true,
            width: 80
        }, {
            name: 'inPeriod',
            display: '入账期间',
            sortable: true,
            width: 60
        }, {
            name: 'belongPeriod',
            display: '归属期间',
            sortable: true,
            width: 60
        }, {
            name: 'detailType',
            display: '业务类型',
            sortable: true,
            width: 80,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '部门费用';
                    case '2' :
                        return '合同项目费用';
                    case '3' :
                        return '研发项目费用';
                    case '4' :
                        return '售前费用';
                    case '5' :
                        return '售后费用';
                    default :
                        return v;
                }
            }
        }, {
            name: 'headDeptName',
            display: '二级部门',
            sortable: true,
            width: 80
        }, {
            name: 'belongDeptName',
            display: '归属部门',
            sortable: true,
            width: 80
        }, {
            name: 'chanceCode',
            display: '商机编号',
            sortable: true
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true,
            width: 150
        }, {
            name: 'customerType',
            display: '客户类型',
            sortable: true
        }, {
            name: 'province',
            display: '所属省份',
            sortable: true,
            width: 70
        }, {
            name: 'parentTypeId',
            display: 'parentTypeId',
            hide: true
        }, {
            name: 'parentTypeName',
            display: '费用明细上级',
            hide: true
        }, {
            name: 'costTypeId',
            display: 'costTypeId',
            hide: true
        }, {
            name: 'costTypeName',
            display: '费用明细',
            width: 100
        }, {
            name: 'costMoney',
            display: '分摊金额',
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
            display: '累计勾稽金额',
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
            display: '本月勾稽金额',
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
            display: '未勾稽金额',
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
            display: '勾稽状态',
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '未勾稽';
                    case '1' :
                        return '已勾稽';
                    case '2' :
                        return '部分勾稽';
                    default :
                        return v;
                }
            },
            width: 60
        }],
//        menusEx: [{
//            text: '勾稽记录',
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
//            text: "快速审核",
//            icon: "edit",
//            action: function (row, rows) {
//                if (row) {
//                    var canAuditArr = [];
//
//                    for (var i = 0; i < rows.length; i++) {
//                        if (checkPeriod(rows[i].inPeriod) == false) {
//                            alert('含有小于当前财务周期的数据，不能进行审核操作');
//                            return false;
//                        }
//
//                        if (rows[i].auditStatus == '2') {
//                            canAuditArr.push(rows[i].id);
//                        }
//                    }
//
//                    if (canAuditArr.length > 0) {
//                        if (confirm('确认将选中的记录审核吗？')) {
//                            $.ajax({
//                                url: "?model=finance_cost_costshare&action=quickAudit",
//                                data: {ids: canAuditArr.toString()},
//                                type: 'POST',
//                                success: function (data) {
//                                    if (data == "1") {
//                                        alert('审核成功');
//                                        show_page();
//                                    } else {
//                                        alert('审核失败');
//                                    }
//                                }
//                            });
//                        }
//                    } else {
//                        alert('选中记录中没有可审核的记录');
//                    }
//                } else {
//                    alert('请选择一条记录');
//                }
//            }
//        }, {
//            text: "审核",
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
//                                1, 700, 1100, '审核分摊记录');
//                            return false;
//                        }
//                    }
//                }
//                // 检测是否有需要审核的分摊记录
//                $.ajax({
//                    url: "?model=finance_cost_costshare&action=getWaitInfo",
//                    type: "POST",
//                    success: function (data) {
//                        if (data == "0") {
//                            alert('没有需要审核的单据');
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
//                                1, 700, 1100, '审核分摊记录');
//                        }
//                    }
//                });
//            }
//        }, {
//            text: "反审核",
//            icon: "delete",
//            action: function (row, rows) {
//                if (row) {
//                    var canAuditArr = [];
//                    for (var i = 0; i < rows.length; i++) {
//                        if (rows[i].hookStatus != 0) {
//                            alert('不能对已进行过勾稽操作的数据做反审核操作');
//                            return false;
//                        }
//
//                        if (checkPeriod(rows[i].inPeriod) == false) {
//                            alert('含有小于当前财务周期的数据，不能进行反审核操作');
//                            return false;
//                        }
//
//                        if (rows[i].auditStatus == '1') {
//                            canAuditArr.push(rows[i].id);
//                        }
//                    }
//
//                    if (canAuditArr.length > 0) {
//                        if (confirm('确认将选中的记录反审核吗？')) {
//                            $.ajax({
//                                url: "?model=finance_cost_costshare&action=unAudit",
//                                data: {ids: canAuditArr.toString()},
//                                type: 'POST',
//                                success: function (data) {
//                                    if (data == "1") {
//                                        alert('反审核成功');
//                                        show_page();
//                                    } else {
//                                        alert('反审核失败');
//                                    }
//                                }
//                            });
//                        }
//                    } else {
//                        alert('选中记录中没有可反审核的记录');
//                    }
//                } else {
//                    alert('请选择一条记录');
//                }
//            }
//        }, {
//            text: "撤回",
//            icon: "delete",
//            action: function (row, rows) {
//                if (row) {
//                    var canBackArr = [];
//                    for (var i = 0; i < rows.length; i++) {
//                        if (rows[i].hookStatus != '0') {
//                            alert('不能对已进行过勾稽操作的数据做反审核操作');
//                            return false;
//                        }
//
//                        if (checkPeriod(rows[i].inPeriod) == false) {
//                            alert('含有小于当前财务周期的数据，不能进行撤回操作');
//                            return false;
//                        }
//
//                        if (rows[i].auditStatus == '2') {
//                            canBackArr.push(rows[i].id);
//                        }
//                    }
//
//                    if (canBackArr.length > 0) {
//                        if (confirm('确认将选中的记录撤回吗？')) {
//                            $.ajax({
//                                url: "?model=finance_cost_costshare&action=quickBack",
//                                data: {ids: canBackArr.toString()},
//                                type: 'POST',
//                                success: function (data) {
//                                    if (data == "1") {
//                                        alert('撤回成功');
//                                        show_page();
//                                    } else {
//                                        alert('撤回失败');
//                                    }
//                                }
//                            });
//                        }
//                    } else {
//                        alert('选中记录中没有可撤回的记录');
//                    }
//                } else {
//                    alert('请选择一条记录');
//                }
//            }
//        }, {
//            text: "导出",
//            icon: 'excel',
//            action: function () {
//                window.open(
//                    '?model=finance_cost_costshare&action=exportExcel&periodNo=' + $("#periodNo").val() +
//                    '&auditStatusNo=0&auditStatusArr=' + $("#auditStatusArr").val() +
//                    '&hookStatusArr=' + $("#hookStatusArr").val() +
//                    '&inPeriod=' + $("#inPeriod").val()
//                    ,
//                    '其他发票导出',
//                    'width=200,height=200,top=200,left=200'
//                );
//            }
//        }],
        //过滤数据
        comboEx: [
//        {
//            text: '审核',
//            key: 'auditStatusArr',
//            value: '1,2',
//            data: [{
//                text: '未审核',
//                value: '2'
//            }, {
//                text: '已审核',
//                value: '1'
//            }, {
//                text: '撤回',
//                value: '3'
//            }, {
//                text: '未审、已审',
//                value: '1,2'
//            }]
//        }, {
//            text: '勾稽',
//            key: 'hookStatusArr',
//            value: '0,2',
//            data: [{
//                text: '未勾稽',
//                value: '0'
//            }, {
//                text: '已勾稽',
//                value: '1'
//            }, {
//                text: '部分勾稽',
//                value: '2'
//            }, {
//                text: '未完成',
//                value: '0,2'
//            }]
//        }, {
//            text: '财务期',
//            key: 'periodNo',
//            value: periodDefault,
//            data: periodArr
//        }, 
        {
            text: '入账期间',
            key: 'inPeriod',
            data: periodArr
        }],
        searchitems: [{
            display: "源单编号",
            name: 'objCodeSearch'
        }, {
            display: "商机编号",
            name: 'chanceCodeSearch'
        }, {
            display: "项目编号",
            name: 'projectCodeSearch'
        }, {
            display: "合同编号",
            name: 'contractCodeSearch'
        }],
        sortorder: 'objId'
    });
});

// 单据查看
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

// 验证财务周期
var periodYear;
var periodMonth;
function checkPeriod(period) {
    var periodArr = period.split('.');
    var thisPeriodYear = periodArr[0];
    var thisPeriodMonth = periodArr[1];

    // 如果未定义财务周期，先获取
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