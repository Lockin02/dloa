var show_page = function(page) {
	$("#expenseGrid").yxgrid("reload");
};

//查看方法 - 兼容新旧报销单
function viewBill(id,billNo,isNew){
	if(isNew == '1'){
		showModalWin("?model=finance_expense_exsummary&action=toView&id="+ id )
	}else{
		showOpenWin("general/costmanage/reim/summary_detail.php?status=出纳付款&BillNo="+ billNo )
	}
}

$(function() {
	$("#expenseGrid").yxgrid({
		model : 'finance_expense_expense',
		title : '项目检查列表',
		isDelAction : false,
		param : {"isNew" : 1 ,'needExpenseCheck' : 1,'isPush' : '1','projectId' : $("#projectId").val()},
		customCode : 'checkproject',
		showcheckbox : false,
		isAddAction : false,
		isOpButton : false,
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
				}else{
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
			name : 'CostBelongDeptName',
			display : '费用归属部门',
			sortable : true,
			width : 75,
			hide : true
		}, {
			name : 'CostBelongCom',
			display : '费用归属公司',
			sortable : true,
			width : 75,
			hide : true
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
			hide : true
		}, {
			name : 'invoiceNumber',
			display : '发票数量',
			sortable : true,
			width : 60,
			hide : true
		}, {
			name : 'CheckAmount',
			display : '检查金额',
			sortable : true,
			hide : true,
			width : 80,
			process : function(v,row){
				if(row.Amount*1 != v*1){
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}else{
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'isProject',
			display : '项目报销',
			sortable : true,
			process : function(v){
				if(v == '1'){
					return '是';
				}else{
					return '否';
				}
			},
			width : 60,
			hide : true
		}, {
			name : 'ProjectNO',
			display : '项目编号',
			sortable : true,
			width : 150,
			hide : true
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 150,
			hide : true
		}, {
			name : 'proManagerName',
			display : '项目经理',
			sortable : true,
			width : 80,
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
			width : 180,
			hide : true
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
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '审批日期',
			width : 80,
			sortable : true,
			hide : true
		}, {
			name : 'InputDate',
			display : '录入时间',
			sortable : true,
			width : 130
		}, {
			name : 'subCheckDT',
			display : '提交检查时间',
			sortable : true,
			width : 130
		}, {
			name : 'UpdateDT',
			display : '更新时间',
			sortable : true,
			width : 130,
			hide : true
		}],
		//打开的是expense中的方法 -- 情形比较特殊
		toEditConfig : {
			showMenuFn : function(row){
				if( row.isNew == '1' && row.Status == '部门检查' && row.ExaStatus == '编辑'){
					return true;
				}
				return false;
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.CheckAmount != row.Amount){
					alert('单据金额发生变更，检查前单据金额为: ' + row.Amount + ',检查后单据金额为:' + row.CheckAmount + ',请重新确认此报销单或将单据提交到报销人确认' );
					return false;
				}
				showModalWin("?model=finance_expense_expense&action=toEditCheck&id=" + row.id );
			}
		},
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
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toView&id="+ rowData.id )
			}
		},
		buttonsEx : [{
			text: "返回列表",
			icon: 'view',
			action: function() {
				location = '?model=finance_expense_expense&action=checkEsmList';
			}
		}],
		menusEx : [{
				text: "管理明细",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.Status == "部门检查"){
						return true;
					}
					return false;
				},
				action: function(row) {
					showOpenWin("?model=engineering_cost_esmcostdetail&action=manageExpenseList&expenseId=" + row.id ,0,600,1000);
				}
			},{
				text: "提交确认",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.CheckAmount*1 != row.Amount*1){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(confirm('确认要将单据提交确认吗？')){
						$.ajax({
							type : "POST",
							url : "?model=finance_expense_expense&action=handConfirm",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == '1') {
									alert('提交成功！');
									show_page(1);
								}else{
									alert("提交失败! ");
								}
							}
						});
					}
				}
			},{
				text: "部门收单",
				icon: 'edit',
				showMenuFn : function(row){
					if( row.isNotReced == '1' && row.Status != "编辑"){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(confirm('确认收单吗？')){
						$.ajax({
							type : "POST",
							url : "?model=finance_expense_expense&action=ajaxDeptRec",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == '1') {
									alert('收单成功！');
									show_page(1);
								}else{
									alert("收单失败! ");
								}
							}
						});
					}
				}
			},{
				text: "上交财务",
				icon: 'edit',
				showMenuFn : function(row){
					if( row.isNotReced == '0' && row.isHandUp == "0"){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(confirm('确认上交财务吗？')){
						$.ajax({
							type : "POST",
							url : "?model=finance_expense_expense&action=ajaxHandFinance",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == '1') {
									alert('上交成功！');
									show_page(1);
								}else{
									alert("上交失败! ");
								}
							}
						});
					}
				}
			},{
				text: "重新编辑",
				icon: 'edit',
				showMenuFn : function(row){
				if( row.isNew == '1' && row.Status == '部门检查' && row.ExaStatus == '打回'){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(row.CheckAmount != row.Amount){
						alert('单据金额发生变更，检查前单据金额为: ' + row.Amount + ',检查后单据金额为:' + row.CheckAmount + ',请重新确认此报销单或将单据提交到报销人确认' );
						return false;
					}
					showModalWin("?model=finance_expense_expense&action=toEditCheck&id=" + row.id );
				}
			},{
				text: "提交审批",
				icon: 'edit',
				showMenuFn : function(row){
					if( row.isNew == '1' && row.Status == '部门检查'){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(row.CheckAmount*1 != row.Amount*1){
						alert('单据金额发生变更，检查前单据金额为: ' + row.Amount + ',检查后单据金额为:' + row.CheckAmount + ',请重新确认此报销单或将单据提交到报销人确认' );
						return false;
					}
					if(row.isLate == "1"){
						showThickboxWin('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.Amount
							+ '&billDept=' + row.CostBelongDeptId
							+ '&billCompany=' + row.CostBelongComId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}else{
						showThickboxWin('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.Amount
							+ '&billDept=' + row.CostBelongDeptId
							+ '&billCompany=' + row.CostBelongComId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}
				}
			},{
				text: "审批情况",
				icon: 'view',
				showMenuFn : function(row){
					if(row.ExaStatus != '编辑'){
						return true;
					}
					return false;
				},
				action: function(row) {
					showThickboxWin('controller/common/readview.php?itemtype=cost_summary_list'
						+ '&pid=' + row.id
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			},{
				text: "打回",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.Status == '打回' || row.Status == '部门检查'){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(row.CheckAmount != row.Amount){
						alert('单据金额发生变更，检查前单据金额为: ' + row.Amount + ',检查后单据金额为:' + row.CheckAmount + ',请重新确认此报销单或将单据提交到报销人确认' );
						return false;
					}
					if(confirm('确认要打回此单据吗？')){
						$.ajax({
							type : "POST",
							url : "?model=finance_expense_expense&action=ajaxBack",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == '1') {
									alert('打回成功！');
									show_page(1);
								}else{
									alert("打回失败! ");
								}
							}
						});
					}
				}
			},{
				text: "打单",
				icon: 'print',
				showMenuFn : function(row){
					if(row.Status != '编辑' && row.Status != '打回' && row.Status != '部门检查'){
						return true;
					}
					return false;
				},
				action: function(row) {
					showOpenWin("?model=cost_bill_billcheck&action=print_bill&billno=" + row.BillNo);
				}
			}],
		//过滤数据
		comboEx : [{
			text : '报销类型',
			key : 'DetailType',
			data : [{
				text : '部门费用',
				value : '1'
			}, {
				text : '合同项目费用',
				value : '2'
			}, {
				text : '研发费用',
				value : '3'
			}, {
				text : '售前费用',
				value : '4'
			}, {
				text : '售后费用',
				value : '5'
			}, {
				text : '旧报销单',
				value : '0'
			}]
		},{
			text : '单据状态',
			key : 'Status',
			value : '部门检查',
			data : [{
				text : '编辑',
				value : '编辑'
			}, {
				text : '部门检查',
				value : '部门检查'
			}, {
				text : '等待确认',
				value : '等待确认'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '财务审核',
				value : '财务审核'
			}, {
				text : '出纳付款',
				value : '出纳付款'
			}, {
				text : '完成',
				value : '完成'
			}]
		}],
		searchitems : [{
			display : "审批单号",
			name : 'BillNoSearch'
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
			name : 'InputManNameSearch'
		}, {
			display : "事由",
			name : 'PurposeSearch'
		}],
		sortname : "c.UpdateDT"
	});
});