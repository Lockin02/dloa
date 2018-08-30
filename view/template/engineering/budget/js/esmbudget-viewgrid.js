$(function () {
    var projectId = $("#projectId").val();
    var act = $("#act").val();

    if (act == 'searchJson2') {
        $("#oldSelect").show();
    }

    $("#esmbudgetGrid").yxeditgrid({
        url: '?model=engineering_budget_esmbudget&action=' + act,
        type: 'view',
        param: {
            projectId: projectId
        },
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            name: 'parentName',
            display: '费用大类',
            align: 'left',
            width: 200,
            process: function (v, row) {
                if (row.id == 'noId') {
                    return '<b>' + v + '</b>';
                }
                switch (row.budgetType) {
                    case '' :
                        return v;
                    case 'budgetField' :
                        return '<span class="blue">' + v + '</span>';
                    case 'budgetPerson' :
                        return '<span class="green">' + v + '</span>';
                    case 'budgetOutsourcing' :
                        v = v == '外包预算' ? '外包成本' : v;
                        return '<span style="color:gray;">' + v + '</span>';
                    case 'budgetOther' :
                        v = v == '其他预算' ? '其他成本' : v;
                        return v;
                    case 'budgetEqu' :
                        v = v == '设备预算' ? '设备成本' : v;
                        return '<span style="color:brown;">' + v + '</span>';
                    case 'budgetTrial' :
                        return '<span style="color:orange;">' + v + '</span>';
                    case 'budgetFlights' :
                        return '<span style="color:lightseagreen;">' + v + '</span>';
                    default:
                        return v;
                }
            }
        }, {
            name: 'budgetName',
            display: '费用小类',
            align: 'left',
            width: 200,
            process: function (v, row) {
                switch (row.budgetType) {
                    case 'budgetPerson' :
						if (row.isImport && row.isImport == "1") {//由项目费用维护导入的数据
							return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_cost_esmcostmaintain" +
								"&action=toSearchDetailList&parentName=" + row.realName + "&budgetName=" + row.budgetName + "&projectId=" + projectId +
								"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>" + row.detCount + "</span></a>";
						} else {
                            if (row.parentName == '人力成本（已录补贴）') {
                                var url = "general/costmanage/statistics/project/index_type.php?seaPro=" + $("#projectCode").val() + "&checkType=now";
                                return '<a href="javascript:void(0);" onclick="window.open(\'' + url + '\')">' + v + "</a>";
                            } else if (row.detCount) {
                                return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_budget_esmbudget" +
                                    "&action=toSearchDetailList&parentName=" + row.realName + "&projectId=" + projectId + "&budgetType=" + row.budgetType + "&budgetName=" +
                                    "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>[" + row.detCount + "]</span></a>";
                            } else {
                                return "<span style='color:green'>" + v + "</span>";
                            }
						}
					case 'budgetFlights' :
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_records_esmflights" +
							"&action=toSearchList&projectId=" + projectId +
							"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=400\")'>" + v + "</a>";
					case 'budgetTrial' :
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_budget_esmbudget&action=toViewList&projectId=" + row.projectId + "\",1," + row.projectId + ")'>" + v + "</a>";
                    case 'budgetField' :
                        if(row.isRentalCarAuditingCost && row.isRentalCarAuditingCost == 1){
                            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=outsourcing_vehicle_allregister" +
                                "&action=toRecordPage&projectId=" + projectId + "&listOnly=1" +
                                "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=860\")'>" + v + "</a>";
                        }else{
                            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_budget_esmbudget" +
                                "&action=toFeeDetail&projectId=" + projectId + "&budgetName=" + row.budgetName + "&budgetType=" + row.budgetType +
                                "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=650\")'>" + v + "</a>";
                        }
                    case '' :
                    case 'budgetEqu' :
                    default :
                        if (row.id == 'budgetFlights') {
                            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_records_esmflights" +
                                "&action=toSearchList&projectId=" + projectId +
                                "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=400\")'>" + v + "</a>";
                        } else if (row.isImport && row.isImport == "1") {//由项目费用维护导入的数据
                            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_cost_esmcostmaintain" +
                                "&action=toSearchDetailList&parentName=" + row.parentName + "&budgetName=" + row.budgetName + "&projectId=" + projectId +
                                "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>" + row.detCount + "</span></a>";
                        } if (row.id == 'budgetEqu' && row.actFee > 0) {
                            return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_resources_esmdevicefee" +
                                "&action=toSearchDetailList&projectId=" + projectId + "&budgetName=" + row.budgetName +
                                "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=500\")'>" + v + "</a>";
                        } else {
                            return v;
                        }
                }
            }
        }, {
            name: 'projectCode',
            display: '项目编号',
            type: 'hidden'
        }, {
            name: 'projectName',
            display: '项目名称',
            type: 'hidden'
        }, {
            name: 'amount',
            display: '预算',
            align: 'right',
            process: function (v) {
                if (v == 0 || v == "") {
                    return '--';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80
        }, {
            name: 'actFee',
            display: '决算',
            align: 'right',
            process: function (v) {
                if (v == 0 || v == "") {
                    return '--';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80
        }, {
            name: 'amountWait',
            display: '待审核预算',
            align: 'right',
            process: function (v, row) {
                if (row.isImport == 1) {
                    if (row.status == 0) {//导入的数据,未审核的数据标红
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else {//导入的数据,已审核的数据标绿
                        if (!v || v == 0 || v == "") {
                            return '--';
                        } else {
                            return moneyFormat2(v);
                        }
                    }
                } else {
                    if (!v || v == 0 || v == "") {
                        return '--';
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            width: 80
        }, {
            name: 'actFeeWait',
            display: '待审核决算',
            align: 'right',
            process: function (v, row) {
                if (row.isImport == 1) {//导入的数据
                    if (row.status == 0) {//未审核的数据标红
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else {
                        if (!v || v == 0 || v == "") {
                            return '--';
                        } else {
                            return moneyFormat2(v);
                        }
                    }
                } else {
                    if (!v || v == 0 || v == "") {
                        return '--';
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            width: 80
        }, {
            name: 'feeProcess',
            display: '费用进度',
            align: 'right',
            process: function (v, row) {
                if (row.isImport == 1) {
                    if (row.status == 0) {//导入的数据,未审核的数据标红
                        return "<span class='red'>" + v + " %</span>";
                    } else {//导入的数据,已审核的,超过100%的数据标红，不超的标绿
                        if (v * 1 > 100) {
                            return "<span class='red'>" + v + " %</span>";
                        } else {
                            return "<span class='green'>" + v + " %</span>";
                        }
                    }
                } else {
                    if (v * 1 > 100) {
                        return "<span class='red'>" + v + " %</span>";
                    } else {
                        return v + " %";
                    }
                }
            },
            width: 80
        }, {
            name: 'remark',
            display: '备注说明',
            align: 'left',
            process: function (v, row) {
                return v;
            },
            width: 350
        }],
        event: {
            reloadData: function() {
                var i = 0;
                $("input[id^='esmbudgetGrid_cmp_id']").each(function() {
                    var tr = $(this).parent().parent();
                    if ($(this).val() == "noId") {
                        // 渲染背景色
                        tr.css('background', "rgb(226, 237, 255)");
                        tr.children().eq(0).html("");
                    } else {
                        i++;
                        tr.children().eq(0).html(i);
                    }
                });
            }
        }
    });

    $("#budgetType").change(function () {
        var projectId = $("#projectId").val();
        var budgetType = $("#budgetType").val();
        var paramObj = {
            projectId: projectId,
            budgetType: budgetType
        };
        $("#esmbudgetGrid").yxeditgrid("setParam", paramObj).yxeditgrid("processData");
    });
});

//导出Excel
function exportExcel(){
	var url = "?model=engineering_budget_esmbudget&action=exportExcel"
		+ "&projectId=" + $("#projectId").val()
		+ "&budgetType=" + $("#budgetType").val();
	showOpenWin(url, 1 ,200 , 200);
}
