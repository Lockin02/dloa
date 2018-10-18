var show_page = function(page) {
	$("#expenseGrid").yxgrid("reload");
};

//查看方法 - 兼容新旧报销单
function viewBill(id,billNo,isNew){
	if(isNew == '1'){
		showModalWin("?model=finance_expense_exsummary&action=toView&id="+ id ,1)
	}
}

$(function() {
    buttonsArr = [
        {
            text: "重置",
            icon: 'delete',
            action: function (row) {
                var listGrid = $("#expenseGrid").data('yxgrid');
                listGrid.options.extParam = {};
                $("#caseListWrap tr").attr('style',
                    "background-color: rgb(255, 255, 255)");
                listGrid.reload();
            }
        }
    ]
	$("#expenseGrid").yxgrid({
		model : 'finance_expense_expense',
		action : 'statistictPageJson',
		title : '费用管理',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		customCode : 'expense',
		showcheckbox : false,
		isOpButton : false,
        noCheckIdValue : 'noId',
		param : {
//			isNew: 1,
			DetailTypeArr: '4,5',//报销类型为售前与售后
			ExaStatusArr: '完成,变更审批中',
//			Status: '完成',
            feeManId: $('#userId').val(),
            salesAreaId: $('#areaId').val(),
			PayDTYear: $('#year').val(),//本年度
			ProjectNoNull: '1',
            needCountCol: true//是否需要显示汇总行
		},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '收单',
			name : 'recView',
			sortable : true,
			align : 'center',
			width : 30,
			process : function(v,row){
				if(row.needExpenseCheck == "1"){
					if(row.IsFinRec == '1'){
						return '<img title="部门收单['+ row.RecInvoiceDT +'] \n上交财务['+ row.HandUpDT +'] \n财务收单[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}else{
						if(row.isHandUp == "1"){
							return '<img title="部门收单['+ row.RecInvoiceDT +'] \n上交财务['+ row.HandUpDT +']" src="images/icon/ok2.png" style="width:15px;height:15px;">';
						}else{
							if(row.isNotReced == '0'){
								return '<img title="部门收单['+ row.RecInvoiceDT +']" src="images/icon/ok1.png" style="width:15px;height:15px;">';
							}
						}
					}
				}else{
					if(row.IsFinRec == '1'){
						return '<img title="财务收单[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}
				}
			}
		}, {
			display : '部门收单状态',
			name : 'isNotReced',
			sortable : true,
			width : 60,
			hide : true,
			process : function(v,row){
				if(v == '0'){
					return '已收单';
				}else{
					return '未收单';
				}
			}
		}, {
			display : '部门收单时间',
			name : 'RecInvoiceDT',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '单据上交状态',
			name : 'isHandUp',
			sortable : true,
			width : 60,
			hide : true,
			process : function(v,row){
				if(v == '1'){
					return '已上交';
				}else{
					return '未上交';
				}
			}
		}, {
			display : '单据上交时间',
			name : 'HandUpDT',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '财务收单状态',
			name : 'IsFinRec',
			sortable : true,
			width : 60,
			hide : true,
			process : function(v,row){
				if(v == '1'){
					return '已收单';
				}else{
					return '未收单';
				}
			}
		}, {
			display : '财务收单时间',
			name : 'FinRecDT',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '新报销单',
			name : 'isNew',
			sortable : true,
			width : 50,
			process : function(v){
				if(v == '1'){
					return '是';
				}else{
					return '否';
				}
			},
			hide : true
		}, {
			name : 'BillNo',
			display : '审批单号',
			sortable : true,
			width : 130,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='viewBill(\""+ row.id +"\",\""+ row.BillNo +"\",\""+ row.isNew +"\")'>"+ v +"</a>";
			}
		}, {
			name : 'DetailType',
			display : '报销类型',
			sortable : true,
			width : 70,
			process : function(v,row){
				if(v*1 > 0){
					switch(v){
//						case '0' : return '旧报销单';break;
						case '1' : return '部门费用';break;
						case '2' : return '合同项目费用';break;
						case '3' : return '研发费用';break;
						case '4' : return '售前费用';break;
						case '5' : return '售后费用';break;
						default : return v;
					}
				}else if(v != ""){
					switch(row.CostBelongTo){
						case '1' : return '部门费用';break;
						default : return '工程费用';break;
					}
				}
			}
		}, {
			name : 'CostManName',
			display : '报销人',
			sortable : true,
			width : 90
		}, {
			name : 'CostDepartName',
			display : '报销人部门',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'CostManCom',
			display : '报销人公司',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'salesArea',
			display : '费用归属区域',
			sortable : true,
			width : 75
		},{
            name : 'CostBelongDeptName',
            display : '费用归属部门',
            sortable : true,
            width : 75
        },{
			name : 'CostBelongCom',
			display : '费用归属公司',
			sortable : true,
			width : 75
		}, {
			name : 'Amount',
			display : '报销金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'feeRegular',
			display : '常规费用',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'feeSubsidy',
			display : '补贴费用',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'invoiceMoney',
			display : '发票金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'invoiceNumber',
			display : '发票数量',
			sortable : true,
			width : 60,
			hide : true
		},
		// 	{
		// 	name : 'isProject',
		// 	display : '项目报销',
		// 	sortable : true,
		// 	process : function(v){
		// 		if(v == '1'){
		// 			return '是';
		// 		}else{
		// 			return '否';
		// 		}
		// 	},
		// 	width : 60,
		// 	hide : true
		// }, {
		// 	name : 'ProjectNO',
		// 	display : '项目编号',
		// 	sortable : true,
		// 	width : 150,
		// 	hide : true
		// }, {
		// 	name : 'projectName',
		// 	display : '项目名称',
		// 	sortable : true,
		// 	width : 150,
		// 	hide : true
		// }, {
		// 	name : 'proManagerName',
		// 	display : '项目经理',
		// 	sortable : true,
		// 	width : 80,
		// 	hide : true
		// },
			{
			name : 'chanceCode',
			display : '商机编号',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'chanceName',
			display : '商机名称',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'CustomerType',
			display : '客户类型',
			sortable : true,
			hide : true
		}, {
			name : 'Purpose',
			display : '事由',
			sortable : true,
			width : 180
		}, {
			name : 'InputManName',
			display : '录入人',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'Status',
			display : '单据状态',
			sortable : true,
			width : 70
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			width : 70,
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批日期',
			width : 80,
			sortable : true
		}, {
			name : 'InputDate',
			display : '录入时间',
			sortable : true,
			width : 130
		}, {
			name : 'subCheckDT',
			display : '提交检查时间',
			sortable : true,
			width : 130,
			hide : true
		}, {
			name : 'UpdateDT',
			display : '更新时间',
			sortable : true,
			width : 130,
			hide : true
		}],
		//打开的是expense中的方法 -- 情形比较特殊
		toViewConfig : {
			showMenuFn : function(row){
				if( row.isNew == '1'){
					return true;
				}
				return false;
			},
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
//				showModalWin("?model=finance_expense_expense&action=toView&id="+ rowData.id )
				viewBill(row.id,row.BillNo,row.isNew);
			}
		},
//		menusEx : [{
//				text: "查看明细",
//				icon: 'view',
//				showMenuFn : function(row){
//					if( $("#detailLimit").val() == '1'){
//						return true;
//					}
//					return false;
//				},
//				action: function(row) {
//					showModalWin("?model=finance_expense_expense&action=toView&id="+ row.id )
//				}
//			},{
//				text: "审批情况",
//				icon: 'view',
//				showMenuFn : function(row){
//					if(row.Status != '编辑' && row.Status != '部门检查'){
//						return true;
//					}
//					return false;
//				},
//				action: function(row) {
//					if(row.isNew == '1'){
//						showThickboxWin('controller/common/readview.php?itemtype=cost_summary_list'
//							+ '&pid=' + row.id
//					        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//					}else{
//						if(row.ExaStatus != ''){
//							showThickboxWin('controller/common/readview.php?itemtype=cost_summary_list'
//								+ '&pid=' + row.id
//						        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//							var URL="controller/common/readview.php?itemtype=cost_summary_list&pid=" + row.id;
//							var myleft=(screen.availWidth-500)/2;
//							window.open(URL,"read_notify","height=450,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
//						}else{
//							showThickboxWin('general/costmanage/reim/examine_view.php?&status=出纳付款'
//								+ '&BillNo=' + row.BillNo
//						        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//						}
//					}
//				}
//			},{
//				text: "打单",
//				icon: 'print',
//				showMenuFn : function(row){
//					if(row.isNew == "1"){
//						if(row.ExaStatus != "编辑" && row.ExaStatus != '打回' && $("#printLimit").val() == "1"){
//							return true;
//						}
//					}else{
//						if(row.Status != '编辑' && row.Status != '部门检查'){
//							return true;
//						}
//					}
//					return false;
//				},
//				action: function(row) {
//					showOpenWin("?model=cost_bill_billcheck&action=print_bill&billno=" + row.BillNo ,1);
//				}
//			}
//		],
		//过滤数据
		comboEx : [{
			text : '报销类型',
			key : 'DetailType',
			data : [
//			{
//				text : '部门费用',
//				value : '1'
//			}, {
//				text : '合同项目费用',
//				value : '2'
//			}, {
//				text : '研发费用',
//				value : '3'
//			}, 
			{
				text : '售前费用',
				value : '4'
			}, {
				text : '售后费用',
				value : '5'
			}]
		}
//		,{
//			text : '单据状态',
//			key : 'Status',
//			value : '完成',
//			data : [{
//				text : '编辑',
//				value : '编辑'
//			}, {
//				text : '部门检查',
//				value : '部门检查'
//			}, {
//				text : '等待确认',
//				value : '等待确认'
//			}, {
//				text : '部门审批',
//				value : '部门审批'
//			}, {
//				text : '财务审核',
//				value : '财务审核'
//			}, {
//				text : '出纳付款',
//				value : '出纳付款'
//			}, {
//				text : '打回',
//				value : '打回'
//			}, {
//				text : '完成',
//				value : '完成'
//			}]
//		}
		],
        buttonsEx: buttonsArr,
		// 高级搜索
        advSearchOptions: {
            modelName: 'expenseSearch',
            // 选择字段后进行重置值操作
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
                $valInput.yxselect_user("remove");
            },
            searchConfig: [
                {
		            name : '出纳付款日期',
		            value : 'PayDT',
					type:'date',
					changeFn : function($t, $valInput) {
						$valInput.click(function() {
							WdatePicker({
								dateFmt : 'yyyy-MM-dd'
							});
						});
					}
		        },
                {
                    name: '销售负责人',
                    value: 'CostBelonger',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxselect_user({
                            hiddenId: 'CostBelonger' + rowNum,
                            nameCol: 'CostBelonger',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: false
                            }
                        });
                    }
                },
                {
                    name: '报销人',
                    value: 'costManName',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxselect_user({
                            hiddenId: 'costMan' + rowNum,
                            nameCol: 'costManName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: false
                            }
                        });
                    }
                },
                {
                    name: '费用归属区域',
                    value: 'c.salesArea',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaCode' + rowNum,
                            nameCol: 'areaName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: false
                            }
                        });
                    }
                },
            ]
        },
		//快速搜索
		searchitems : [{
			display : "审批单号",
			name : 'BillNoSearch'
		}, {
			display : "项目名称",
			name : 'projectNameSearch'
		}, {
			display : "项目编号",
			name : 'projectCodeSearch'
		}, {
			display : "商机编号",
			name : 'chanceCodeSearch'
		}, {
			display : "商机名称",
			name : 'chanceNameSearch'
		}, {
			display : "合同编号",
			name : 'contractCodeSearch'
		}, {
			display : "合同名称",
			name : 'contractNameSearch'
		}, {
			display : "报销金额",
			name : 'Amount'
		}, {
			display : "报销人",
			name : 'costManNameSearch'
		}, {
			display : "事由",
			name : 'PurposeSearch'
		}],
		sortname : "c.InputDate"
	});
});