$(function () {
    var contractId = $("#contractId").val();
    $.ajax({
        type: "POST",
        url: "index1.php?model=contract_contract_contract&action=getContractTracks&contractId="
        + contractId,
        success: function (data) {
            var data = eval(data);
            var f = function (v) {
                if (typeof(v) == "undefined") {
                    return "";
                } else if (v == 1) {
                    v = "<img src='images/icon/icon088.gif'>"
                }
                return v;
            };
            for (var i = 0; i < data.length; i++) {
                var d = data[i];

                // 历史进度记录显示
                var historyExePortion = d.exePortion;
                var historyExePortionHtml = formatProgress(historyExePortion, null);

                var str = "<tr>"
                    + "<td>" + f(d.time) + "</td>"
                    + "<td class='historyExePortion' data-exePortion='" + d.exePortion + "'>" + historyExePortionHtml + "</td>"
                    + "<td>" + f(d.contractBegin) + "</td><td>" + f(d.completeDate)
                    + "</td><td>" + f(moneyFormat2(d.invoiceMoney)) + "</td><td>"
                    + f(d.invoiceComplete) + "</td><td>" + f(moneyFormat2(d.incomeMoney))
                    + "</td><td>" + f(d.incomeComplete) + "</td><td>"
                    + f(d.signinDate) + "</td><td>" + f(d.closeTime)
                    + "</td></tr>";
                $("#trackTable").append(str);
            }
        }
    });
    var curIncomeAll = 0;
    var grossTrueAll = 0;
    var cfeeAll = 0;
    var conIncom = 0;//合同营收
    var conCost = 0;//合同成本
    var conGross = 0;//合同毛利
    var projectMoneyAll = 0;//项目合同额
    var notProject = true;// 无关联项目标示

    //项目情况
    $.ajax({
        type: "POST",
        url: "index1.php?model=engineering_project_esmproject&action=pageJson",
        data: {"contractCodeSearch": $("#contractCode").val()},
        async: false,
        success: function (data) {
            data = eval("(" + data + ")");
            var data = data.collection;
            var proHtml = "<tr>" +
                "<td>" + $("#contractCode").val() + "</td>" +
                "<td></td>" +
                "<td></td>" +
                "<td></td>" +
                "<td></td>" +
                "<td></td>" +
                "<td></td>" +
                "<td style='text-align:right'>" + $("#conFee").val() + "</td>" +
                "<td></td>" +
                "<td></td>" +
                "<td></td>" +
                "</tr>";
            for (var i = 0; i < data.length; i++) {
                //补全项目信息
                var d = data[i];
                projectMoneyAll += d.projectMoneyWithTax * 1;
                if (typeof(d.projectMoney) == 'undefined') {
                    d.projectMoney = '0.00';
                } else {
                    d.projectMoney = moneyFormat2(d.projectMoney);
                }
                if (typeof(d.projectRate) == 'undefined') {
                    d.projectRate = '0.00';
                } else {
                    d.projectRate = d.projectRate + "%";
                }
                if (typeof(d.grossProfit) == 'undefined') {
                    d.grossProfit = '0.00';
                } else {
                    var grossProfitNum = d.grossProfit;
                    d.grossProfit = moneyFormat2(d.grossProfit);
                }
                if (typeof(d.budgetExgross) == 'undefined') {
                    d.budgetExgross = '0.00';
                } else {
                    d.budgetExgross = d.budgetExgross + "%";
                }
                if (typeof(d.exgross) == "undefined") {
                    d.exgross = '0.00';
                } else {
                    d.exgross = d.exgross + "%";
                }
                conIncom += d.curIncome * 1;//合同营收 - 由项目累加
                conCost += d.feeAll * 1;//合同成本 - 由项目累加
                conGross += grossProfitNum * 1;//合同毛利 - 由项目累加

                proHtml += "<tr>" +
                    "<td style='color:blue;cursor:pointer;' onclick='toConView(\"" + d.pType + "\",\"" + d.id + "\",\"" + d.skey_ + "\")'>" + d.projectCode + "</td>" +
                    "<td style='text-align:right'>" + d.newProLineName + "</td>" +
                    "<td style='text-align:right'>" + d.projectMoney + "</td>" +
                    "<td style='text-align:right'>" + d.projectRate + "</td>" +
                    "<td style='text-align:right'>" + d.statusName + "</td>" +
                    "<td style='text-align:right'>" + d.projectProcess + "%</td>" +
                    "<td style='text-align:right'>" + moneyFormat2(d.curIncome) + "</td>" +
                    "<td style='text-align:right'>" + moneyFormat2(d.feeAll) + "</td>" +
                    "<td style='text-align:right'>" + d.grossProfit + "</td>" +
                    "<td style='text-align:right'>" + d.budgetExgross + "</td>" +
                    "<td style='text-align:right'>" + d.exgross + "</td>" +
                    "</tr>";
            }
            $("#conIncom").append(moneyFormat2(conIncom));
            $("#conCost").append(moneyFormat2(conCost));
            if (isNaN(conGross)) {
                conGross = 0;
            }
            $("#conGross").append(moneyFormat2(conGross));
            $("#proList").append(proHtml);

            // PMS 522 对于合同无关联项目的，无关联项目时，进度取开票和收款进度的最小值。
            if(data.length > 0){
                notProject = false;
            }
        }
    });

    //执行进度
    // var exePortion = conIncom / ($("#contractMoneyRate").val() - $("#deductMoney").val() / (1 + $("#cRateNum").val() * 1) - $("#uninvoiceMoney").val() / (1 + $("#cRateNum").val() * 1)) * 100;
    var exePortion = $.ajax({
        url: '?model=contract_contract_contract&action=getExePortion',
        data: 'contractId=' + contractId +'&contractMoney='+ ($("#contractMoney").val() * 1 ),
        dataType: 'html',
        type: 'get',
        async: false
    }).responseText;
    exePortion = Number(exePortion);

    //开票进度
    var invoiceMoney = $("#invoiceMoney").val();
    var invoicePortion = invoiceMoney / ($("#contractMoney").val() * 1 - $("#deductMoney").val() * 1 - $("#uninvoiceMoney").val() * 1) * 100;
    invoicePortion = (Number(invoicePortion) > 0)? Number(invoicePortion) : 0;
    invoiceMoney = moneyFormat2(invoiceMoney);
    if($("#isNoInvoiceCont").val() === '1'){
        invoicePortion = 100;
        invoiceMoney = false;
    }
    var invoiceTtml = formatProgress(invoicePortion.toFixed(2), invoiceMoney);
    $("#invoice").html(invoiceTtml);
    //到款进度
    var incomeMoney = $("#incomeMoney").val();
    var incomePortion = incomeMoney / ($("#contractMoney").val() * 1 - $("#deductMoney").val() * 1 - $("#badMoney").val() * 1) * 100;
    incomePortion = (Number(incomePortion) > 0)? Number(incomePortion) : 0;
    var incomeTtml = formatProgress(incomePortion.toFixed(2), moneyFormat2(incomeMoney));
    $("#income").html(incomeTtml);

    // 合同执行进度
    var exePortionVal = exePortion.toFixed(2);
    if(notProject){// PMS 522 对于合同无关联项目的，无关联项目时，进度取开票和收款进度的最小值。
        var incomePortionVal = Number(incomePortion.toFixed(2));
        var invoicePortionVal = Number(invoicePortion.toFixed(2));
        if(incomePortionVal < invoicePortionVal){
            exePortionVal = incomePortion.toFixed(2)
        }else{
            exePortionVal = invoicePortion.toFixed(2)
        }
    }
    var exePortionTtml = formatProgress(exePortionVal, null);
    $("#exePortionView").html(exePortionTtml);

    //合同应收
    var thysTmp = $("#contractMoney").val() * exePortionVal / 100 - incomeMoney - $("#deductMoney").val() * 1 - $("#badMoney").val() * 1;
    thysTmp = ($("#isNoSurincomeMoney").val() == 'y')? moneyFormat2(0) : moneyFormat2(thysTmp.toFixed(2));
    $("#htys").html(thysTmp);
    //毛利率 税后
    var mllshTmp = conGross / conIncom * 100
    if (isNaN(mllshTmp)) {
        mllshTmp = 0;
    }
    $("#mllsh").html(mllshTmp.toFixed(2) + "%");
    if ($("#conState").html() == '执行中' || $("#conState").html() == '已完成' || $("#conState").html() == '已关闭') {
        var diffNum = Number($("#contractMoney").val()) - Number(projectMoneyAll);
        console.log(Number(projectMoneyAll) + " => " + diffNum);
        var createDate = $("#ExaDTOne").val();
        var createDateArr = createDate.split("-");
        if(Number(createDateArr[0]) < 2015){// PMS 730 合同建立日期为2015-01-01前的合同，直接过滤，视为正确。
            $("#proCheck").html("<img src='images/icon/dui.png'/>");
        }else{
            if (diffNum > 0.1 || diffNum < -0.1) {
                $("#proCheck").html("<img src='images/icon/cuo.png'/>");
            } else {
                $("#proCheck").html("<img src='images/icon/dui.png'/>");
            }
        }
    } else {
        $("#proCheck").html("<img src='images/icon/heng.png'/>");
    }


});
//用于列表进度显示
function formatProgress(value, money) {
    if (value) {
        if (money) {
            var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">' +
                '<div style="width:' + value + '%;background:#66FF66;white-space:nowrap;padding: 0px;">' + value + '%  金额：' + money + '' + '</div>'
            '</div>';
            return s;
        } else {
            var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">' +
                '<div style="width:' + value + '%;background:#66FF66;white-space:nowrap;padding: 0px;">' + value + '%' + '</div>'
            '</div>';
            return s;
        }

    } else {
        return '';
    }
}

function toConView(type, id, skey_) {
    if (type == 'esm') {
        showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + id + "&skey=" + skey_, 1, id);
    } else {
        var cid = id.substring(1);
        showModalWin("?model=contract_conproject_conproject&action=viewTab&id=" + cid, 1, id);
    }
}