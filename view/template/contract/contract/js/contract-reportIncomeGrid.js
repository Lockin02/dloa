var show_page = function(page) {
	$("#incomeGrid").yxgrid("reload");
};
$(function() {
    var periodArr = [];
    var periodDefault = '';
    $.ajax({
        type: "POST",
        url: "?model=finance_period_period&action=getAllPeriod",
        data: {effectiveCost: 1},
        async: false,
        success: function (data) {
            periodArr = eval("(" + data + ")");
            if (periodArr.length > 0) {
                var newPeriod = [];
                for (var i = 0; i < periodArr.length; i++) {
                    newPeriod.push({
                        value: periodArr[i].text,
                        text: periodArr[i].text
                    });
                }
                periodArr = newPeriod;
                periodDefault = periodArr[0].value;
            }
        }
    });

	buttonsArr = [{
                text: "重置",
                icon: 'delete',
                action: function (row) {
                    var listGrid = $("#incomeGrid").data('yxgrid');
                    listGrid.options.extParam = {};
                    $("#caseListWrap tr").attr('style',
                        "background-color: rgb(255, 255, 255)");
                    listGrid.reload();
                }
            },{
            name: 'export',
            text: "报表导出",
            icon: 'excel',
            action: function (row) {
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#incomeGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#incomeGrid").data('yxgrid').options.searchParam[t];
                    }
                }

                var statesStr = ($("#states").val() != '')? "&states=" + $("#states").val() : '';
                var isIncomeStr = ($("#isIncome").val() != '')? "&isIncome=" + $("#isIncome").val() : '';
                var overPointStr = ($('#overPoint').val() != '')? "&overPoint=" + $('#overPoint').val() : '';

                var i = 1;
                var colId = "";
                var colName = "";
                $("#incomeGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    })
                window
                    .open("?model=contract_contract_contract&action=reportIncomeExcel&colId="
                        + colId
                        + "&colName="
                        + colName
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal
                        + overPointStr
                        + statesStr
                        + isIncomeStr
                    )
            }
		}],
	$("#incomeGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'reportIncomeJson',
		title : '应收账款分析',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		leftLayout: true,
		showcheckbox : false,
		// 扩展右键菜单
		menusEx : [],
		//		lockCol : ['conflag', 'exeStatus'],// 锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			able : true,
			hide : true
		}, {
			name : 'formBelongName',
			display : "所属公司",
			able : true,
			width : 80
		}, {
			name : 'areaName',
			display : "所属区域",
			able : true,
			width : 80
		}, {
			name : 'accMoney',
			display : '应收款总额',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'conTMoney',
			display : '已确定T日应收款',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeMoney',
			display : '已回款总额',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unAccMoney',
			display : '未回款总额',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'rondIncome',
			display : '本年度回款率',
			able : true,
			width : 100
		}, {
			name : 'unInomeMoney',
			display : '逾期应收款',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney_q',
			display : '本季度应收款',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney_nq',
			display : '下季度应收款',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney_aq',
			display : '下季度以后应收款',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'noTMoney',
			display : '未确定T日应收款',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '所属公司',
			name : 'formBelongName'
		}, {
			display : '所属区域',
			name : 'areaName'
		}],
		name : "createTime",
		buttonsEx: buttonsArr,
		//分类搜索
        comboEx: [
            {
                text: '合同状态',
                key: 'states',
                value: '2,3,4',
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
                    },
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
                text:'逾期日期节点',
                key: 'overPoint',
                data: periodArr
            }
        ],
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
                }
                ,
                {
                    name: '客户类型',
                    value: 'c.customerTypeName',
                    type: 'select',
                    datacode: 'KHLX'
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
                    name: '签约主体',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
	});

});
