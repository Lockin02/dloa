//初始化金额设置
$(function() {
    var chinseMoneyObj = $("#chinseMoney");
    if (chinseMoneyObj.length != 0) {
        chinseMoneyObj.html(toChinseMoney($("#payMoney").val() * 1));
    }

    var chinseMoneyPayedObj = $("#chinseMoneyPayed");
    if (chinseMoneyPayedObj.length != 0) {
        chinseMoneyPayedObj.html(toChinseMoney($("#payedMoney").val() * 1));
    }

    //提前付款值初始化
    var isAdvPayObj = $("#isAdvPay");
    if (isAdvPayObj.length != 0) {
        if (isAdvPayObj.val() == 1) {
            $(".isAdvPayShow").show();
            $("#payDate").attr('disabled', false);
        }
    }

    //初始化结算方式
    changePayTypeFun();

    //初始化关闭信息
    var statusObj = $("#status");
    if (statusObj.length != 0) {
        if (statusObj.val() == 'FKSQD-04') {
            $(".closeInfo").show();
        }
    }

    //银行初始化
    var bankObj = $("#bank");
    if (bankObj.val() == "") {
        bankObj.attr('readonly', false);
        bankObj.attr('class', 'txt');
    }

    //银行账号初始化
    var accountObj = $("#account");
    if (accountObj.val() == "") {
        accountObj.attr('readonly', false);
        accountObj.attr('class', 'txt');
    }

    //币种初始化
//    var currencyCodeObj = $("#currencyCode");
//    if (currencyCodeObj.length > 0) {
//        // 金额币别
//        $("#currency").yxcombogrid_currency({
//            hiddenId: 'currencyCode',
//            valueCol: 'currencyCode',
//            isFocusoutCheck: false,
//            gridOptions: {
//                showcheckbox: false,
//                event: {
//                    'row_dblclick': function(e, row, data) {
//                        $("#rate").val(data.rate);
//                    }
//                }
//            }
//        });
//    }
    $("#businessImg").click();
});

//结算方式
function changePayTypeFun() {
    var innerPayType = $("#payType").find("option:selected").attr("e1");
    if (innerPayType == 1) {
        $("#bankNeed").show();
        $("#accountNeed").show();
    } else {
        $("#bankNeed").hide();
        $("#accountNeed").hide();
    }
}


//删除行操作方法
function mydel(obj, mytable) {
    if (confirm('确定要删除该行？')) {
        var rowNo = obj.parentNode.parentNode.rowIndex * 1;
        var mytable = document.getElementById(mytable);
        mytable.deleteRow(rowNo - 2);
        //重新对行号赋值
        $.each($("tbody#invbody tr td:nth-child(2)"), function(i, n) {
            $(this).html(i + 1);
        });
    }
    countAll();
}

/**
 * 开票历史
 * @param thisVal
 * 1.开票历史
 */
function clickFun() {
    var url = '?model=supplierManage_formal_flibrary&action=supplierInfo'
            + '&id=' + $("#supplierId").val()
            + '&skey=' + $("#supplierSkey").val()
        ;
    showOpenWin(url);
}

//源单编号
function openContract(contractId, contractType) {
    switch (contractType) {
        case '外包合同':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=contract_outsourcing_outsourcing&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + contractId + "&skey=" + skey, 1);
            break;

        case '其他合同':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=contract_other_other&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=contract_other_other&action=viewTab&fundType=KXXZB&id=" + contractId + "&skey=" + skey, 1);
            break;

        case '采购订单':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=purchase_contract_purchasecontract&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=purchase_contract_purchasecontract&action=toTabRead&id=" + contractId + "&skey=" + skey, 1);
            break;

        case '租车合同':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=outsourcing_contract_rentcar&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + contractId + "&skey=" + skey, 1);
            break;
        default :
            alert('该类型暂不支持查看功能');
    }
}

//打开付款申请相关 项目
function openObject(projectId, projectType) {
    switch (projectType) {
        case '工程项目' :
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=engineering_project_esmproject&action=md5RowAjax",
                data: {"id": projectId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId + "&skey=" + skey, 1);
            break;
        default :
            alert('该类型暂不支持查看功能');
    }
}


//新增 - 提交审批
function audit(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=add&act=audit";
    } else {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=add";
    }
}

//编辑页 - 提交审批
function auditEdit(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=edit&act=audit";
    } else {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=edit";
    }
}


//收起 - 隐藏
function showAndHide(btnId, tblId) {
    //缓存表格对象
    var tblObj = $("table[id^='" + tblId + "']");
    //如果表格当前是隐藏状态，则显示
    if (tblObj.is(":hidden")) {
        tblObj.show();
        $("#" + btnId).attr("src", "images/icon/info_up.gif");
    } else {
        tblObj.hide();
        $("#" + btnId).attr("src", "images/icon/info_right.gif");
    }
}

//选择银行代扣后触发事件
function entrustFun(thisVal) {
    if (thisVal == '1') {
        if (confirm('选择已付款后，不再由出纳进行款项支付，确认选择吗？')) {
            $("#bank").val('已付款').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
            $("#account").val('已付款').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
        } else {
            $("#isEntrustNo").attr('checked', true);
            $("#bank").val('').attr('class', 'txt').attr('readonly', false);
            $("#account").val('').attr('class', 'txt').attr('readonly', false);
        }
    } else {
        $("#bank").val('').attr('class', 'txt').attr('readonly', false);
        $("#account").val('').attr('class', 'txt').attr('readonly', false);
    }
}

// 修改审批付款日期
function updateAuditDate() {
    if (confirm("确定要修改审批付款日期吗?")) {
        $.ajax({
            type: "POST",
            url: "?model=finance_payablesapply_payablesapply&action=updateAuditDate",
            data: {
                id: $("#payablesapplyId").val(),
                auditDate: $("#auditDate").val()
            },
            success: function(msg) {
                if (msg) {
                    $("#tempAuditDate").val($("#auditDate").val());
                    alert('修改成功！');
                } else {
                    alert("修改失败！")
                }
            }
        });
    } else {
        $("#auditDate").val($("#tempAuditDate").val());
    }
}