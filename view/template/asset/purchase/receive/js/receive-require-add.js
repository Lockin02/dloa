$(function () {
    $("#receiveTable").yxeditgrid({
        objName: 'receive[receiveItem]',
        title: '验收明细',
        isAdd: false,
        url: '?model=asset_purchase_receive_receiveItem&action=getApplyItemPage',
        param: {
            "applyId": $("#applyId").val(),
            "isDel": '0'
        },
        event: {
            reloadData: function (e, g, data) {
                var isCanAdd = false;
                if (data) {
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].checkAmount > 0) {
                            isCanAdd = true;
                        }
                    }
                }
                if (!isCanAdd) {
                    alert("该采购需求的物料已经全部验收.")
                    self.parent.tb_remove();
                }
                check_all();
            },
            removeRow: function (t, rowNum, rowData) {
                check_all();
            }
        },
        colModel: [{
            display: '采购申请id',
            name: 'applyId',
            type: 'hidden'
        }, {
            display: '采购申请编码',
            name: 'applyCode',
            type: 'hidden'
        }, {
            display: '采购申请明细表id',
            name: 'applyEquId',
            type: 'hidden'
        }, {
            display: '资产名称',
            name: 'assetName',
            tclass: 'readOnlyTxtItem',
            readonly: true,
            process: function ($input, row) {
                if (row.productName == '') {
                    $input.val(row.inputProductName);
                } else {
                    $input.val(row.productName);
                }
            }
        }, {
            display: '规格',
            name: 'spec',
            tclass: 'readOnlyTxtItem',
            readonly: true,
            process: function ($input, row) {
                $input.val(row.pattem);
            }
        }, {
            display: '最大数量',
            name: 'maxNum',
            process: function ($input, row) {
                $input.val(row.checkAmount);
            },
            type: 'hidden'
        }, {
            display: '数量',
            name: 'checkAmount',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    var rownum = $(this).data('rowNum');// 第几行
                    var grid = $(this).data('grid');// 表格组件
                    //验证数量是否合法
                    var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
                    var checkAmount = $(this).val();
                    if (checkAmount != "" && !isNum(checkAmount)) {
                        alert("数量请填正整数");
                        $(this).val(maxNum).focus();
                        return false;
                    } else if (accSub(checkAmount, maxNum) > 0) {
                        alert("验收数量不能大于采购需求数量【" + maxNum + "】");
                        $(this).val(maxNum).focus();
                        return false;
                    }

                    var price = grid.getCmpByRowAndCol(rownum, 'price').val();
                    var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
                    var checkAmount = $(this).val();
                    var amountId = $amount.attr('id').replace('_v', '');
                    var amount = accMul(price, checkAmount);
                    setMoney(amountId, amount);
                    check_all();
                }
            },
            validation: {
                custom: ['onlyNumber']
            }
        }, {
            display: '单价',
            name: 'price',
            tclass: 'txtshort',
            type: 'money',
            event: {
                blur: function () {
                    var rownum = $(this).data('rowNum');// 第几行
                    var grid = $(this).data('grid');// 表格组件
                    var checkAmount = grid.getCmpByRowAndCol(rownum, 'checkAmount').val();
                    var price = $(this).val();
                    var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
                    var amountId = $amount.attr('id').replace('_v', '');
                    var amount = accMul(price, checkAmount);
                    setMoney(amountId, amount);
                    check_all();
                }
            }
        }, {
            display: '金额',
            name: 'amount',
            tclass: 'readOnlyTxtItem',
            type: 'money',
            readonly: true,
            process: function ($input, row) {
                $input.val(row.checkAmount * row.price);
            }
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt'
        }]
    });
    // 选择人员组件
    $("#salvage").yxselect_user({
        hiddenId: 'salvageId',
        isGetDept: [true, "deptId", "deptName"],
        event: {
            select: function (e, returnValue) {
                if (returnValue) {
                    $('#company').val(returnValue.companyCode)
                    $('#companyName').val(returnValue.companyName)
                }
            }
        }
    });

    /**
     * 验证信息
     */
    validate({
        "code": {
            required: true
        },
        "salvage": {
            required: true
        },
        "limitYears": {
            custom: ['date']
        },
        "amount": {
            required: true
        },
        "result": {
            required: true
        }
    });
});

// 根据从表的金额动态计算总金额
function check_all() {
    var rowAmountVa = 0;
    var cmps = $("#receiveTable").yxeditgrid("getCmpByCol", "amount");
    cmps.each(function () {
        rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
    });
    $("#amount").val(rowAmountVa);
    $("#amount_v").val(moneyFormat2(rowAmountVa));
    return false;
}

/*
 * 审核确认
 */
function confirmAudit() {
    if (confirm("你确定要提交审核吗?")) {
        $("#form1").attr("action", "?model=asset_purchase_receive_receive&action=add&actType=audit").submit();
    } else {
        return false;
    }
}

/**
 * 验证验收金额必填
 */
function checkform() {
    if ($("#amount").val() == "") {
        alert("填写好请验收金额！");
        return false;
    }
    return true;
}
