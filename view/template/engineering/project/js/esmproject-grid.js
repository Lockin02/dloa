var show_page = function () {
    $("#esmprojectGrid").yxgrid("reload");
};

$(function () {
	// 决算版本获取
	var feeDates = [];

	$.ajax({
		type: 'POST',
		url: '?model=engineering_records_esmfielddetail&action=getDates',
		async: false,
		success: function(data) {
			if (data != "") {
				feeDates = eval("(" + data + ")");
			}
		}
	});

    // 收入版本获取
    var incomeDates = [];

    $.ajax({
        type: 'POST',
        url: '?model=engineering_records_esmincome&action=getDates',
        async: false,
        success: function(data) {
            if (data != "") {
                incomeDates = eval("(" + data + ")");
            }
        }
    });

    //表头按钮数组
    var buttonsArr = [];

    //表头按钮数组
    var excelInArr = {
        name: 'exportIn',
        text: "导入",
        icon: 'excel',
        action: function () {
            showThickboxWin("?model=engineering_project_esmproject&action=toExcelIn"
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
        }
    };

    var projectArr = {
        name: 'project',
        text: "更新项目",
        icon: 'add',
        action: function () {
            showThickboxWin("?model=engineering_project_esmproject&action=toUpdateProject"
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=405&width=800")
        }
    };

    $.ajax({
        type: 'POST',
        url: '?model=engineering_project_esmproject&action=getLimitArr',
        async: false,
        success: function (data) {
            if (data != "") {
                data = eval("(" + data + ")");

                if (data['更新项目'] && data['更新项目'] == 1) {
                    buttonsArr.push(projectArr);
                }

                if (data['导入权限'] && data['导入权限'] == 1) {
                    buttonsArr.push(excelInArr);
                }

                // 导入部分
                var excelArr = {
                    name: 'excel',
                    text: "导出",
                    icon: 'excel',
                    items: []
                };

                if (data['导出权限-项目汇总'] && data['导出权限-项目汇总'] == 1) {
                    excelArr['items'].push({
                        text: '项目汇总表',
                        icon: 'excel',
                        action: function () {
                            var thisGrid = $("#esmprojectGrid").data('yxgrid');
                            var colName = [];
                            var colCode = [];
                            var colModel = thisGrid.options.colModel;
                            for (var i = 0; i < colModel.length; i++) {
                                if (!colModel[i].hide) {
                                    colName.push(colModel[i].display);
                                    colCode.push(colModel[i].name);
                                }
                            }
                            var url = "?model=engineering_project_esmproject&action=exportSummary"
                                + '&status=' + filterUndefined(thisGrid.options.param.status)
                                + '&attribute=' + filterUndefined(thisGrid.options.param.attribute)
                                + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                            if (thisGrid.options.qtype) {
                                url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
                            }
                            window.open(url, "", "width=200,height=200,top=200,left=200");
                        }
                    });
                }

                if (data['导出权限-项目'] && data['导出权限-项目'] == 1) {
                    excelArr['items'].push({
                        text: '项目信息-管理员',
                        icon: 'excel',
                        action: function () {
                            var advParam = $("#esmprojectGrid").yxgrid('getAdvSearchArr');

                            var feeBeginDate = '';
                            var feeEndDate = '';
                            var incomeBeginDate = '';
                            var incomeEndDate = '';
                            if (advParam) {
                                for (var i = 0; i < advParam.length; i++) {
                                    if (advParam[i].searchField == 'feeBeginDate') {
                                        feeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'feeEndDate') {
                                        feeEndDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeBeginDate') {
                                        incomeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeEndDate') {
                                        incomeEndDate = advParam[i].value;
                                    }
                                }
                            }
                            window.open(
                                "?model=engineering_project_esmproject&action=exportExcel&feeBeginDate="
                                    + feeBeginDate + "&feeEndDate=" + feeEndDate
                                    + "&incomeBeginDate=" + incomeBeginDate + "&incomeEndDate=" + incomeEndDate,
                                "", "width=200,height=200,top=200,left=200");
                        }
                    }, {
                        text: '项目信息(Excel07)-管理员',
                            icon: 'excel',
                            action: function () {
                            var advParam = $("#esmprojectGrid").yxgrid('getAdvSearchArr');
                            var feeBeginDate = '';
                            var feeEndDate = '';
                            var incomeBeginDate = '';
                            var incomeEndDate = '';
                            if (advParam) {
                                for (var i = 0; i < advParam.length; i++) {
                                    if (advParam[i].searchField == 'feeBeginDate') {
                                        feeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'feeEndDate') {
                                        feeEndDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeBeginDate') {
                                        incomeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeEndDate') {
                                        incomeEndDate = advParam[i].value;
                                    }
                                }
                            }
                            window.open(
                                "?model=engineering_project_esmproject&action=exportExcel&excelType=07&feeBeginDate="
                                    + feeBeginDate + "&feeEndDate=" + feeEndDate
                                    + "&incomeBeginDate=" + incomeBeginDate + "&incomeEndDate=" + incomeEndDate,
                                "", "width=200,height=200,top=200,left=200");
                        }
                    }, {
                        text: '项目预决算-管理员',
                        icon: 'excel',
                        action: function () {
                            show_page();
                            window.open(
                                "?model=engineering_budget_esmbudget&action=exportAllExcel",
                                "", "width=200,height=200,top=200,left=200");
                        }
                    }, {
                        text: '项目预决算(CSV)-管理员',
                        icon: 'excel',
                        action: function () {
                            show_page();
                            window.open(
                                "?model=engineering_budget_esmbudget&action=exportAllExcelCSV",
                                "", "width=200,height=200,top=200,left=200");
                        }
                    });
                }

                if (data['导出权限-设备'] && data['导出权限-设备'] == 1) {
                    excelArr['items'].push({
                        text: '设备信息-管理员',
                        icon: 'excel',
                        action: function () {
                            window.open(
                                "?model=engineering_device_esmdevice&action=exportDeviceExcel",
                                "", "width=200,height=200,top=200,left=200");
                        }
                    });
                }

                // 如果有导出功能载入，那么就设置导出菜单
                if (excelArr['items'].length > 0) {
                    buttonsArr.push(excelArr);
                }
            }
        }
    });

    $("#esmprojectGrid").yxgrid({
        model: 'engineering_project_esmproject',
        title: '项目汇总表-实时',
        isDelAction: false,
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        showcheckbox: false,
        customCode: 'esmprojectGridNew',
        isOpButton: false,
        autoload: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'attributeName',
            display: '项目属性',
            width: 70,
            hide: true
        }, {
            name: 'productLineName',
            display: '执行区域',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'newProLineName',
            display: '产品线',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'contractId',
            display: '合同id',
            sortable: true,
            hide: true
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true,
            width: 140,
            process: function (v, row) {
                return (row.contractId == "0" || row.contractId == "") && row.contractType != 'GCXMYD-04' ? "<span style='color:blue' title='未关联合同号的项目'>" + v + "</span>" : v;
            }
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if(row.pType == 'esm')
                    return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                else
                    var cid = row.id.substring(1);
                return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_conproject_conproject&action=viewTab&id=" + cid + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            width: 160,
            process: function (v, row) {
                if(row.attribute == 'GCXMSS-01'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_trialproject_trialproject&action=viewTab&id='
                        + row.contractId
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            },
            hide: true
        }, {
            name: 'managerName',
            display: '项目经理',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'planBeginDate',
            display: '预计开始日期',
            sortable: true,
            width: 80
        }, {
            name: 'planEndDate',
            display: '预计结束日期',
            sortable: true,
            width: 80
        }, {
            name: 'statusName',
            display: '项目状态',
            sortable: true,
            align: 'center',
            width: 60
        }, {
            name: 'categoryName',
            display: '项目类别',
            sortable: true,
            width: 50,
            hide: true
        }, {
            name: 'projectProcess',
            display: '项目进度',
            process: function (v) {
                return v != "" ? v + ' %' : "--";
            },
            align: 'right',
            width: 70
        }, {
            name: 'contractMoney',
            display: '合同金额',
            sortable: true,
            process: function (v, row) {
                if (row.contractType == 'GCXMYD-01') {
                    return moneyFormat2(v);
                } else {
                    return '--';
                }
            },
            align: 'right',
            width: 70,
            hide: true
        }, {
            name: 'projectMoneyWithTax',
            display: '项目金额',
            sortable: true,
            process: function (v, row) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'estimates',
            display: '项目概算',
            process: function (v, row) {
                // if (row.contractType == 'GCXMYD-01') {
                //     return moneyFormat2(v);
                // }
                if(v != ''){
                    return moneyFormat2(v);
                }else {
                    return '--';
                }
            },
            align: 'right',
            width: 70,
            hide: true
        }, {
            name: 'budgetAll',
            display: '总预算',
            process: function (v) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'feeAll',
            display: '总成本',
            process: function (v) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'curIncome',
            display: '当前收入',
            process: function (v, row) {
                if (row.contractType == 'GCXMYD-01') {
                    return moneyFormat2(v);
                } else {
                    return '0';
                }
            },
            align: 'right',
            width: 70
        }, {
            name: 'grossProfit',
            display: '项目毛利',
            process: function (v, row) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'exgross',
            display: '毛利率',
            process: function (v, row) {
                if (row.contractType == 'GCXMYD-01') {
                    return v + " %";
                } else {
                    return '--';
                }
            },
            align: 'right',
            width: 70
        }, {
            name: 'outsourcingTypeName',
            display: '外包类型',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'CPI',
            display: 'CPI',
            width: 70,
            align: 'right',
            hide: true
        }, {
            name: 'SPI',
            display: 'SPI',
            width: 70,
            align: 'right',
            hide: true
        }, {
            name: 'feeAllProcess',
            display: '费用进度',
            process: function (v) {
                return v != "" ? v + ' %' : "--";
            },
            align: 'right',
            width: 70,
            hide: true
        }],
        buttonsEx: buttonsArr,
        // 扩展右键菜单
        menusEx: [{
            text: '查看项目',
            icon: 'view',
            action: function (row) {
            	if(row.pType == 'esm'){
            	   showModalWin("?model=engineering_project_esmproject&action=viewTab&id="+ row.id + "&skey=" + row.skey_, 1, row.id);
            	}else{
            		var cid = row.id.substring(1);
            	    showModalWin("?model=contract_conproject_conproject&action=viewTab&id="+ cid, 1, row.id);
            	}
            }
        }, {
            text: '编辑项目',
            icon: 'edit',
            showMenuFn: function (row) {
                return $("#editProjectLimit").val() == "1" && row.pType == 'esm';
            },
            action: function (row) {
            	  showModalWin("?model=engineering_project_esmproject&action=toEditRight&id="
                + row.id + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            name: 'aduit',
            text: '审批情况',
            icon: 'view',
            showMenuFn: function (row) {
                return (row.ExaStatus == "完成" || row.ExaStatus == "打回") && row.pType == 'esm';
            },
            action: function (row) {
                    showThickboxWin("controller/common/readview.php?itemtype=oa_esm_project&pid="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
            }
        }, {
            name: 'incomeType',
            text: '更新收入确认方式',
            icon: 'edit',
            action: function (row) {
                showThickboxWin("?model=engineering_baseinfo_esmcommon&action=updateIncomeType&id="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
            }
        }, {
            name: 'outsourcing',
            text: '外包申请',
            icon: 'add',
            showMenuFn: function (row) {
                return row.pType == 'esm' && (row.status == "GCXMZT02" || row.status == "GCXMZT01");
            },
            action: function (row) {
                window.open("?model=outsourcing_outsourcing_apply&action=toAddFromProject&projectId="
                    + row.id);
            }
        }, {
            name: 'openClose',
            text: '开启关闭项目',
            icon: 'edit',
            showMenuFn: function (row) {
                return row.pType == 'esm' && $("#openCloseLimit").val() == '1' && row.ExaStatus == '完成';
            },
            action: function (row) {
                showThickboxWin("?model=engineering_project_esmproject&action=toOpenClose&id="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
            }
        }],
        // 高级搜索
        advSearchOptions: {
            modelName: 'esmprojectSearch',
            // 选择字段后进行重置值操作
            selectFn: function ($valInput) {
                $valInput.yxselect_user("remove");
                $valInput.yxcombogrid_office("remove");
            },
            searchConfig: [{
                name: '项目名称',
                value: 'c.projectName'
            }, {
                name: '项目编号',
                value: 'c.projectCode'
            }, {
                name: '归属区域',
                value: 'c.officeName',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#officeId" + rowNum)[0]) {
                        var $hiddenCmp = $("<input type='hidden' id='officeId" + rowNum + "'/>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxcombogrid_office({
                        hiddenId: 'officeId' + rowNum,
                        height: 200,
                        width: 550,
                        gridOptions: {
                            showcheckbox: false
                        }
                    });
                }
            }, {
                name: '项目经理',
                value: 'c.managerName',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#managerId" + rowNum)[0]) {
                        var $hiddenCmp = $("<input type='hidden' id='managerId" + rowNum + "'/>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxselect_user({
                        hiddenId: 'managerId' + rowNum,
                        height: 200,
                        width: 550,
                        gridOptions: {
                            showcheckbox: false
                        }
                    });
                }
            }, {
                name: '项目状态',
                value: 'c.status',
                type: 'select',
                datacode: 'GCXMZT'
            }, {
                name: '网络性质',
                value: 'nature',
                type: 'select',
                datacode: 'GCXMXZ'
            }, {
                name: '外包类型',
                value: 'outsourcing',
                type: 'select',
                datacode: 'WBLX'
            }, {
                name: '长/短期',
                value: 'cycle',
                type: 'select',
                datacode: 'GCCDQ'
            }, {
                name: '项目类别',
                value: 'category',
                type: 'select',
                datacode: 'XMLB'
            }, {
				name: '决算开始日期',
				value: 'feeBeginDate',
				type: 'select',
				options: feeDates
			}, {
				name: '决算结束日期',
				value: 'feeEndDate',
				type: 'select',
				options: feeDates
			}, {
                name: '收入开始日期',
                value: 'incomeBeginDate',
                type: 'select',
                options: incomeDates
            }, {
                name: '收入结束日期',
                value: 'incomeEndDate',
                type: 'select',
                options: incomeDates
            }]
        },
        searchitems: [{
            display: '办事处',
            name: 'officeName'
        }, {
            display: '项目编号',
            name: 'projectCodeSearch'
        }, {
            display: '项目名称',
            name: 'projectName'
        }, {
            display: '项目经理',
            name: 'managerName'
        }, {
            display: '业务编号',
            name: 'rObjCodeSearch'
        }, {
            display: '鼎利合同号',
            name: 'contractCodeSearch'
        }],
        // 审批状态数据过滤
        comboEx: [{
            text: "项目属性",
            key: 'attribute',
            datacode: 'GCXMSS'
        }, {
            text: "项目状态",
            key: 'status',
            datacode: 'GCXMZT'
        }],
        // 默认搜索字段名
        sortname: "c.updateTime",
        // 默认搜索顺序 降序
        sortorder: "DESC"
    });
});

//构建查看版本号
function createVersionNum() {
    var storeYear = $("#storeYear").val();
    var storeMonth = $("#storeMonth").val();

    // $.ajax({
    //     type: "POST",
    //     url: "?model=engineering_records_esmrecord&action=getVersionArr",
    //     data: {storeYear: storeYear, storeMonth: storeMonth},
    //     async: false,
    //     success: function(data) {
    //         $("#view").append("<div id='verSelect'></div>");
    //         if (data != 0) {
    //             $("#verSelect").html("<tr><td class='form_text_left'>版本号</td>" +
    //                 "<td class='form_view_right'>" +
    //                 "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
    //                 data +
    //                 "</select></td></tr>");
    //         } else {
    //             $("#verSelect").html("<tr><td class='form_text_left'>版本号</td>" +
    //                 "<td class='form_view_right'>" +
    //                 "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
    //                 "<option>暂无数据</option>" +
    //                 "</select></td></tr>");
    //         }
    //     }
    // });
}