var show_page = function(page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function() {
	//数据过滤条件
	var param = {};
	var comboEx = [{
		text : '优先级',
		key : 'urgentLevelCode',
		datacode : 'SCJHYXJ'
	}];
	if ($("#finish").val() == 'yes') {
		param = {
			docStatusIn : '2,3'
		};
		var comboExArr = {
			text : '单据状态',
			key : 'docStatus',
			data : [{
				text : '已完成',
				value : '2'
			},{
				text : '已关闭',
				value : '3'
			}]
		};
		comboEx.push(comboExArr);
	} else {
		param = {
			docStatusIn : '0,1'
		};
		var comboExArr = {
			text : '单据状态',
			key : 'docStatus',
			data : [{
				text : '未确定',
				value : '0'
			},{
				text : '执行中',
				value : '1'
			}]
		};
		comboEx.push(comboExArr);
	}

	$("#produceplanGrid").yxgrid({
		model: 'produce_plan_produceplan',
		param : param,
		title: '生产计划',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'relDocCode',
			display: '源单编号',
			sortable: true
		},{
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width : 140,
			process : function (v ,row) {
				if (row.docStatus == 0) {
					v = '<img title="新增的生产计划" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.planNum > row.stockNum) {
						var nowData = new Date();
						var dateArr = (row.planEndDate).split('-');
						var planEndDate = new Date(dateArr[0] ,parseInt(dateArr[1]) ,parseInt(dateArr[2]));
						if (nowData.getTime() > planEndDate.getTime()) {
							v = '<img title="超时的生产计划" src="images/icon/hred.gif">' + v;
						}
					}
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'docStatus',
			display: '单据状态',
			sortable: true,
			width : 60,
			align : 'center',
			process : function(v ,row) {
				switch (v) {
					case '0' : return "未确定";break;
					case '1' : return "执行中";break;
					case '2' : return "已完成";break;
					case '3' : return "已关闭";break;
					default : return "--";
				}
			}
		},{
			name: 'urgentLevel',
			display: '优先级',
			sortable: true,
			align : 'center'
		},{
			name: 'docDate',
			display: '单据日期',
			sortable: true,
			width : 80,
			align : 'center'
		},{
			name: 'proType',
			display: '物料类型',
			sortable: true
		},{
			name: 'productName',
			display: '配置名称',
			sortable: true,
			width : 200
		},{
			name: 'productCode',
			display: '配置编码',
			sortable: true
		},{
			name: 'planNum',
			display: '数量',
			sortable: true,
			width : 60
		},{
			name: 'qualifiedNum',
			display: '质检合格数量',
			sortable: true,
			width : 80
		},{
			name: 'stockNum',
			display: '入库数量',
			sortable: true,
			width : 60
		},{
			name: 'taskCode',
			display: '生产任务单号',
			sortable: true,
			width : 120
		},{
			name: 'applyDocCode',
			display: '生产申请单号',
			sortable: true
		},{
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			width : 150
		},{
			name: 'productionBatch',
			display: '生产批次',
			sortable: true
		},{
			name: 'planStartDate',
			display: '计划开始时间',
			sortable: true,
			align : 'center'
		},{
			name: 'planEndDate',
			display: '计划结束时间',
			sortable: true,
			align : 'center'
		},{
			name: 'chargeUserName',
			display: '责任人',
			sortable: true,
			align : 'center'
		},{
			name: 'saleUserName',
			display: '销售代表',
			sortable: true,
			align : 'center'
		},{
			name: 'deliveryDate',
			display: '交货日期',
			sortable: true,
			align : 'center'
		},{
			name: 'remark',
			display: '备注',
			sortable: true,
			width : 350
		}],

		//扩展菜单
		buttonsEx : [{
			name : 'excelOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=produce_plan_produceplan&action=toExcelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
			}
		}],

		//扩展右键菜单
		menusEx : [{
			text : '打印',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toView&id=' + row.id ,'1');
			}
		},{
			text : '库存信息',
			icon : 'view',
			action : function(row) {
				showModalWin("index1.php?model=produce_apply_produceapply&action=toStatisticsProduct&code=" + row.productCode ,'1');
			}
		}
//		,{
//			text : '关闭',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.docStatus == '0' || row.docStatus == '1') {
//					return true;
//				} else {
//					return false;
//				}
//			},
//			action : function(row) {
//				if (window.confirm("确认要关闭?")) {
//					$.ajax({
//						type : "POST",
//						url : "?model=produce_plan_produceplan&action=closePlan",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == '0') {
//								alert('关闭成功！');
//								show_page();
//							} else {
//								alert('关闭失败！');
//							}
//						}
//					});
//				}
//			}
//		}
		],

		//下拉过滤
		comboEx : comboEx,

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_produceplan&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		},{
			name: 'docDate',
			display: '单据日期'
		},{
			name: 'productName',
			display: '配置名称'
		},{
			name: 'productCode',
			display: '配置编码'
		},{
			name: 'taskCode',
			display: '生产任务单号'
		},{
			name: 'relDocCode',
			display: '合同编号'
		},{
			name: 'applyDocCode',
			display: '生产申请单号'
		},{
			name: 'customerName',
			display: '客户名称'
		},{
			name: 'productionBatch',
			display: '生产批次'
		},{
			name: 'planStartDate',
			display: '计划开始时间'
		},{
			name: 'planEndDate',
			display: '计划结束时间'
		},{
			name: 'chargeUserName',
			display: '责任人'
		},{
			name: 'urgentLevel',
			display: '优先级'
		},{
			name: 'saleUserName',
			display: '销售代表'
		},{
			name: 'deliveryDate',
			display: '交货日期'
		}]
	});
});