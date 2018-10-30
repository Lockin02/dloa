// 计算方法
function countAll(rowNum) {
    var beforeStr = "productInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // 获取当前数
        var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // 获取当前单价
        var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // 计算本行金额 - 不含税
        var thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}


function countAllP(rowNum) {
    var beforeStr = "borrowConEquInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // 获取当前数
        var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // 获取当前单价
        var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // 计算本行金额 - 不含税
        var thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}

$(function () {
    proInfoList();

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


    if ($("#isSubAppChange").val() == '1') {
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
    $("#materialInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        type: 'view',
        tableClass: 'form_in_table',
        param: param,
        colModel: [
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
