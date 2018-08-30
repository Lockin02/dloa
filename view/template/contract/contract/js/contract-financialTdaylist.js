var show_page = function(page) {
    $("#financialList").yxgrid("reload");
};

$(function() {
    $("#financialList").yxgrid({
        model : 'contract_contract_contract',
		action : 'TdayPageJson',
//        param : {
//            'states' : '1,2,3,4,5,6,7',
//            'isTemp' : '0',
//            'ExaStatus' : '完成',
//            'signStatusArr' : '1'
//        },

        title : '收款确认',
        isViewAction : false,
        isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        isOpButton : false,
//        customCode : 'contractSigninCom',
        buttonsEx : [{
            text: "重置",
            icon: 'delete',
            action: function (row) {
                history.go(0);
            }
        },{
            name : 'Add',
            text : "批量确认",
            icon : 'add',
            action : function(rowData, rows, rowIds, g) {
                if (rowData) {
                    var checkArr = new Array();
                    for (var i = 0; i < rows.length; i++) {
                        var rNum = rows[i].rowNum;
                        if($("#tday"+rNum).val() == '' ){
                            alert("T-日，日期不允许为空！");
                            return false;
                        }else if(rows[i].isConfirm == '1'){
                            alert("已确认T-日数据不允许使用批量确认操作。");
                            return false;
                        }else{
                            var jsonTemp = {
                                id : rows[i].id,
                                tday : $("#tday"+rNum).val()
                            }
                            checkArr.push(jsonTemp);
                        }
                    }
                    $.ajax({
                        type : 'POST',
                        url : "?model=contract_contract_contract&action=updateTdayBatch",
                        data : {
                            checkArr : checkArr
                        },
                        async : false,
                        success : function(data) {
                            if (data == '1') {
                                alert("确认完成");
                                $("#financialList").yxgrid("reload");
                            }else{
//                                alert(data)
                                alert("确认失败");
                                $("#financialList").yxgrid("reload");
                            }
                        }
                    });
                } else {
                    alert('请先选择记录');
                }
            }
        }
//            ,{
//            name : 'export',
//            text : "导出",
//            icon : 'excel',
//            action : function(row) {
//                var searchConditionKey = "";
//                var searchConditionVal = "";
//                for (var t in $("#comsigninGrid").data('yxgrid').options.searchParam) {
//                    if (t != "") {
//                        searchConditionKey += t;
//                        searchConditionVal += $("#comsigninGrid")
//                            .data('yxgrid').options.searchParam[t];
//                    }
//                }
//                var state = $("#state").val();
//                var ExaStatus = $("#ExaStatus").val();
//                var contractType = $("#contractType").val();
//                var beginDate = $("#comsigninGrid").data('yxgrid').options.extParam.beginDate;//开始时间
//                var endDate = $("#comsigninGrid").data('yxgrid').options.extParam.endDate;//截止时间
//                var ExaDT = $("#comsigninGrid").data('yxgrid').options.extParam.ExaDT;//建立时间
//                var areaNameArr = $("#comsigninGrid").data('yxgrid').options.extParam.areaNameArr;//归属区域
//                var orderCodeOrTempSearch = $("#comsigninGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//合同编号
//                var prinvipalName = $("#comsigninGrid").data('yxgrid').options.extParam.prinvipalName;//合同负责人
//                var customerName = $("#comsigninGrid").data('yxgrid').options.extParam.customerName;//客户名称
//                var customerType = $("#comsigninGrid").data('yxgrid').options.extParam.customerType;//客户类型
//                var orderNatureArr = $("#comsigninGrid").data('yxgrid').options.extParam.orderNatureArr;//合同属性
//                var DeliveryStatusArr = $("#comsigninGrid").data('yxgrid').options.extParam.DeliveryStatusArr;//是否有发货记录
//                var i = 1;
//                var colId = "";
//                var colName = "";
//                $("#comsigninGrid_hTable").children("thead").children("tr")
//                    .children("th").each(function() {
//                        if ($(this).css("display") != "none"
//                            && $(this).attr("colId") != undefined) {
//                            colName += $(this).children("div").html() + ",";
//                            colId += $(this).attr("colId") + ",";
//                            i++;
//                        }
//                    })
//
//                window.open("?model=contract_contract_contract&action=singInExportExcel&colId="
//                    + colId + "&colName=" + colName+ "&state=" + state + "&ExaStatus=" + ExaStatus + "&contractType=" + contractType
//                    + "&beginDate=" + beginDate + "&endDate=" + endDate + "&ExaDT=" + ExaDT
//                    + "&areaNameArr=" + areaNameArr + "&orderCodeOrTempSearch=" + orderCodeOrTempSearch
//                    + "&prinvipalName=" + prinvipalName + "&customerName=" + customerName
//                    + "&customerType=" + customerType
//                    + "&orderNatureArr=" + orderNatureArr
//                    + "&DeliveryStatusArr=" + DeliveryStatusArr
//                    + "&searchConditionKey="
//                    + searchConditionKey
//                    + "&searchConditionVal="
//                    + searchConditionVal
//                    + "&signStatusArr=1"
////								+ "&ExaStatus=完成,变更审批中"
////								+ "&states=2,3,4"
//                    + "&1width=200,height=200,top=200,left=200,resizable=yes")
//            }
//        }
        ],
        // 扩展右键菜单

        menusEx : [{
            text : '查看',
            icon : 'view',
            action : function(row) {
                showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                    + row.id + "&skey=" + row['skey_']);
            }
        }],

        // 列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'isFlag',
            display : '是否确认',
            sortable : true,
            align: 'center',
            width : 50
        }, {
            name : 'ExaDtOne',
            display : '建立时间',
            sortable : true,
            width : 80,
            hide : true
        }, {
            name : 'contractCode',
            display : '合同编号',
            sortable : true,
            width : 120,
            process : function(v, row) {
                return  '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
            }
        }, {
            name : 'contractName',
            display : '合同名称',
            sortable : true,
            width : 150,
            hide : true
        }, {
            name : 'paymentterm',
            display : '付款条件',
            sortable : true,
            width : 120
        }, {
            name : 'paymentPer',
            display : '付款百分比',
            sortable : true,
            width : 60,
            align: 'center',
            process : function(v) {
                return v + "%";
            }
        }, {
            name : 'money',
            display : '计划付款金额',
            sortable : true,
            align: 'right',
            width : 80,
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'proEndDate',
            display : '合同完成时间',
            sortable : true,
            width : 80
        }, {
            name : 'planInvoiceMoney',
            display : '计划开票金额',
            sortable : true,
            align: 'right',
            width : 80,
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'remark',
            display : '备注',
            sortable : true,
            hide : true
        }, {
            name : 'schedulePer',
            display : '进度百分比',
            align: 'center',
            sortable : true,
            process : function(v) {
                if(v > 0){
                    return v + "%";
                }else{
                    return "-";
                }
            }
        },{
            name : 'Tday',
            display : '财务T-日',
            align: 'center',
            sortable : true
//            process : function(v,row){
//                if(row.confirmStatus == '已确认' && row.checkStatus == '已验收'){
//                    return v+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="查看变更历史" src="images/icon/view.gif"></span>';
//                }else if(row.confirmStatus == '已确认'){
//                    return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="查看变更历史" src="images/icon/view.gif"></span>';
//                }else{
//                    return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="查看变更历史" src="images/icon/view.gif"></span>';
//                }
//            }
        }, {
            name : 'invoiceMoney',
            display : '开票金额',
            width : 80,
            sortable : true,
            align: 'right',
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'incomMoney',
            display : '到款金额',
            width : 80,
            sortable : true,
            align: 'right',
            process : function(v, row) {
                return moneyFormat2(v);
            }
        }, {
            name : 'unInvoiceMoney',
            display : '未开票金额',
            width : 80,
            align: 'right',
            process : function(v, row) {
            	var tempMoney = row.money - row.deductMoney - row.invoiceMoney;
                return moneyFormat2(tempMoney);
            }
        }, {
            name : 'unIncomMoney',
            display : '未到款金额',
            width : 80,
            align: 'right',
            process : function(v, row) {
                var tempMoney = row.money - row.deductMoney - row.incomMoney;
                return moneyFormat2(tempMoney);
            }
        }, {
            name : 'deductMoney',
            display : '扣款金额',
            width : 60,
            align: 'right',
            sortable : true,
            process : function(v, row) {
                return moneyFormat2(v);
            },
            hide : true
        }, {
            name : 'isCom',
            display : '是否完成',
            sortable : true,
            align: 'center',
            width : 60,
            process : function(v){
                if(v == 0){
                    return "未完成";
                }else{
                    return "已完成";

                }
            }
        }, {
            name : 'comDate',
            display : '完成时间',
            sortable : true,
            width : 60,
            hide : true
        }, {
            name : 'periodName',
            display : '奖惩状态',
            sortable : true,
            hide : true
        }, {
            name : 'confirmBtn',
            display : '确认T日',
            sortable : true,
            width : 80
        }, {
            name : 'changeTips',
            display : '变更原因',
            sortable : true,
            width : 320
        }],
        comboEx : [{
            text : '是否完成',
            key : 'isCom',
            data : [{
                text : '已完成',
                value : '1'
            }, {
                text : '未完成',
                value : '0'
            }]
        }, {
            text : '是否确认',
            key : 'isConfirm',
            value : '0',
            data : [{
                text : '未确认',
                value : '0'
            }, {
                text : '已确认',
                value : '1'
            }]
        },{
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
        }],
        /**
         * 快速搜索
         */
        searchitems : [{
            display : '合同编号',
            name : 'contractCode'
        }, {
            display : '合同名称',
            name : 'contractName'
        }]
    });
});


function confirmTday(id, k,isConfirm) {
    var tday = $("#tday" + k).val();
    var tdayOld = $("#tdayOld"+k).val();
    if (tday == '' || tday == undefined) {
        alert("请正确选择“财务T-日”");
    } else {
        if(isConfirm == '1'){
             if($("#changeTips"+k).val() == ''){
                 alert("请填写变更原因");
                 return false;
             }else{
                 var changeTips = $("#changeTips"+k).val();
             }
        }else{
            var changeTips = "";
        }
        $.ajax({
            type : 'POST',
            url : "?model=contract_contract_contract&action=updateTday",
            data : {
                id : id,
                tday : tday,
                tdayOld : tdayOld,
                changeTips : changeTips
            },
            async : false,
            success : function(data) {
                if (data == '1') {
                    alert("确认完成")
                    $("#financialList").yxgrid("reload");
                }else{
                    alert(data)
                }
            }
        });
    }
}


//查看变更历史
function changeHistory(id){
    showThickboxWin('?model=contract_contract_contract&action=showChanceHistory&id='
        + id
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
}
