var show_page = function (page) {
    $("#contractGrid").yxgrid("reload");
};
$(function () {


    buttonsArr = [
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
        }/*, {
         text : "附件下载",
         icon : 'add',
         action : function() {
         var listGrid = $("#contractGrid").data('yxgrid');
         var ids = listGrid.getAllCheckedRowIds();
         if(ids.toString().substring(ids.toString().length-1)==","){
         ids=ids.toString().substring(0,ids.toString().length-1);
         }
         showThickboxWin('?model=contract_contract_contract&action=toDownAllFile&ids='
         + ids
         + "&type=oa_contract_contract"
         + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
         }
         }*/
    ]

        //add chenrf 20130417 附件
    /*****************附件下载权限控制****************************/
//        attachment = {
//            text: "附件下载",
//            icon: 'add',
//            action: function () {
//                var listGrid = $("#contractGrid").data('yxgrid');
//                var ids = listGrid.getAllCheckedRowIds();
//                if (ids.toString().substring(ids.toString().length - 1) == ",") {
//                    ids = ids.toString().substring(0, ids.toString().length - 1);
//                }
//                showThickboxWin('?model=contract_contract_contract&action=toDownAllFile&ids='
//                    + ids
//                    + "&type=oa_contract_contract"
//                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
//            }
//        }
//    $.ajax({
//        type: 'POST',
//        url: '?model=contract_contract_contract&action=getLimits',
//        data: {
//            'limitName': '附件下载'
//        },
//        async: false,
//        success: function (data) {
//            if (data == 1) {
//                buttonsArr.push(attachment);
//            }
//        }
//    });
    /*****************附件下载权限控制 end****************************/

//    HTDC = {
//        name: 'export',
//        text: "合同导出",
//        icon: 'excel',
//        action: function (row) {
//            // var getAdvSearchArr =
//            // $("#contractGrid").yxgrid("getAdvSearchArr")
//            // alert(getAdvSearchArr)
//            var searchConditionKey = "";
//            var searchConditionVal = "";
//            for (var t in $("#contractGrid").data('yxgrid').options.searchParam) {
//                if (t != "") {
//                    searchConditionKey += t;
//                    searchConditionVal += $("#contractGrid").data('yxgrid').options.searchParam[t];
//                }
//            }
//            var contractType = $('#contractType').val();
//            var states = $('#states').val();
//            var ExaStatusArr = $('#ExaStatusArr').val();
//            var businessBelong = $('#businessBelong').val();
//            var isTemp = $("#contractGrid").data('yxgrid').options.param.isTemp;
//            var states = $("#contractGrid").data('yxgrid').options.param.states;
//			alert(contractType);
//			exit();
//            if (states == undefined) {
//                states = '';
//            }
            // var ExaStatus = $("#ExaStatus").val();
            // var beginDate =
            // $("#contractGrid").data('yxgrid').options.extParam.beginDate;//
            // 开始时间
            // var endDate =
            // $("#contractGrid").data('yxgrid').options.extParam.endDate;//
            // 截止时间
            // var ExaDT =
            // $("#contractGrid").data('yxgrid').options.extParam.ExaDT;// 建立时间
            // var areaNameArr =
            // $("#contractGrid").data('yxgrid').options.extParam.areaNameArr;//
            // 归属区域
            // var orderCodeOrTempSearch =
            // $("#contractGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//
            // 合同编号
            // var prinvipalName =
            // $("#contractGrid").data('yxgrid').options.extParam.prinvipalName;//
            // 合同负责人
            // var customerName =
            // $("#contractGrid").data('yxgrid').options.extParam.customerName;//
            // 客户名称
            // var orderProvince =
            // $("#contractGrid").data('yxgrid').options.extParam.orderProvince;//
            // 所属省份
            // var customerType =
            // $("#contractGrid").data('yxgrid').options.extParam.customerType;//
            // 客户类型
            // var orderNatureArr =
            // $("#contractGrid").data('yxgrid').options.extParam.orderNatureArr;//
            // 合同属性
            // var isShip =
            // $("#contractGrid").data('yxgrid').options.extParam.DeliveryStatusArr;//
            // 是否有发货记录
//            var i = 1;
//            var colId = "";
//            var colName = "";
//            $("#contractGrid_hTable").children("thead").children("tr")
//                .children("th").each(function () {
//                    if ($(this).css("display") != "none"
//                        && $(this).attr("colId") != undefined) {
//                        colName += $(this).children("div").html() + ",";
//                        colId += $(this).attr("colId") + ",";
//                        i++;
//                    }
//                })
//            var searchSql = $("#contractGrid").data('yxgrid').getAdvSql();
//            var searchArr = [];
//            searchArr[0] = searchSql;
//            searchArr[1] = searchConditionKey;
//            searchArr[2] = searchConditionVal;

//			alert(searchArr[1]);
//			exit();
//            window
//                .open("?model=contract_contract_contract&action=exportExcel&colId="
//                    + colId
//                    + "&colName="
//                    + colName
//                    + "&isTemp="
//                    + isTemp
//                    + "&states="
//                    + states
//                    + "&contractType="
//                    + contractType
//                    + "&ExaStatusArr="
//                    + ExaStatusArr
//                    + "&businessBelong="
//                    + businessBelong
//                    + "&searchConditionKey="
//                    + searchConditionKey
//                    + "&searchConditionVal=" + searchConditionVal)
//        }
//    }
        // ,updateObjCode = {
        // text : "更新业务编号",
        // icon : 'add',
        // action : function(row) {
        // showThickboxWin("?model=common_contract_allsource&action=toUpdateObjCode"
        // + "&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=600")
        // }
        // }, updateOld = {
        // text : "更新旧数据",
        // icon : 'add',
        // action : function(row) {
        // showThickboxWin("?model=common_contract_allsource&action=updateOldToNewContract"
        // + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
        // }
        // }
//        , mergeArr = {
//        text: "财务金额导入",
//        icon: 'excel',
//        action: function (row) {
//            showThickboxWin("?model=contract_contract_contract&action=FinancialImportexcel"
//                + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
//        }
//    }, importExcel = {
//        text: "合同导入",
//        icon: 'add',
//        action: function (row) {
//            showThickboxWin("?model=contract_contract_contract&action=toExcel"
//                + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
//        }
//    };
//    $.ajax({
//        type: 'POST',
//        url: '?model=contract_contract_contract&action=getLimits',
//        data: {
//            'limitName': '合同导入权限'
//        },
//        async: false,
//        success: function (data) {
//            if (data == 1) {
//                buttonsArr.push(importExcel);
//            }
//        }
//    });
//    $.ajax({
//        type: 'POST',
//        url: '?model=contract_contract_contract&action=getLimits',
//        data: {
//            'limitName': '财务金额导入'
//        },
//        async: false,
//        success: function (data) {
//            if (data == 1) {
//                buttonsArr.push(mergeArr);
//            }
//        }
//    });
//    $.ajax({
//        type: 'POST',
//        url: '?model=contract_contract_contract&action=getLimits',
//        data: {
//            'limitName': '合同信息列表导出'
//        },
//        async: false,
//        success: function (data) {
//            if (data == 1) {
//                buttonsArr.push(HTDC);
//            }
//        }
//    });


    // $.ajax({
    // type : 'POST',
    // url : '?model=contract_contract_contract&action=getLimits',
    // data : {
    // 'limitName' : '更新权限'
    // },
    // async : false,
    // success : function(data) {
    // if (data == 1) {
    // buttonsArr.push(updateObjCode);
    // buttonsArr.push(updateOld);
    // }
    // }
    // });
//    var param = {
//        'states': '0,1,2,3,4,5,6,7',
//        'isTemp': '0'
//    }
//    if ($("#lastAdd").val()) {
//        param.lastAdd = $("#lastAdd").val();
//    }
//    if ($("#lastChange").val()) {
//        param.lastChange = $("#lastChange").val();
//    }

    //助理权限
    var assLimit = $("#assLimit").val();
    var autoloadVal = $("#autoload").val();
    if (autoloadVal == "") {
        autoloadVal = false;
    }
    $("#contractGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'statistictPageJson',
        title: '合同主表',
        param: {
//        	ExaStatus: '完成',
            ExaStatusArr: '完成,变更审批中',
            isTemp: '0',
            prinvipalId: $('#userId').val(),
            areaCode: $('#areaId').val(),
			ExaYear: $('#year').val()
        },
//        leftLayout: true,
        title: '合同信息',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox : false,
        isAddAction: false,
        autoload: autoloadVal,
        customCode: 'contractInfoNew',//2014-10-23调整合同信息列表默认显示字段，重置自定义表头 contractInfo
        // 扩展右键菜单

        menusEx: [
            {
                text: '查看',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900',1);
                }
            },
//            {
//                text: '变更查看',
//                icon: 'view',
//                showMenuFn: function (row) {
//                    if (row && row.becomeNum != '0' && row.becomeNum != '') {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showModalWin('?model=contract_contract_contract&action=showViewTab&id='
//                        + row.id
//                        + "&skey="
//                        + row['skey_']
//                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
//                }
//            },
//            {
//                text: '开票申请',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row && row.financial == 1) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
//                        + row.id
//                        + '&invoiceapply[objCode]='
//                        + row.contractCode
//                        + '&invoiceapply[objType]=KPRK-12');
//                }
//            },
//            {
//                text: '录入不开票金额',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row && row.financial == 1) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
//                        + row.id
//                        + '&objCode='
//                        + row.contractCode
//                        + '&objType=KPRK-12'
//                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
//                }
//            },
//            {
//                text: '扣款申请',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row && row.financial == 1) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showThickboxWin('?model=contract_deduct_deduct&action=toAdd&contractId='
//                        + row.id
//                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
//                }
//            },
//            {
//                name: 'stamp',
//                text: '申请盖章',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (assLimit != "1") {
//                        return false;
//                    }
//                    if (row && row.status == 3) {
//                        return false;
//                    }
//                    if (row && (row.ExaStatus == "完成" ))   //&& row.isStamp != "1"
//                        return true;
//                    else
//                        return false;
//                },
//                action: function (row, rows, grid) {
//                    if (row) {
//                        if (row.isNeedStamp == '1') {
//                            alert('此合同已申请盖章,不能重复申请');
//                            return false;
//                        }
//                        //alert(row.contractType);
//                        //add chenrf 20130524
//                        //检查是否已存在盖章并且未审核完的
//                        var msg = $.ajax({
//                            url: '?model=contract_stamp_stamp&action=checkStamp',
//                            data: 'contractId=' + row.id + '&contractType=HTGZYD-04',
//                            dataType: 'html',
//                            type: 'post',
//                            async: false
//                        }).responseText;
//                        if (msg == 1) {
//                            alert('此合同已申请盖章,不能重复申请');
//                            return false;
//                        }
//                        showThickboxWin("?model=contract_contract_contract&action=toStamp&id="
//                            + row.id
//                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
//                    } else {
//                        alert("请选中一条数据");
//                    }
//                }
//            },
//            {
//                text: '变更合同',
//                icon: 'edit',
//                showMenuFn: function (row) {
//                    if (row && row.financial == 1) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showModalWin('?model=contract_contract_contract&action=toChange&noApp=1&id='
//                        + row.id
//                        + "&skey="
//                        + row['skey_']
//                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
//                }
//            },
//            {
//                text: '修改',
//                icon: 'edit',
//                showMenuFn: function (row) {
//                    if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回' || row.parentName != '')
//                        && row.isSubApp == '0') {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showModalWin('?model=contract_contract_contract&action=init&id='
//                        + row.id
//                        + '&perm=edit'
//                        + "&skey="
//                        + row['skey_']
//                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
//                }
//            },
//            {
//                text: '附件上传',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showThickboxWin('?model=contract_contract_contract&action=toUploadFile&id='
//                        + row.id
//                        + '&type=oa_contract_contract'
//                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
//                }
//            },
//            {
//                text: '附件下载',
//                icon: 'add', // downloadLimit
//                showMenuFn: function (row) {
//                    if (row && row.downloadLimit == 1) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showThickboxWin('?model=contract_contract_contract&action=toDownFile&id='
//                        + row.id
//                        + '&type=oa_contract_contract'
//                        + '&contractName='
//                        + row.contractName
//                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
//                }
//            },
//            {
//                text: '财务相关日期',
//                icon: 'add',
//                showMenuFn: function (row) {
//                    if (row && row.financialDate == 1) {
//                        return true;
//                    }
//                    return false;
//                },
//                action: function (row) {
//                    showThickboxWin('?model=contract_contract_contract&action=financialRelatedDate&id='
//                        + row.id
//                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
//                }
//            }
            // , {
            // text : '完成合同',
            // icon : 'edit',
            // showMenuFn : function(row) {
            // if (row && (row.state == 2 && row.exeLimit == 1)) {
            // return true;
            // }
            // return false;
            // },
            // action : function(row) {
            // if (window.confirm(("确定要把合同状态改为 “完成” 状态吗？"))) {
            // $.ajax({
            // type : "POST",
            // url : "?model=contract_contract_contract&action=completeOrder&id="
            // + row.id,
            // success : function(msg) {
            // $("#contractGrid").yxgrid("reload");
            // }
            // });
            // }
            // }
            // }, {
            // text : '执行合同',
            // icon : 'edit',
            // showMenuFn : function(row) {
            // if (row && (row.state == 4 && row.exeLimit == 1)) {
            // return true;
            // }
            // return false;
            // },
            // action : function(row) {
            // if (window.confirm(("确定要把合同状态改为 “执行中” 状态吗？"))) {
            // $.ajax({
            // type : "POST",
            // url : "?model=contract_contract_contract&action=exeOrder&id="
            // + row.id,
            // success : function(msg) {
            // $("#contractGrid").yxgrid("reload");
            // }
            // });
            // }
            // }
            // }
            // , {
            // text : '关闭合同',
            // icon : 'delete',
            // showMenuFn : function(row) {
            // if (row && (row.state == '2' || row.state == '4')) {
            // return true;
            // }
            // return false;
            // },
            // action : function(row) {
            // showThickboxWin('?model=contract_contract_contract&action=closeContract&id='
            // + row.id
            // +
            // '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
            // }
            // }, {
            // text : '合同共享',
            // icon : 'add',
            // showMenuFn : function(row) {
            // if (row) {
            // return true;
            // }
            // return false;
            // },
            // action : function(row) {
            // showThickboxWin('?model=contract_contract_contract&action=toShare&id='
            // + row.id
            // +
            // '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=500');
            // }
            // }
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
                name: 'contractType',
                display: "合同类型Code",
                sortable: true,
//					datacode : 'HTLX',
                width: 60,
                hide: true
            },
            {
                name: 'contractTypeName',
                display: "合同类型",
                sortable: true,
//					datacode : 'HTLX',
                width: 60
            },
            {
                name: 'contractCode',
                display: '合同编号',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
            },
            {
                name: 'customerName',
                display: '客户名称',
                sortable: true,
                width: 180
            },
            {
                name: 'customerTypeName',
                display: '客户类型',
                sortable: true,
//					datacode : 'KHLX',
                width: 70,
                hide: true
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
                display: '成本概算',
                sortable: true,
                hide: true,
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
                name: 'KPLXSD',
                display: '开票类型税点'
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
                name: 'badMoney',
                display: '坏账',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
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
                name: 'surplusInvoiceMoney',
                display: '剩余开票金额',
                sortable: true,
                hide: true,
                process: function (v, row) {
                    return "<font color = 'blue'>" + moneyFormat2(v);
                    +"</font>"
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
                hide: true,
                width: 120,
                process: function (v, row) {
                    return "<font color = 'blue'>" + moneyFormat2(v);
                    +"</font>"
                }
            },
            {
                name: 'surincomeMoney',
                display: '财务应收账款余额',
                sortable: true,
                hide: true,
                process: function (v, row) {
                    return "<font color = 'blue'>" + moneyFormat2(v);
                    +"</font>"
                }
            }
            ,
            {
                name: 'deliveryCostsAll',
                display: '交付成本',
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
                            + '&moneyType=deliveryCosts'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                            + "<font color = '#4169E1'>"
                            + moneyFormat2(v) + "</font>" + '</a>';
                    } else {
                        return v;
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
                name: 'financeconfirmPlan',
                display: '财务确认进度',
                sortable: false,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == undefined) {
                        return "";
                    }
                    if (v != '******') {
                        var financePlan = moneyFormat2(row.serviceconfirmMoney
                            / (accSub(row.contractMoney,
                            row.deductMoney)));
                        if (isNaN(financePlan)) {
                            return "0.00%";
                        } else {
                            financePlan = parseFloat(financePlan)
                                .toFixed(2);
                            return financePlan * 100 + "%";
                        }
                    } else {
                        return v;
                    }
                }
            },
            {
                name: 'gross',
                display: '毛利',
                sortable: true,
                hide: true,
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
                name: 'ExaDTOne',
                display: '建立时间',
                sortable: true,
                width: 80
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
                name: 'budgetOutsourcing',
                display: '外包费用预算',
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
                name: 'feeFieldCount',
                display: '现场费用',
                sortable: false,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'feeOutsourcing',
                display: '外包费用',
                sortable: false,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'feeOther',
                display: '其他费用',
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
                name: 'feeAll',
                display: '总费用',
                sortable: false,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'projectProcessAll',
                display: '工作量进度',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    }
                    if (v == "") {
                        return "0.00";
                    }
                    return v + "%";
                }
            },
            {
                name: 'processMoney',
                display: '按工作量执行合同额',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'winRate',
                display: '合同赢率',
                sortable: true,
                hide: true,
                width: 70
            },
            {
                name: 'businessBelong',
                display: '签约公司编码',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'businessBelongName',
                display: '签约公司',
                sortable: true,
                width: 60
            },
            {
                name: 'prinvipalName',
                display: '合同负责人',
                sortable: true,
                width: 80
            }   ,
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
                name: 'contractSigner',
                display: '合同签署人',
                sortable: true,
                hide: true,
                width: 80
            },
            {
                name: 'state',
                display: '合同状态',
                sortable: true,
                hide: true,
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
                name: 'objCode',
                display: '业务编号',
                hide: true,
                sortable: true,
                width: 120
            },
        /***************************************************************
         * { name : 'prinvipalDept', display : '负责人部门', sortable : true,
				 * hide : true },
         **************************************************************/
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
                name: 'lastInvoiceDate',
                display: '最后一次开票日期',
                hide: true,
                sortable: true
            },
            {
                name: 'shouldInvoiceDate',
                display: '应开票日期',
                hide: true,
                sortable: true
            },
            {
                name: 'preliminaryDate',
                display: '初验日期',
                hide: true,
                sortable: true
            },
            {
                name: 'finalDate',
                display: '终验日期',
                hide: true,
                sortable: true
            },
            {
                name: 'signContractType',
                display: "签收合同类型Code",
                sortable: true,
                hide: true,
//					datacode : 'HTLX',
                width: 60
            },
            {
                name: 'signContractTypeName',
                display: "签收合同类型",
                sortable: true,
                hide: true,
//					datacode : 'HTLX',
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
                name: 'isNeedStamp',
                display: '是否盖章',
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
                name: 'exeStatus',
                display: '执行进度',
                sortable: true,
                width: 50,
                process: function (v, row) {
                    return "<p onclick='exeStatusView(" + row.id + ");' style='cursor:pointer;color:blue;' >" + v + "</p>";
                }
            },
            {
                name: 'trialprojectCostAll',
                display: '试用项目费用',
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
                            return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                                + row.id
                                + '&tablename='
                                + row.contractType
                                + '&moneyType=trialprojectCost'
                                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                                + "<font color = '#4169E1'>"
                                + moneyFormat2(v) + "</font>" + '</a>';
                        }
                    }
                }
            },
            {
                name: 'trialprojectCode',
                display: '试用项目编号',
                hide: true,
                sortable: true
            },
            {
                name: 'chanceCode',
                display: '商机编号',
                sortable: true,
                hide: true,
                width: 180,
                process: function (v, row) {
                    if (row.chanceId != '')
                        return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
                            + row.chanceId
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>"
                            + v
                            + "</font>"
                            + '</a>';
                }
            },
            {
                name: 'ExaYear',
                display: '建立时间（年）',
                hide: true,
                process: function (v, row) {
                    if (v) {
                        return v + "年";
                    } else {
                        return "-";
                    }
                }
            },
            {
                name: 'ExaMonth',
                display: '建立时间（月）',
                hide: true,
                process: function (v, row) {
                    if (v) {
                        return v + "月";
                    } else {
                        return "-";
                    }
                }
            },
            {
                name: 'ExaQuarter',
                display: '建立时间(季度)',
                hide: true,
                process: function (v, row) {
                    if (v) {
                        return v + "季度";
                    } else {
                        return "-";
                    }
                }
            },
            {
                name: 'customerId',
                display: '客户Id',
                sortable: true,
                hide: true,
                width: 100
            },
            {
                name: 'contractFee',
                display: '合同费用',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    }
                    return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=feeCostView&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                        + "<font color = '#4169E1'>"
                        + moneyFormat2(v) + "</font>" + '</a>';
                }
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
                name: 'contractNatureName',
                display: '合同属性',
                sortable: true,
                width: 60,
                process: function (v) {
                    if (v == '') {
                        return v;
                        // return "金额合计";
                    } else {
                        if (v == 'NULL') {
                            return "";
                        } else {
                            return v;
                        }
                    }
                }
            }
        ],
        comboEx: [
//            {
//                text: '类型',
//                key: 'contractType',
//                data: [
//                    {
//                        text: '销售合同',
//                        value: 'HTLX-XSHT'
//                    },
//                    {
//                        text: '服务合同',
//                        value: 'HTLX-FWHT'
//                    },
//                    {
//                        text: '租赁合同',
//                        value: 'HTLX-ZLHT'
//                    },
//                    {
//                        text: '研发合同',
//                        value: 'HTLX-YFHT'
//                    }
//                ]
//            },
            {
                text: '合同状态',
                key: 'states',
                value: '2,3,4',
                data: [
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
                    // , {
                    // text : '已合并',
                    // value : '5'
                    // }, {
                    // text : '已拆分',
                    // value : '6'
                    // }
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
//            {
//                text: '审批状态',
//                key: 'ExaStatusArr',
//                data: [
//                    {
//                        text: '未审批',
//                        value: '未审批'
//                    },
//                    {
//                        text: '部门审批',
//                        value: '部门审批'
//                    },
//                    {
//                        text: '变更审批中',
//                        value: '变更审批中'
//                    },
//                    {
//                        text: '打回',
//                        value: '打回'
//                    },
//                    {
//                        text: '完成',
//                        value: '完成'
//                    },
//                    {
//                        text: '完成和变更审批中',
//                        value: '完成,变更审批中'
//                    }
//                ]
//            },
            {
                text: '签约主体',
                key: 'businessBelong',
                datacode: 'QYZT'
            }
        ],
        // 主从表格设置
        // subGridOptions : {
        // url : '?model=contract_contract_product&action=pageJson',// 获取从表数据url
        // // 传递到后台的参数设置数组
        // param : [{
        // paramId : 'contractId',// 传递给后台的参数名称
        // colId : 'id'// 获取主表行数据的列名称
        //
        // }],
        // // param:{
        // // 'contractId' : $("#contractId").val(),
        // // 'dir' : 'ASC',
        // // 'prinvipalId':$("#prinvipalId").val(),
        // // 'createId':$("#createId").val(),
        // // 'areaPrincipalId':$("#areaPrincipalId").val(),
        // // // 'isTemp' : '0',
        // // 'isDel' : '0'
        // // },
        // // 显示的列
        // colModel : [{
        // name : 'conProductName',
        // width : 200,
        // display : '产品名称'
        // }, {
        // name : 'conProductDes',
        // display : '产品描述',
        // width : 80
        // }, {
        // name : 'number',
        // display : '数量',
        // width : 80
        // }, {
        // name : 'price',
        // display : '单价',
        // width : 80
        // }, {
        // name : 'money',
        // display : '金额',
        // width : 80
        // }, {
        // name : 'licenseButton',
        // display : '加密配置',
        // process : function(v, row) {
        // if (row.license != "") {
        // return "<a href='#' onclick='showLicense(\'"
        // + row.license + "\')'>查看</a>";
        // } else {
        // return "";
        // }
        // }
        // }, {
        // name : 'deployButton',
        // display : '产品配置',
        // process : function(v, row) {
        // if (row.deploy != "") {
        // return "<a href='#' onclick='showGoods(\""
        // + row.deploy + "\",\""
        // + row.conProductName + "\")'>查看</a>";
        // } else {
        // return "";
        // }
        // }
        // }]
        // },
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
                // , {
                // name : '剩余开票金额',
                // value : 'c.surplusInvoiceMoney'
                // }
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
    url = "?model=contract_contract_contract&action=exeStatusView&cid=" + cid;
    showModalDialog(url, '', "dialogWidth:1100px;dialogHeight:600px;");

}