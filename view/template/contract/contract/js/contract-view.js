//2012-12-27备份
$(function () {

    // if( $("#paperContract").val()=='无'){
    //     $("#paperContractView").html("*此合同无纸质合同");
    // }else{
    //     $("#paperContractView").hide("");
    // }
    //
    // if( $("#checkFile").val()=='有'){
    //     $("#checkFileView").show();
    // }else{
    //     $("#checkFileView").hide();
    // }

    //详细物料成本权限控制
    var equCoseLimit = $("#equCoseLimit").val();
    if (equCoseLimit == '1') {
        $("#equCost").show();
    } else {
        $("#equCost").hide();
    }

    //判断预计毛利颜色
    var exgross = $("#exgrossDiv").html();
    var exgrossVal = $("#exgrossVal").val();
    if (exgross < exgrossVal) {
        $("#exgrossDiv").attr('style', "color:red");
    } else {
        $("#exgrossDiv").attr('style', "color:black");
    }
    // 合同金额
    var winRate = $("#winRate").html();
    if (winRate == '100%') {
        $("#contractMoney").html("签约金额");
        $("#contractMoneyA").html("签约金额");
        document.getElementById("signDateNone").style.display = "";
    } else {
        $("#contractMoney").html("预计金额");
        $("#contractMoneyA").html("预计金额");
        document.getElementById("signDateNone").style.display = "none";
    }
    //开票类型
    var invoiceLimitR = $("#invoiceLimitR").val();
    if (invoiceLimitR == '1') {

    } else {
        $("#invoiceLimit").html("******");
    }

    //新开票类型控制
    var dataCode = $("#dataCode").val();
    if (dataCode != '' && typeof(dataCode) != 'undefined') {
        var itemArr = dataCode.split(',');
        var itemLength = itemArr.length;
        for (i = 0; i < itemLength; i++) {
            if ($("#" + itemArr[i] + "V").val() == 1) {
                $("#" + itemArr[i]).before("√");
                $("#" + itemArr[i] + "Hide").show();
            } else {
                $("#" + itemArr[i]).before("×");
                $("#" + itemArr[i]).css("color", "#969696");
            }
        }
    }

    //合同付款条件
    var advance = $("#advance").html();
    if (advance != "") {
        $("#advance").html("<span style='color:#0080FF'>预付款</span> : "
            + advance + "%&nbsp&nbsp|&nbsp");
    }
    var delivery = $("#delivery").html();
    if (delivery != "") {
        $("#delivery").html("<span style='color:#0080FF'>货到付款</span> : "
            + delivery + "%&nbsp&nbsp|&nbsp");
    }
    var initialpayment = $("#initialpayment").html();
    if (initialpayment != "") {
        $("#initialpayment")
            .html("<span style='color:#0080FF'>初验通过付款</span> : "
                + initialpayment + "%&nbsp&nbsp|&nbsp");
    }
    var finalpayment = $("#finalpayment").html();
    if (finalpayment != "") {
        $("#finalpayment").html("<span style='color:#0080FF'>终验通过付款</span> : "
            + finalpayment + "%&nbsp&nbsp|&nbsp");
    }
    //按进度付款
    var progresspayment = $("#progresspayment").html();
    if (progresspayment != "") {
        $("#progresspayment")
            .html("<span style='color:#0080FF'>按进度付款</span> :");
        var progressArr = progresspayment.split(',');
        $.each(progressArr, function (i, n) {
            var str = '<tr>' + '<td id="progresspaymentterm' + i + '"></td>'
                + '<td>' + n + '%</td>' + '</tr>';
            $("#progresspayment").append(str);
        });
        var progresspaymentterm = $("#progresspaymentterm").val();
        var progresspaymenttermArr = progresspaymentterm.split(',');
        $.each(progresspaymenttermArr, function (i, n) {
            $("#progresspaymentterm" + i).html(n);
        });
    }
    //其他付款
    var otherpayment = $("#otherpayment").html();
    if (otherpayment != "") {
        $("#otherpayment").html("<span style='color:#0080FF'>其他付款条件</span> :");
        var otherpaymentArr = otherpayment.split(',');
        $.each(otherpaymentArr, function (i, n) {
            var str = '<tr>' + '<td id="otherpaymentterm' + i + '"></td>'
                + '<td>' + n + '%</td>' + '</tr>';
            $("#otherpayment").append(str);
        });
        var otherpaymentterm = $("#otherpaymentterm").val();
        var otherpaymenttermArr = otherpaymentterm.split(',');
        $.each(otherpaymenttermArr, function (i, n) {
            $("#otherpaymentterm" + i).html(n);
        });
    }
    //续签信息
    var isRenewed = strTrim($("#isRenewed").html());
    if(isRenewed == "续签合同"){
        $("#renewedCode").show();
        $("#renewedCodeV").show();
    }else{
        $("#renewedCode").hide();
        $("#renewedCodeV").hide();
    }
})

