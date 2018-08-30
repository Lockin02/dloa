var show_page = function (page) {
    $("#contractGrid").yxgrid("reload");
};
$(function () {
    var buttonsArr = [
        {
            text: "重置",
            icon: 'delete',
            action: function (row) {
                var listGrid = $("#contractGrid").data('yxgrid');
                listGrid.options.extParam = {};
                $("#caseListWrap tr").attr('style',
                    "background-color: rgb(255, 255, 255)");
                listGrid.reload();
            }
        }, {
            text: "数据更新",
            icon: 'add',
            action: function (row) {
                showThickboxWin("?model=contract_contract_contract&action=toUpdateConRedundancy"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600")
            }
        }
    ];

    //add chenrf 20130417 附件
    /*****************附件下载权限控制****************************/
    var attachment = {
        text: "附件下载",
        icon: 'add',
        action: function () {
            var listGrid = $("#contractGrid").data('yxgrid');
            var ids = listGrid.getAllCheckedRowIds();
            if (ids.toString().substring(ids.toString().length - 1) == ",") {
                ids = ids.toString().substring(0, ids.toString().length - 1);
            }
            showThickboxWin('?model=contract_contract_contract&action=toDownAllFile&ids='
                + ids
                + "&type=oa_contract_contract"
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
        }
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '附件下载'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(attachment);
            }
        }
    });
    /*****************附件下载权限控制 end****************************/

    var HTDC = {
        name: 'export',
        text: "导出",
        icon: 'excel',
        items: [{
            name: 'export',
            text: "合同导出",
            icon: 'excel',
            action: function (row) {
                // var getAdvSearchArr =
                // $("#contractGrid").yxgrid("getAdvSearchArr")
                // alert(getAdvSearchArr)
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#contractGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#contractGrid").data('yxgrid').options.searchParam[t];
                    }
                }
                var contractType = $('#contractType').val();
                var ExaStatusArr = $('#ExaStatusArr').val();
                var businessBelong = $('#businessBelong').val();
                var isTemp = $("#contractGrid").data('yxgrid').options.param.isTemp;
                var states = $("#contractGrid").data('yxgrid').options.param.states;
                if (states == undefined) {
                    states = '';
                }
                var i = 1;
                var colId = "";
                var colName = "";
                $("#contractGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    });
                var searchSql = $("#contractGrid").data('yxgrid').getAdvSql();
                var searchArr = [];
                searchArr[0] = searchSql;
                searchArr[1] = searchConditionKey;
                searchArr[2] = searchConditionVal;
                var msg = $.ajax({
                    url: '?model=contract_contract_contract&action=setColInfoToSession',
                    data: 'ColId=' + colId + '&ColName=' + colName,
                    dataType: 'html',
                    type: 'post',
                    async: false
                }).responseText;

                if (msg == 1) {
                    window
                        .open("?model=contract_contract_contract&action=exportExcel&"
                        + "&isTemp="
                        + isTemp
                        + "&states="
                        + states
                        + "&contractType="
                        + contractType
                        + "&ExaStatusArr="
                        + ExaStatusArr
                        + "&businessBelong="
                        + businessBelong
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
                }
            }
        }, {
            name: 'export',
            text: "合同导出(CSV)",
            icon: 'excel',
            action: function (row) {
                // var getAdvSearchArr =
                // $("#contractGrid").yxgrid("getAdvSearchArr")
                // alert(getAdvSearchArr)
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#contractGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#contractGrid").data('yxgrid').options.searchParam[t];
                    }
                }
                var contractType = $('#contractType').val();
                var ExaStatusArr = $('#ExaStatusArr').val();
                var businessBelong = $('#businessBelong').val();
                var isTemp = $("#contractGrid").data('yxgrid').options.param.isTemp;
                var states = $("#contractGrid").data('yxgrid').options.param.states;
                if (states == undefined) {
                    states = '';
                }
                var i = 1;
                var colId = "";
                var colName = "";
                $("#contractGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    });
                var searchSql = $("#contractGrid").data('yxgrid').getAdvSql();
                var searchArr = [];
                searchArr[0] = searchSql;
                searchArr[1] = searchConditionKey;
                searchArr[2] = searchConditionVal;

                var msg = $.ajax({
                    url: '?model=contract_contract_contract&action=setColInfoToSession',
                    data: 'ColId=' + colId + '&ColName=' + colName,
                    dataType: 'html',
                    type: 'post',
                    async: false
                }).responseText;

                if (msg == 1) {
                    window
                        .open("?model=contract_contract_contract&action=exportExcel"
                        + "&isTemp="
                        + isTemp
                        + "&states="
                        + states
                        + "&contractType="
                        + contractType
                        + "&ExaStatusArr="
                        + ExaStatusArr
                        + "&businessBelong="
                        + businessBelong
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal="
                        + searchConditionVal
                        + "&CSV")
                }
            }
        }]
    }, mergeArr = {
        text: "财务金额导入",
        icon: 'excel',
        action: function (row) {
            showThickboxWin("?model=contract_contract_contract&action=FinancialImportexcel"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
        }
    };
    var importExcel = {
        text: "合同导入",
        icon: 'add',
        action: function (row) {
            showThickboxWin("?model=contract_contract_contract&action=toExcel"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
        }
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '合同导入权限'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(importExcel);
            }
        }
    });
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '财务金额导入'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(mergeArr);
            }
        }
    });
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '合同信息列表导出'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(HTDC);
            }
        }
    });
    var param = {
        'states': '0,1,2,3,4,5,6,7',
        'isTemp': '0'
    };
    if ($("#lastAdd").val()) {
        param.lastAdd = $("#lastAdd").val();
    }
    if ($("#lastChange").val()) {
        param.lastChange = $("#lastChange").val();
    }

    //助理权限
    var assLimit = $("#assLimit").val();
    var autoloadVal = $("#autoload").val();
    if (autoloadVal == "") {
        autoloadVal = false;
    }
    $("#contractGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'conPageJson',
        param: param,
        leftLayout: true,
        title: '合同信息',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        autoload: autoloadVal,
        customCode: 'contractInfoNew',//2014-10-23调整合同信息列表默认显示字段，重置自定义表头 contractInfo
        // 扩展右键菜单
        menusEx: [
            {
                text: '查看',
                icon: 'view',
                showMenuFn: function (row) {
                    return row ? true : false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                }
            },
            {
                text: '变更查看',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row && row.becomeNum != '0' && row.becomeNum != '') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=showViewTab&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                }
            },
            {
                text: '开票申请',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
                        + row.id
                        + '&invoiceapply[objCode]='
                        + row.contractCode
                        + '&invoiceapply[objType]=KPRK-12');
                }
            },
            {
                text: '录入不开票金额',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
                        + row.id
                        + '&objCode='
                        + row.contractCode
                        + '&objType=KPRK-12'
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
                }
            },
            {
                text: '扣款申请',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_deduct_deduct&action=toAdd&contractId='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
                }
            },
            {
                name: 'stamp',
                text: '申请盖章',
                icon: 'add',
                showMenuFn: function (row) {
                    if (assLimit != "1") {
                        return false;
                    }
                },
                action: function (row, rows, grid) {
                    if (row) {
                        if (row.isNeedStamp == '1') {
                            alert('此合同已申请盖章,不能重复申请');
                            return false;
                        }
                        //alert(row.contractType);
                        //add chenrf 20130524
                        //检查是否已存在盖章并且未审核完的
                        var msg = $.ajax({
                            url: '?model=contract_stamp_stamp&action=checkStamp',
                            data: 'contractId=' + row.id + '&contractType=HTGZYD-04',
                            dataType: 'html',
                            type: 'post',
                            async: false
                        }).responseText;
                        if (msg == 1) {
                            alert('此合同已申请盖章,不能重复申请');
                            return false;
                        }
                        showThickboxWin("?model=contract_contract_contract&action=toStamp&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                    } else {
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '变更合同',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=toChange&noApp=1&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
                }
            },
            {
                text: '修改',
                icon: 'edit',
                showMenuFn: function (row) {
                    var canEdit = $.ajax({
                        url: '?model=contract_contract_contract&action=chkHwEditLimit',
                        type: 'get',
                        async: false
                    }).responseText;
                    if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回' || row.parentName != '')
                        && row.isSubApp == '0' && canEdit == '1') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=init&id='
                        + row.id
                        + '&perm=hwedit'
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                }
            },
            {
                text: '附件上传',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_contract_contract&action=toUploadFile&id='
                        + row.id
                        + '&type=oa_contract_contract'
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
                }
            },
            {
                text: '附件下载',
                icon: 'add', // downloadLimit
                showMenuFn: function (row) {
                    if (row && row.downloadLimit == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_contract_contract&action=toDownFile&id='
                        + row.id
                        + '&type=oa_contract_contract'
                        + '&contractName='
                        + row.contractName
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
                }
            },
            {
                text: '财务相关日期',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financialDate == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_contract_contract&action=financialRelatedDate&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
                }
            },{
                text : '归档信息修改',
                icon : 'add',
                showMenuFn : function(row) {
                    if($("#archivedInfoModifyLimit").val() == '1'){
                        return true;
                    }else{
                        return false;
                    }
                },
                action : function(row) {
                    showThickboxWin("?model=contract_contract_contract&action=toUpdateArchivedInfo&id="
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=380&width=650");
                }
            }
        ],
        lockCol: ['conflag', 'exeStatus'],// 锁定的列名
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'conflag',
                display: '沟通板',
                sortable: true,
                width: 40,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "合计";
                    }
                    if (v == '') {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
                            + row.id
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
                            + "<img src='images/icon/icon139.gif' />"
                            + '</a>';
                    } else {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
                            + row.id
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
                            + "<img src='images/icon/icon095.gif' />"
                            + '</a>';
                    }

                }
            },
            {
                name : 'exeStatus',
                display : '合同状态',
                sortable : true,
                width : 50,
                process : function(v, row) {
                    return "<p onclick='exeStatusView(" + row.id
                        + ");' style='cursor:pointer;color:blue;' >"
                        + v + "</p>";
                }
            },
            {
                name: 'contractTypeName',
                display: "合同类型",
                sortable: true,
                width: 60
            },
            {
                name: 'customerTypeName',
                display: '客户类型',
                sortable: true,
                width: 70
            },
            {
                name: 'customerName',
                display: '客户名称',
                sortable: true,
                width: 180,
                hide: true
            },
            {
                name: 'contractCode',
                display: '合同编号',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
            },
            {
                name: 'objCode',
                display: '业务编号',
                hide: true,
                sortable: true,
                width: 120
            },
            {
                name: 'contractName',
                display: '合同名称',
                sortable: true,
                width: 150
            },
            {
                name: 'contractMoney',
                display: '合同金额',
                sortable: true,
                width: 80,
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'costEstimates',
                display: '成本概算(不含税)',
                sortable: true,
                width: 80,
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'exgross',
                display: '概算毛利率',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    }

                    if (isNaN(v) || !v) {
                        return v;
                    } else {
                        return v + "%";
                    }

                }
            },
            {
                name: 'budgetAll',
                display: '总预算',
                sortable: false,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'conProgress',
                display: '进度',
                width: 80,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00%";
                    } else {
                        return v+ "%";
                    }
                }
            },
            {
                name: 'curIncome',
                display: '收入',
                sortable: false,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'feeAll',
                display: '成本',
                sortable: false,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'gross',
                display: '毛利',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v != '******') {
                        return moneyFormat2(v);
                    } else {
                        return v;
                    }
                }
            },
            {
                name: 'rateOfGross',
                display: '毛利率',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    }
                    if (isNaN(v) || !v) {
                        return v;
                    } else {
                        return v + "%";
                    }
                }
            },
            {
                name: 'serviceconfirmMoneyAll',
                display: '财务确认总收入',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v != '******') {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                            + row.id
                            + '&tablename='
                            + row.contractType
                            + '&moneyType=serviceconfirmMoney'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                            + "<font color = '#4169E1'>"
                            + moneyFormat2(v) + "</font>" + '</a>';
                    } else {
                        return v;
                    }

                }
            },
            {
                name: 'financeconfirmMoneyAll',
                display: '财务确认总成本',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v != '******') {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                            + row.id
                            + '&tablename='
                            + row.contractType
                            + '&moneyType=financeconfirmMoney'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                            + "<font color = '#4169E1'>"
                            + moneyFormat2(v) + "</font>" + '</a>';
                    } else {
                        return v;
                    }
                }
            },
            {
                name: 'KPLXSD',
                display: '开票类型税点',
                hide: true
            },
            {
                name: 'comPoint',
                display: '综合税点',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'invoiceApplyMoney',
                display: '开票申请总金额',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'lastInvoiceDate',
                display: '最后一次开票日期',
                hide: true,
                sortable: true
            },
            {
                name: 'invoiceMoney',
                display: '开票金额',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'invoiceProgress',
                display: '开票进度',
                width: 80,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00%";
                    } else {
                        return v * 100 + "%";
                    }
                },
                hide: true
            },
            {
                name: 'softMoney',
                display: '软件金额',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'hardMoney',
                display: '硬件金额',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'serviceMoney',
                display: '服务金额',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'repairMoney',
                display: '维修金额',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'equRentalMoney',
                display: '设备租赁金额',
                hide: true,
                sortable: true
            },
            {
                name: 'spaceRentalMoney',
                display: '场地租赁金额',
                hide: true,
                sortable: true
            },
            {
                name: 'dsEnergyCharge',
                display: '代收电费总金额',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'dsWaterRateMoney',
                display: '代收水费总金额',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'houseRentalFee',
                display: '房屋出租总金额',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'installationCost',
                display: '安装服务总金额',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'deductMoney',
                display: '扣款金额',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == '******') {
                        return "******";
                    } else {
                        if (v == '') {
                            return "0.00";
                        } else {
                            return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                                + row.id
                                + '&tablename='
                                + row.contractType
                                + '&moneyType=deductMoney'
                                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                                + "<font color = '#4169E1'>"
                                + moneyFormat2(v) + "</font>" + '</a>';
                        }
                    }
                }
            },
            {
                name: 'uninvoiceMoney',
                display: '不开票金额',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == '******') {
                        return "******";
                    } else {
                        if (v == '') {
                            return "0.00";
                        } else {
                            return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_uninvoice_uninvoice&action=toObjList&objId='
                                + row.id
                                + '&objType=KPRK-12'
                                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'
                                + moneyFormat2(v) + '</a>';
                        }
                    }
                }
            },
            {
                name: 'surplusInvoiceMoney',
                display: '剩余开票金额',
                sortable: true,
                process: function (v, row) {
                    return "<span color = 'blue'>" + moneyFormat2(v) + "</span>";
                }
            },
            {
                name: 'incomeMoney',
                display: '已收金额',
                width: 60,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'surOrderMoney',
                display: '签约合同应收账款余额',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return "<span class='blue'>" + moneyFormat2(v) + "</span>";
                },
                hide: true
            },
            {
                name: 'incomeProgress',
                display: '收款进度',
                width: 80,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00%";
                    } else {
                        return v * 100 + "%";
                    }
                },
                hide: true
            },
            {
                name: 'badMoney',
                display: '坏账',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'surincomeMoney',
                display: '财务应收账款余额',
                sortable: true,
                process: function (v, row) {
                    return "<span color = 'blue'>" + moneyFormat2(v) + "</span>";
                }
            },
            {
                name: 'contractSigner',
                display: '合同签署人',
                sortable: true,
                hide: true,
                width: 80
            },
            {
                name: 'prinvipalName',
                display: '合同负责人',
                sortable: true,
                width: 80
            },
            {
                name: 'areaName',
                display: '归属区域',
                sortable: true,
                width: 60
            },
            {
                name: 'areaPrincipal',
                display: '区域负责人',
                hide: true,
                sortable: true
            },
            {
                name: 'contractProvince',
                display: '省份',
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'contractCity',
                display: '城市',
                hide: true,
                sortable: true,
                width: 60
            },
            {
                name: 'ExaDTOne',
                display: '建立时间',
                sortable: true,
                width: 80
            },
            {
                name: 'isRenewed',
                display: '是否续签',
                hide: true,
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "新签合同";
                    } else if (v == '1') {
                        return "续签合同";
                    } else {
                        return "";
                    }
                },
                width: 60
            },
            {
                name: 'businessBelongName',
                display: '签约公司',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'state',
                display: '合同状态',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "未提交";
                    } else if (v == '1') {
                        return "审批中";
                    } else if (v == '2') {
                        return "执行中";
                    } else if (v == '3') {
                        return "已关闭";
                    } else if (v == '4') {
                        return "已完成";
                    } else if (v == '5') {
                        return "已合并";
                    } else if (v == '6') {
                        return "已拆分";
                    } else if (v == '7') {
                        return "异常关闭";
                    }
                },
                width: 60
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'signStatus',
                display: '签收状态',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (v == '0') {
                        return "未签收";
                    } else if (v == '1') {
                        return "已签收";
                    } else if (v == '2') {
                        return "变更未签收";
                    }
                }
            },
            {
                name: 'signContractTypeName',
                display: "签收合同类型",
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'isAcquiring',
                display: '收单状态',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v) {
                    if (v == '0') {
                        return "未收单";
                    } else if (v == '1') {
                        return "已收单";
                    }
                }
            },
            {
                name: 'paperContract',
                display: '纸质合同',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'isNeedStamp',
                display: '是否需要盖章',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    } else {
                        if (v == '0') {
                            return "否";
                        } else {
                            return "是";
                        }
                    }
                }
            },
            {
                name: 'checkFile',
                display: '验收文件',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'trialprojectCode',
                display: '试用项目编号',
                hide: true,
                sortable: true
            },
            //{
            //    name: 'chanceCode',
            //    display: '商机编号',
            //    sortable: true,
            //    hide: true,
            //    width: 180,
            //    process: function (v, row) {
            //        if (row.chanceId != '')
            //            return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
            //                + row.chanceId
            //                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
            //                + "<span color='#4169E1'>"
            //                + v
            //                + "</span>"
            //                + '</a>';
            //    }
            //},
            {
                name: 'beginDate',
                display: '合同起始（始）',
                width: 80,
                sortable: true
            },
            {
                name: 'endDate',
                display: '合同起始（终）',
                width: 80,
                sortable: true
            },
            {
                name: 'signSubjectName',
                display: '合同签约主体',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanName',
                display: '甲方联系人',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanTel',
                display: '甲方联系方式',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'partAContractCode',
                display: '甲方合同编号',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'esmManagerName',
                display: '项目经理',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'paperSignTime',
                display: '纸质版签订时间',
                width: 80,
                sortable: true,
                process: function(v, row){
                    if(v == 'NULL'){
                        return '';
                    }else{
                        return v;
                    }
                }
            },
            // PMS 559 要求去掉这些字段的显示
            // {
            //     name: 'productCheck',
            //     display: '产品金额检查',
            //     width: 80,
            //     sortable: true,
            //     hide: true
            // },
            // {
            //     name: 'projectCheck',
            //     display: '项目金额检查',
            //     width: 80,
            //     sortable: true,
            //     hide: true
            // },
            // {
            //     name: 'invoiceCheck',
            //     display: '预计开票检查',
            //     width: 80,
            //     sortable: true,
            //     hide: true,
            //     process: function(v, row){
            //         if(row.isNoInvoiceCont == '1'){
            //             return '-';
            //         }else{
            //             return v;
            //         }
            //     }
            // },
            // {
            //     name: 'invoiceTrueCheck',
            //     display: '实际开票检查',
            //     width: 80,
            //     sortable: true,
            //     hide: true,
            //     process: function(v, row){
            //         if(row.isNoInvoiceCont == '1'){
            //             return '-';
            //         }else{
            //             return v;
            //         }
            //     }
            // },
            {
                name: 'icomeMoney',
                display: '营收应收款',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return "<span class='blue'>" + moneyFormat2(v) + "</span>";
                },
                hide: true
            },
            {
                name: 'contractNatureName',
                display: '合同属性',
                width: 80,
                sortable: true,
                hide: true
            }
        ],
        comboEx: [
            {
                text: '类型',
                key: 'contractType',
                data: [
                    {
                        text: '销售合同',
                        value: 'HTLX-XSHT'
                    },
                    {
                        text: '服务合同',
                        value: 'HTLX-FWHT'
                    },
                    {
                        text: '租赁合同',
                        value: 'HTLX-ZLHT'
                    },
                    {
                        text: '研发合同',
                        value: 'HTLX-YFHT'
                    }
                ]
            },
            {
                text: '合同状态',
                key: 'states',
                value: '0,1,2,3,4,5,6',
                data: [
                    {
                        text: '所有(不含异常合同)',
                        value: '0,1,2,3,4,5,6'
                    },
                    {
                        text: '审批中',
                        value: '1'
                    },
                    {
                        text: '执行中',
                        value: '2'
                    },
                    {
                        text: '已完成',
                        value: '4'
                    },
                    {
                        text: '已关闭',
                        value: '3'
                    }
                    ,
                    {
                        text: '异常关闭',
                        value: '7'
                    },
                    {
                        text: '有效合同(执行，完成，关闭)',
                        value: '2,3,4'
                    }
                ]
            },
            {
                text: '审批状态',
                key: 'ExaStatusArr',
                data: [
                    {
                        text: '未审批',
                        value: '未审批'
                    },
                    {
                        text: '部门审批',
                        value: '部门审批'
                    },
                    {
                        text: '变更审批中',
                        value: '变更审批中'
                    },
                    {
                        text: '打回',
                        value: '打回'
                    },
                    {
                        text: '完成',
                        value: '完成'
                    },
                    {
                        text: '完成和变更审批中',
                        value: '完成,变更审批中'
                    }
                ]
            },
            {
                text: '签约主体',
                key: 'businessBelong',
                datacode: 'QYZT'
            }
        ],
        /**
         * 快速搜索
         */
        searchitems: [
            {
                display: '合同编号',
                name: 'contractCode'
            },
            {
                display: '合同名称',
                name: 'contractName'
            },
            {
                display: '客户名称',
                name: 'customerName'
            },
            {
                display: '业务编号',
                name: 'objCode'
            },
            {
                display: '产品名称',
                name: 'conProductName'
            },
            {
                display: '试用项目',
                name: 'trialprojectCode'
            }
        ],
        sortname: "createTime",
        buttonsEx: buttonsArr,

        // 高级搜索
        advSearchOptions: {
            modelName: 'contractInfo',
            // 选择字段后进行重置值操作
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
                $valInput.yxselect_user("remove");
            },
            searchConfig: [
                {
                    name: '建立日期',
                    value: 'c.ExaDTOne',
                    changeFn: function ($t, $valInput) {
                        $valInput.click(function () {
                            WdatePicker({
                                dateFmt: 'yyyy-MM-dd'
                            });
                        });
                    }
                },
                {
                    name: '年份（直接输入数字，如2013）',
                    value: 'date_format(c.ExaDTOne,"%Y")'
                },
                {
                    name: '月份（直接输入数字，如 04、11）',
                    value: 'date_format(c.ExaDTOne,"%m")'
                },
                {
                    name: '季度（直接输入数字，如 1、2、3、4）',
                    value: 'quarter(c.ExaDTOne)'
                },
                {
                    name: '合同类型',
                    value: 'c.contractType',
                    type: 'select',
                    datacode: 'HTLX'
                },
                {
                    name: '销售合同属性',
                    value: 'c.contractNature*XS',
                    type: 'select',
                    datacode: 'HTLX-XSHT'
                }
                ,
                {
                    name: '服务合同属性',
                    value: 'c.contractNature*FW',
                    type: 'select',
                    datacode: 'HTLX-FWHT'
                },
                {
                    name: '租赁合同属性',
                    value: 'c.contractNature*ZL',
                    type: 'select',
                    datacode: 'HTLX-ZLHT'
                },
                {
                    name: '研发合同属性',
                    value: 'c.contractNature*YF',
                    type: 'select',
                    datacode: 'HTLX-YFHT'
                }
                ,
                {
                    name: '客户类型',
                    value: 'c.customerType',
                    type: 'select',
                    datacode: 'KHLX'
                }
                ,
                {
                    name: '签约合同应收账款余额',
                    value: 'c.surOrderMoney'
                },
                {
                    name: '财务应收账款余额',
                    value: 'c.surincomeMoney'
                },
                {
                    name: '区域负责人',
                    value: 'c.areaPrincipal',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaPrincipalId' + rowNum,
                            nameCol: 'areaPrincipal',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '归属区域',
                    value: 'c.areaName',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaCode' + rowNum,
                            nameCol: 'areaName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '合同负责人',
                    value: 'c.prinvipalName',
                    changeFn: function ($t, $valInput, rowNum) {

                        $valInput.yxselect_user({
                            hiddenId: 'prinvipalId' + rowNum,
                            nameCol: 'prinvipalName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '合同签署人',
                    value: 'c.contractSigner',
                    changeFn: function ($t, $valInput, rowNum) {

                        $valInput.yxselect_user({
                            hiddenId: 'contractSignerId' + rowNum,
                            nameCol: 'contractSigner',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '省份',
                    value: 'c.contractProvince'
                },
                {
                    name: '城市',
                    value: 'c.contractCity'
                },
                {
                    name: '合同状态',
                    value: 'c.state',
                    type: 'select',
                    options: [
                        {
                            'dataName': '未提交',
                            'dataCode': '0'
                        },
                        {
                            'dataName': '审批中',
                            'dataCode': '1'
                        },
                        {
                            'dataName': '执行中',
                            'dataCode': '2'
                        },
                        {
                            'dataName': '已完成',
                            'dataCode': '4'
                        },
                        {
                            'dataName': '已关闭',
                            'dataCode': '3'
                        },
                        {
                            'dataName': '异常关闭',
                            'dataCode': '7'
                        }
                    ]

                },
                {
                    name: '审批状态',
                    value: 'c.ExaStatus',
                    type: 'select',
                    options: [
                        {
                            'dataName': '未审批',
                            'dataCode': '未审批'
                        },
                        {
                            'dataName': '部门审批',
                            'dataCode': '部门审批'
                        },
                        {
                            'dataName': '变更审批中',
                            'dataCode': '变更审批中'
                        },
                        {
                            'dataName': '打回',
                            'dataCode': '打回'
                        },
                        {
                            'dataName': '完成',
                            'dataCode': '完成'
                        }
                    ]

                },
                {
                    name: '签约主体',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
    });

});

// 执行进度显示
function exeStatusView(cid) {
    showModalWin("?model=contract_contract_contract&action=exeStatusView&cid=" + cid);
}