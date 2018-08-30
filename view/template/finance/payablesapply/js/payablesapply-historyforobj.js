var show_page = function() {
    $("#payablesapplyGrid").yxgrid("reload");
};

$(function() {
    var thisId = $("#thisId").val();

    var colModel = [{
        display: '付款单号',
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
        display: '申请单编号',
        sortable: true,
        width: 130,
        process: function(v, row) {
            if (thisId != row.id) {
                return v;
            } else {
                return "<span style='color: blue' title='当前申请单'>" + v + "</span>";
            }
        }
    }, {
        name: 'formDate',
        display: '单据日期',
        sortable: true,
        hide: true,
        width: 70
    }];

    var objType = $("#objType").val();
    if (objType == 'YFRK-06') {
        colModel.push({
            name: 'period',
            display: '归属月份',
            sortable: true,
            width: 60
        });
    }

    // 继续填充
    colModel.push({
        name: 'payDate',
        display: '期望付款日期',
        sortable: true,
        width: 70
    }, {
        name: 'actPayDate',
        display: '实际付款日期',
        sortable: true,
        width: 70
    }, {
        name: 'payFor',
        display: '申请类型',
        sortable: true,
        datacode: 'FKLX',
        width: 60
    }, {
        name: 'supplierName',
        display: '供应商名称',
        sortable: true,
        width: 160
    }, {
        name: 'payMoney',
        display: '申请金额',
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
        display: '已付金额',
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
        display: '状态',
        sortable: true,
        datacode: 'FKSQD',
        width: 60
    }, {
        name: 'ExaStatus',
        display: '审批状态',
        sortable: true,
        width: 60
    }, {
        name: 'ExaDT',
        display: '审批时间',
        sortable: true,
        width: 80
    }, {
        name: 'deptName',
        display: '申请部门',
        sortable: true,
        hide: true,
        width: 80
    }, {
        name: 'salesman',
        display: '申请人',
        sortable: true,
        width: 80
    }, {
        name: 'createName',
        display: '创建人',
        sortable: true,
        hide: true,
        width: 80
    }, {
        name: 'createTime',
        display: '创建日期',
        sortable: true,
        width: 120,
        hide: true
    });

    $("#payablesapplyGrid").yxgrid({
        model: 'finance_payablesapply_payablesapply',
        action: 'historyJson',
        param: {dObjId: $("#objId").val(), dObjIds: $("#objIds").val(), dObjType: objType},
        title: '付款申请历史',
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        isOpButton: false,
        //列信息
        colModel: colModel,
        menusEx: [
            {
                text: '查看',
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
                text: '提交财务支付',
                icon: 'edit',
                showMenuFn: function(row) {
                    return row.status == 'FKSQD-00' && row.ExaStatus == '完成' && row.createId == $("#userId").val();
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
                        //     alert('距离期望付款日期还有 ' + s + " 天，暂不能提交财务支付");
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
                text: '上传附件',
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
                text: '打印',
                icon: 'print',
                showMenuFn: function(row) {
                    return row.status != 'FKSQD-04' && row.createId == $("#userId").val();
                },
                action: function(row) {
                    showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'], 1);
                }
            }, {
                text: '关闭',
                icon: 'delete',
                showMenuFn: function(row) {
                    return row.ExaStatus == '完成' && (row.status == 'FKSQD-01' || row.status == 'FKSQD-00') && row.createId == $("#userId").val();
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