$(function () {
    proInfoList();

    // 收款计划
    $("#paymentListInfo").yxeditgrid({
        objName: 'contract[payment]',
        tableClass: 'form_in_table',
        url: '?model=contract_contract_receiptplan&action=listJson',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'isDel': 0,
            'isfinance' : 0
        },
        isAddOneRow: false,
        isAdd: false,
        colModel: [
            {
                display: '付款条件ID',
                name: 'paymenttermId',
                tclass: 'txt',
                type: 'hidden'
            },
            {
                display: '付款条件',
                name: 'paymentterm'

            },
            {
                display: '付款百分比（%）',
                name: 'paymentPer',
                tclass: 'txtshort'

            },
            {
                display: '计划付款金额',
                name: 'money',
                tclass: 'txtshort',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '计划付款日期',
                name: 'payDT',
                type: 'date'
            },
            {
                display: '备注',
                name: 'remark',
                tclass: 'txtlong'
            }
        ]
    });
    $("#linkmanListInfo").yxeditgrid({
        objName: 'contract[linkman]',
        url: '?model=contract_contract_linkman&action=listJsonLimit',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'prinvipalId': $("#prinvipalId").val(),
            'createId': $("#createId").val(),
            'areaPrincipalId': $("#areaPrincipalId").val(),
            'isTemp': '0',
            'isDel': '0'
        },
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '客户联系人',
                name: 'linkmanName',
                tclass: 'txt'
            },
            {
                display: '联系人ID',
                name: 'linkmanId',
                type: 'hidden'
            },
            {
                display: '电话',
                name: 'telephone',
                tclass: 'txt'
            },
            {
                display: '职位',
                name: 'position',
                tclass: 'txt'
            },
            {
                display: '邮箱',
                name: 'Email',
                tclass: 'txt'
            },
            {
                display: '备注',
                name: 'remark',
                tclass: 'txt',
                width: 500
            }
        ]
    });


    if ($("#isSubAppChange").val() == '1' || $("#isTemp").val() == '1') {//临时合同查看页面，要显示临时物料
        var param = {
            'contractId': $("#contractId").val(),
            'isDel': '0',
            'isBorrowToorder': '0'
        };
    } else {
        var param = {
            'contractId': $("#contractId").val(),
            'isTemp': '0',
            'isDel': '0',
            'isBorrowToorder': '0'
        };
    }
    //发货清单
    $("#equInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJsonGroup',
        type: 'view',
        tableClass: 'form_in_table',
        param: param,
        colModel: [
            {
                display: '所属产品',
                name: 'conProductName',
                tclass: 'txt'
            },
            {
                display: '物料编号',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '物料名称',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '物料Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '需求数量',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '已执行数量',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '已退库数量',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: '实际执行数量',
                name: 'actNum'
            },
            {
                display: '单价',
                name: 'price',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v, 6);
                }
            },
            {
                display: '金额',
                name: 'money',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '加密配置Id',
                name: 'license',
                type: 'hidden'
            },
            {
                name: 'licenseButton',
                display: '加密配置',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\"" + row.license
                            + "\")'>查看</a>";
                    }
                }
            }
        ]
    });
    //收开计划
    $("#financialplanInfo").yxeditgrid({
        objName: 'contract[financialplan]',
        url: '?model=contract_contract_financialplan&action=listJsonLimit',
        type: 'view',
        tableClass: 'form_in_table',
        param: {
            'contractId': $("#contractId").val()
        },
        colModel: [
            {
                display: '日期',
                name: 'planDate',
                type: 'date'
            },
            {
                display: '开票金额',
                name: 'invoiceMoney',
                tclass: 'txtshort',
                type: 'money'
            },
            {
                display: '收款金额',
                name: 'incomeMoney',
                tclass: 'txtshort',
                type: 'money'
            },
            {
                display: '备注',
                name: 'remark',
                tclass: 'txtlong'
            }
        ]
    });
    //借试用转销售
    $("#borrowConEquInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': '0',
            'isDel': '0',
            'isBorrowToorder': '1'
        },
        isAddOneRow: false,
        isAdd: false,
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '归属产品',
                name: 'conProduct',
                tclass: 'txt'
            },
            {
                display: '物料编号',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '物料名称',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '物料Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '需求数量',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '已执行数量',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '已退库数量',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: '实际执行数量',
                name: 'actNum'
            },
            {
                display: '单价',
                name: 'price',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '金额',
                name: 'money',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '加密配置Id',
                name: 'license',
                type: 'hidden'
            },
            {
                name: 'licenseButton',
                display: '加密配置',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\"" + row.license
                            + "\")'>查看</a>";
                    }
                }
            }
        ]

    });

});
//表单收缩
function hideList(listId) {
    var temp = document.getElementById(listId);
    var tempH = document.getElementById(listId + "H");
    if (temp.style.display == '') {
        temp.style.display = "none";
        if (tempH != null) {
            tempH.style.display = "";
        }
    } else if (temp.style.display == "none") {
        temp.style.display = '';
        if (tempH != null) {
            tempH.style.display = 'none';
        }
    }
}
$(function () {
    var currency = $("#currency").html();
    if (currency != '人民币' && currency != '') {
        document.getElementById("currencyRate").style.display = "";
    }
});
// 详细物料成本
function equCoseView() {
    showThickboxWin('?model=contract_contract_contract&action=equCoseView&contractId='
        + $("#pid").val()
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}


//产品清单
function proInfoList() {
    var currency = $("#currency").html();
    if (currency != '人民币') {
        var rate = $("#rate").html();
        //产品清单
        $("#productInfo").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_contract_product&action=listJsonLimit',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'contractId': $("#contractId").val(),
                'dir': 'ASC',
                'prinvipalId': $("#prinvipalId").val(),
                'createId': $("#createId").val(),
                'areaPrincipalId': $("#areaPrincipalId").val(),
                //			'isTemp' : '0',
                'isDel': '0'
            },
            colModel: [
                {
                    name: 'newProLineName',
                    display: '产品线',
                    width: 100
                },
                {
                    name: 'exeDeptName',
                    display: '执行区域',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '产品类型',
                    width: 80
                },
                {
                    display: '产品名称',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + $("#contractId").val()
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '产品Id',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '产品描述',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '数量',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '单价(' + currency + ')',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '单价',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v * rate);
                    }
                },
                {
                    display: '金额(' + currency + ')',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '金额',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v * rate);
                    }
                },
                {
                    display: '加密配置Id',
                    name: 'license',
                    type: 'hidden'
                },
                //{
                //    name: 'licenseButton',
                //    display: '加密配置',
                //    process: function (v, row) {
                //        if (row.license != "") {
                //            return "<a href='javascript:void(0)' onclick='showLicense(\""
                //                + row.license + "\")'>加密配置</a>";
                //        }
                //    }
                //},
                {
                    display: '产品配置Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    name: 'deployButton',
                    display: '产品配置',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>产品配置</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    } else {
        //产品清单
        $("#productInfo").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_contract_product&action=listJsonLimit',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'contractId': $("#contractId").val(),
                'dir': 'ASC',
                'prinvipalId': $("#prinvipalId").val(),
                'createId': $("#createId").val(),
                'areaPrincipalId': $("#areaPrincipalId").val(),
                //			'isTemp' : '0',
                'isDel': '0'
            },
            colModel: [
                {
                    name: 'newProLineName',
                    display: '产品线',
                    width: 100
                },
                {
                    name: 'exeDeptName',
                    display: '执行区域',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '产品类型',
                    width: 80
                },
                {
                    display: '产品名称',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + $("#contractId").val()
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '产品Id',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '产品描述',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '数量',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '单价',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '金额',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                    //		}, {
                    //			display : '保修期',
                    //			name : 'warrantyPeriod',
                    //			tclass : 'txtshort'
                },
                {
                    display: '加密配置Id',
                    name: 'license',
                    type: 'hidden'
                },
                {
                    display: '产品配置Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    name: 'deployButton',
                    display: '产品配置',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>产品配置</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    }
}


function parentView(pid) {
    showThickboxWin('?model=contract_contract_contract&action=parentView&contractId='
        + pid
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}